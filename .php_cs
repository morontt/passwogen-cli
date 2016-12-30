<?php

$finder = Symfony\CS\Finder::create()
    ->in([
        __DIR__ . '/bin',
        __DIR__ . '/src',
    ])
;

$config = Symfony\CS\Config::create()
    ->fixers([
        '-phpdoc_params',
        '-phpdoc_short_description',
        '-phpdoc_no_empty_return',
        '-pre_increment',
        '-spaces_cast',
        '-heredoc_to_nowdoc',
        'concat_with_spaces',
        'ordered_use',
    ])
    ->finder($finder)
;

return $config;
