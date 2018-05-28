<?php

require_once("vendor/autoload.php");


$loader = new \Twig_Loader_Filesystem(__DIR__.'/templates');
$twig = new \Twig_Environment($loader); array('cache' => false);

if (isset($_GET['page']) && $_GET['page'] == "blog") {
  $template = 'blog.twig';
} else {
  $template = 'index.twig';
}

echo $twig->render($template);