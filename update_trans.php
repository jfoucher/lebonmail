<?php

$tplDir = dirname(__FILE__) . '/templates';
$tmpDir = '/tmp/cache/';
require_once 'Views/Twig/Autoloader.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem($tplDir);

$twig = new Twig_Environment($loader, array(
    'cache' => $tmpDir,
    'auto_reload' => true
));
$twig->addExtension(new Twig_Extensions_Extension_I18n());
// configure Twig the way you want

// iterate over all your templates
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($tplDir), RecursiveIteratorIterator::LEAVES_ONLY) as $file) {
    // force compilation
    echo $file . "\r\n";

    try {
        if (is_file($file)) {
            $twig->loadTemplate(str_replace($tplDir . '/', '', $file));
        }
    } catch (ErrorException $e) {
        echo 'error';
    }
}
