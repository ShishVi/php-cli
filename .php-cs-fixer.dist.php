<?php
declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/config')
    ->in(__DIR__ . '/bin')
    ->exclude('vendor')
    ->exclude('var')
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        '@PSR12:risky' => true,
        '@PHP80Migration:risky' => true,

        // === Дополнительные правила сверх PSR-12 ===
        'declare_strict_types' => true,  // require declare(strict_types=1)
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
            'imports_order' => ['class', 'function', 'const'],
        ],
        'no_unused_imports' => true,
        'single_quote' => true,
        'array_syntax' => ['syntax' => 'short'],
        'trailing_comma_in_multiline' => [
            'elements' => ['arrays', 'arguments', 'parameters'],
        ],
        'concat_space' => ['spacing' => 'one'],
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_order' => true,
        'no_superfluous_phpdoc_tags' => true,
        'blank_line_before_statement' => [
            'statements' => ['return', 'throw', 'try'],
        ],
        'native_function_invocation' => [
            'include' => ['@compiler_optimized'],
            'scope' => 'namespaced',
        ],
        'global_namespace_import' => [
            'import_classes' => true,
            'import_functions' => false,
            'import_constants' => false,
        ],
    ])
    ->setFinder($finder)
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache');