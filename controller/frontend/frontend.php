<?php
class Frontend
{
    private $twig;

    public function __construct(){
        $loader = new Twig_Loader_Filesystem('D:\wamp64\www\Projet 5\Blog\view\frontend\templates');
        $this->twig = new Twig_Environment($loader); array('cache' => false);
    }

    public function blog(){
        $template = 'blog.twig';
        return $this->twig->render($template);
    }

    public function index(){
        $template = 'index.twig';
        return $this->twig->render($template);
    }

    public function login_register(){
        $template = 'login_register.twig';
        return $this->twig->render($template);
    }
}