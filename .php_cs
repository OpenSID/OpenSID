<?php

/**
 * Rules we follow are from PSR-2 as well as the rectified PSR-2 guide.
 *
 * - https://github.com/FriendsOfPHP/PHP-CS-Fixer
 * - https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
 * - https://github.com/php-fig-rectified/fig-rectified-standards/blob/master/PSR-2-R-coding-style-guide-additions.md
 *
 * If something isn't addressed in either of those, some other common community rules are
 * used that might not be addressed explicitly in PSR-2 in order to improve code quality
 * (so that devs don't need to comment on them in Code Reviews).
 *
 * For instance: removing trailing white space, removing extra line breaks where
 * they're not needed (back to back, beginning or end of function/class, etc.),
 * adding trailing commas in the last line of an array, etc.
 */

$finder = PhpCsFixer\Finder::create()
    ->exclude('node_modules')
    ->exclude('vendor')
    ->in(__DIR__);

return PhpCsFixer\Config::create()
    ->setRules([        
        'braces' => [
            'position_after_control_structures' => 'next',
            'position_after_functions_and_oop_constructs' => 'next',
            'allow_single_line_closure' => true,
        ]
    ])
    ->setFinder($finder);