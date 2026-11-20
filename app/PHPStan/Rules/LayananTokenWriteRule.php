<?php

declare(strict_types=1);

namespace App\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Melarang penulisan setting token layanan di luar repository yang disahkan.
 *
 * Untuk `layanan_opendesa_token`: WAJIB melalui App\Repositories\LayananTokenRepository
 * yang memverifikasi token via TokenDecoder sebelum menyimpan.
 *
 * Untuk token layanan lain (mapbox_key, google_api_key, google_recaptcha_*):
 * Diperbolehkan melalui SettingAplikasiRepository::updateWithKey() dengan validasi
 * di dalam method tersebut.
 *
 * Mencegah regresi CWE-345/CWE-347 (lihat #6663): token dari respon API
 * sempat ditulis mentah ke setting tanpa verifikasi.
 *
 * @implements Rule<MethodCall>
 */
final class LayananTokenWriteRule implements Rule
{
    private const KELAS_SAH_OPENSESA = 'App\\Repositories\\LayananTokenRepository';
    private const KELAS_SAH_SETTING = 'App\\Repositories\\SettingAplikasiRepository';

    /** Daftar key token layanan yang perlu validasi khusus. */
    private const KEY_TOKEN_LAYANAN = [
        'layanan_opendesa_token',
        'mapbox_key',
        'google_api_key',
        'google_recaptcha_site_key',
        'google_recaptcha_secret_key',
    ];

    /** Method yang diperbolehkan untuk update token layanan via SettingAplikasiRepository. */
    private const METHOD_UPDATE_WITH_KEY = 'updatewithkey';

    /** Method generik penulisan yang perlu dicek. */
    private const METHOD_TULIS = [
        'update', 'updateorcreate', 'create', 'insert', 'save',
    ];

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

        // Cek apakah ini updateWithKey() - method yang disahkan untuk token layanan
        if ($methodName === self::METHOD_UPDATE_WITH_KEY) {
            return $this->processUpdateWithKey($node, $scope);
        }

        // Cek method penulisan generik lainnya
        if (in_array($methodName, self::METHOD_TULIS, true)) {
            return $this->processMethodTulis($node, $scope);
        }

        return [];
    }

    private function processUpdateWithKey(MethodCall $node, Scope $scope): array
    {
        $keyToken = $this->getFirstArgKey($node);

        if ($keyToken === null) {
            return [];
        }

        // Cek apakah key adalah token layanan
        if (! in_array($keyToken, self::KEY_TOKEN_LAYANAN, true)) {
            return [];
        }

        // Untuk layanan_opendesa_token, WAJIB via LayananTokenRepository
        if ($keyToken === 'layanan_opendesa_token') {
            $kelas = $scope->getClassReflection();
            if ($kelas !== null && $kelas->is(self::KELAS_SAH_OPENSESA)) {
                return [];
            }

            return [
                RuleErrorBuilder::message(
                    'Penulisan setting layanan_opendesa_token dilarang di sini; gunakan '
                    . 'App\\Repositories\\LayananTokenRepository::simpan() yang memverifikasi token '
                    . 'lebih dulu (mencegah regresi CWE-345/CWE-347, lihat #6663).'
                )
                    ->identifier('opensid.layananTokenWrite')
                    ->build(),
            ];
        }

        // Untuk token layanan lain, diperbolehkan via SettingAplikasiRepository::updateWithKey()
        // dengan validasi di dalam method tersebut
        $kelas = $scope->getClassReflection();
        if ($kelas !== null && $kelas->is(self::KELAS_SAH_SETTING)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                "Penulisan setting {$keyToken} dilarang di sini; gunakan "
                . 'App\\Repositories\\SettingAplikasiRepository::updateWithKey() yang melakukan '
                . 'validasi value sebelum menyimpan.'
            )
                ->identifier('opensid.layananTokenWrite')
                ->build(),
        ];
    }

    private function processMethodTulis(MethodCall $node, Scope $scope): array
    {
        if (! $this->argMenyebutKeyToken($node)) {
            return [];
        }

        $kelas = $scope->getClassReflection();
        if ($kelas !== null && $kelas->is(self::KELAS_SAH_OPENSESA)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                'Penulisan setting layanan_opendesa_token dilarang di sini; gunakan '
                . 'App\\Repositories\\LayananTokenRepository::simpan() yang memverifikasi token '
                . 'lebih dulu (mencegah regresi CWE-345/CWE-347, lihat #6663).'
            )
                ->identifier('opensid.layananTokenWrite')
                ->build(),
        ];
    }

    private function getFirstArgKey(MethodCall $node): ?string
    {
        $args = $node->getArgs();
        if (empty($args)) {
            return null;
        }

        $firstArg = $args[0];
        if ($firstArg->value instanceof String_) {
            return $firstArg->value->value;
        }

        return null;
    }

    private function argMenyebutKeyToken(MethodCall $node): bool
    {
        foreach ($node->getArgs() as $arg) {
            if ($arg->value instanceof String_ && $arg->value->value === 'layanan_opendesa_token') {
                return true;
            }
        }

        return false;
    }
}
