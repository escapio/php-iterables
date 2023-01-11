<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__);

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PSR12' => true,
    '@PHP81Migration' => true,
    'array_syntax' => ['syntax' => 'short'],
])
    ->setFinder($finder);
