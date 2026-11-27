<?php

namespace App\PHPStan\Rules;

use App\Attributes\AdminModul;
use App\Http\Controllers\Controller;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/** @implements Rule<Node\Stmt\Class_> */
class AdminModulAttributeRule implements Rule
{
    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
    ) {}

    public function getNodeType(): string
    {
        return Node\Stmt\Class_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->name === null || $node->extends === null) {
            return [];
        }

        $className = $scope->getNamespace() . '\\' . $node->name;

        if (! str_starts_with($className, 'App\\Http\\Controllers\\Admin\\')) {
            return [];
        }

        if (! $this->reflectionProvider->hasClass($className)) {
            return [];
        }

        $reflection = $this->reflectionProvider->getClass($className);

        if (! $reflection->isSubclassOf(Controller::class)) {
            return [];
        }

        $errors = $this->validateClassAttribute($node, $scope, $className);

        foreach ($node->getMethods() as $method) {
            $errors = [...$errors, ...$this->validateMethodAttributes($method, $scope, $className)];
        }

        return $errors;
    }

    /** @return list<\PHPStan\Rules\IdentifierRuleError> */
    private function validateClassAttribute(Node\Stmt\Class_ $node, Scope $scope, string $className): array
    {
        $attributes = $this->adminModulAttributes($node->attrGroups, $scope);

        if (count($attributes) === 0) {
            return [
                RuleErrorBuilder::message(
                    "Controller admin {$className} wajib mendeklarasikan attribute #[AdminModul]."
                )
                    ->identifier('adminModul.missingAttribute')
                    ->line($node->getStartLine())
                    ->build(),
            ];
        }

        if (count($attributes) > 1) {
            return [
                RuleErrorBuilder::message(
                    "Controller admin {$className} mendeklarasikan #[AdminModul] lebih dari satu kali — maksimal satu per class."
                )
                    ->identifier('adminModul.duplicateAttribute')
                    ->line($attributes[1]->getStartLine())
                    ->build(),
            ];
        }

        return $this->resolveModulArgument($attributes[0], $className);
    }

    /** @return list<\PHPStan\Rules\IdentifierRuleError> */
    private function resolveModulArgument(Node\Attribute $attribute, string $className): array
    {
        foreach ($attribute->args as $arg) {
            if ($arg->name?->toString() === 'modul') {
                return $this->validateSlug($arg->value, $className);
            }
        }

        $first = $attribute->args[0] ?? null;

        if ($first === null || $first->name !== null) {
            return [
                RuleErrorBuilder::message(
                    "Attribute #[AdminModul] pada {$className} wajib memiliki argumen modul."
                )
                    ->identifier('adminModul.invalidSlug')
                    ->line($attribute->getStartLine())
                    ->build(),
            ];
        }

        return $this->validateSlug($first->value, $className);
    }

    /** @param list<\PhpParser\Node\AttributeGroup> $attrGroups @return list<\PhpParser\Node\Attribute> */
    private function adminModulAttributes(array $attrGroups, Scope $scope): array
    {
        $attributes = [];

        foreach ($attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attribute) {
                if ($scope->resolveName($attribute->name) === AdminModul::class) {
                    $attributes[] = $attribute;
                }
            }
        }

        return $attributes;
    }

    /** @return list<\PHPStan\Rules\IdentifierRuleError> */
    private function validateMethodAttributes(Node\Stmt\ClassMethod $method, Scope $scope, string $className): array
    {
        $attributes = $this->adminModulAttributes($method->attrGroups, $scope);

        if (count($attributes) <= 1) {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                "Method {$className}::{$method->name}() mendeklarasikan #[AdminModul] lebih dari satu kali — maksimal satu per method."
            )
                ->identifier('adminModul.duplicateAttribute')
                ->line($attributes[1]->getStartLine())
                ->build(),
        ];
    }

    /** @return list<\PHPStan\Rules\IdentifierRuleError> */
    private function validateSlug(Node\Expr $modul, string $className): array
    {
        if (! $modul instanceof Node\Scalar\String_) {
            return [
                RuleErrorBuilder::message(
                    "Attribute #[AdminModul] pada {$className} harus memakai literal string untuk argumen modul."
                )
                    ->identifier('adminModul.invalidSlug')
                    ->line($modul->getStartLine())
                    ->build(),
            ];
        }

        $value = $modul->value;

        if ($value !== strtolower($value) || preg_match('/^[a-z0-9-]+$/', $value) !== 1) {
            return [
                RuleErrorBuilder::message(
                    "Attribute #[AdminModul] pada {$className} memakai modul '{$value}' — harus slug lowercase (huruf kecil, angka, dan dash saja)."
                )
                    ->identifier('adminModul.invalidSlug')
                    ->line($modul->getStartLine())
                    ->build(),
            ];
        }

        return [];
    }
}
