<?php

declare(strict_types=1);

namespace App\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Melarang dekode JWT token berlangganan secara langsung di luar App\Services\Layanan\TokenDecoder.
 * Mencegah regresi CWE-347 (lihat #6662): dekode token JWT sempat dilakukan
 * di berbagai tempat tanpa verifikasi signature yang proper.
 *
 * @implements Rule<MethodCall>
 */
final class RawTokenDecodeRule implements Rule
{
    /** Satu-satunya kelas yang boleh mendekode token berlangganan. */
    private const KELAS_SAH = 'App\\Services\\Layanan\\TokenDecoder';

    /** Method JWT decoding yang dilarang dipanggil langsung. */
    private const METHOD_TERLARANG = [
        'decode', // Firebase\JWT\JWT::decode
    ];

    /** Kelas yang berisi method terlarang. */
    private const KELAS_JWT = 'Firebase\\JWT\\JWT';

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (! $node->name instanceof Identifier) {
            return [];
        }

        $methodName = strtolower($node->name->toString());

        if (! in_array($methodName, self::METHOD_TERLARANG, true)) {
            return [];
        }

        // Cek apakah ini pemanggilan JWT::decode
        $type = $scope->getType($node->var);
        if ($type->getObjectClassNames() === [] || ! in_array(self::KELAS_JWT, $type->getObjectClassNames(), true)) {
            return [];
        }

        // Izinkan jika dipanggil dari TokenDecoder
        $kelas = $scope->getClassReflection();
        if ($kelas !== null && $kelas->getName() === self::KELAS_SAH) {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                'Dekode JWT token berlangganan secara langsung dilarang di sini; gunakan '
                . 'App\\Services\\Layanan\\TokenDecoder yang telah melakukan verifikasi signature '
                . 'dan validasi payload (mencegah regresi CWE-347, lihat #6662).'
            )
                ->identifier('opensid.rawTokenDecode')
                ->build(),
        ];
    }
}
