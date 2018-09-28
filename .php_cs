<?php

return PhpCsFixer\Config::create()
    ->setUsingCache(false)
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => true,
        'single_import_per_statement' => false,
        'no_whitespace_in_blank_line' => true,
        'no_unused_imports' => true,
        'no_blank_lines_before_namespace' => false,
        'blank_line_before_return' => true,
        'binary_operator_spaces' => true,
        'cast_spaces' => true,
        'short_scalar_cast' => true,
        'declare_equal_normalize' => true,
        'method_argument_space' => true,
        'method_separation' => true,
        'no_leading_namespace_whitespace' => true,
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_whitespace_before_comma_in_array' => true,
        'trim_array_spaces' => true,
        //'single_quote' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_align' => true,
        'phpdoc_scalar' => true,
        'phpdoc_indent' => true,
        'phpdoc_order' => true,
        'phpdoc_separation' => true,
        'no_empty_statement' => true,
        'concat_space' => ['spacing' => 'one'],
        'no_multiline_whitespace_before_semicolons' => true,
        'no_leading_import_slash' => true,
        'no_trailing_comma_in_list_call' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_align' => true,
        'phpdoc_no_empty_return' => true,
        'return_type_declaration' => true,
        'ternary_operator_spaces' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
    );