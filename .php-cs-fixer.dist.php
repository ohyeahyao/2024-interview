<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

return (new Config())
    ->setParallelConfig(ParallelConfigFactory::detect()) // @TODO 4.0 no need to call this manually
    ->setRiskyAllowed(true)
    ->setRules([
        // https://cs.symfony.com/doc/ruleSets/index.html
        '@PSR12'                 => true,
        '@PhpCsFixer'            => true,
        '@PhpCsFixer:risky'      => true,
        '@PHP74Migration'        => true,
        '@PHP74Migration:risky'  => true,
        'array_syntax'           => ['syntax' => 'short'], // Use short array syntax
        'binary_operator_spaces' => [
            'default' => 'align_single_space_minimal',
        ], // Align binary operators
        'blank_line_after_namespace'   => true, // Add blank line after namespace
        'blank_line_after_opening_tag' => true, // Add blank line after PHP opening tag
        'blank_line_before_statement'  => [
            'statements' => ['return'],
        ], // Add blank line before return statements
        'braces' => [
            'position_after_functions_and_oop_constructs' => 'next',
            'position_after_anonymous_constructs'         => 'next',
        ], // Control brace style for functions and classes
        'cast_spaces'             => ['space' => 'single'], // Single space after cast
        'concat_space'            => ['spacing' => 'one'], // One space for concatenation
        'declare_equal_normalize' => ['space' => 'none'], // Normalize declare statements
        'function_typehint_space' => true, // Ensure space after function typehints
        'include'                 => true, // Standardize include statements
        'lowercase_cast'          => true, // Casts should be lowercase
        'no_extra_blank_lines'    => [
            'tokens' => [
                'extra',
                'throw',
                'use',
            ],
        ], // Remove extra blank lines
        'no_unused_imports'                 => true, // Remove unused use statements
        'not_operator_with_successor_space' => true, // Ensure space after not operator
        'ordered_imports'                   => ['sort_algorithm' => 'alpha'], // Alphabetically order use statements
        'phpdoc_align'                      => ['align' => 'vertical'], // Align PHPDoc
        'phpdoc_indent'                     => true, // Indent PHPDoc
        'phpdoc_no_alias_tag'               => ['replacements' => [
            'property-read'  => 'property',
            'property-write' => 'property',
            'type'           => 'var',
            'link'           => 'see',
        ]], // Normalize PHPDoc tags
        'phpdoc_scalar'                   => true, // Scalar types in PHPDoc should be lowercase
        'phpdoc_single_line_var_spacing'  => true, // Single line PHPDoc var spacing
        'phpdoc_summary'                  => true, // PHPDoc must have a summary
        'phpdoc_to_comment'               => false, // Do not convert PHPDoc to regular comments
        'phpdoc_trim'                     => true, // Trim PHPDoc comments
        'phpdoc_types'                    => true, // Normalize PHPDoc types
        'single_quote'                    => true, // Use single quotes for strings
        'space_after_semicolon'           => true, // Space after semicolon
        'standardize_not_equals'          => true, // Use !== instead of <>
        'ternary_operator_spaces'         => true, // Ensure spacing for ternary operators
        'trim_array_spaces'               => true, // Trim spaces in arrays
        'unary_operator_spaces'           => true, // Ensure spacing for unary operators
        'whitespace_after_comma_in_array' => true, // Ensure space after comma in arrays
    ])
    // ->setRules([
        // '@PHP74Migration' => true,
        // '@PHP74Migration:risky' => true,
        // '@PHPUnit100Migration:risky' => true,
        // '@PhpCsFixer' => true,
        // '@PhpCsFixer:risky' => true,
        // 'general_phpdoc_annotation_remove' => ['annotations' => ['expectedDeprecation']], // one should use PHPUnit built-in method instead
        // 'modernize_strpos' => true, // needs PHP 8+ or polyfill
        // 'no_useless_concat_operator' => false, // TODO switch back on when the `src/Console/Application.php` no longer needs the concat
        // 'numeric_literal_separator' => true,
    // ])
    ->setFinder(
        (new Finder())
            ->ignoreDotFiles(false)
            ->ignoreVCSIgnored(true)
            ->exclude(['dev-tools/phpstan', 'tests/Fixtures'])
            ->in(__DIR__)
    )
;
