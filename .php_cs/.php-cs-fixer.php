<?php

$finder = PhpCsFixer\Finder::create()
    ->files()
    ->name('*.php')
    ->in(__DIR__.'/../src')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        '@PSR12' => true,
        '@DoctrineAnnotation' => true,
        '@PhpCsFixer' => true,
        '@PHP81Migration' => true,
    ])
    ->setFinder($finder)
;
