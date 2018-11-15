<?php
require_once '../vendor/autoload.php';

$loader = new Twig_Loader_Filesystem('./');
$twig = new Twig_Environment($loader, array(
//    'cache' => './compilation_cache',
));
$entry = json_decode(file_get_contents('entry.json'),true);
echo $twig->render('ticket.html', $entry);
