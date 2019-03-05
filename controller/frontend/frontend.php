<?php

/**
 * Les fichiers suivant sont inclus dans frontend.php :
 */
require_once('model/frontend/Post.php');
require_once('model/frontend/User.php');
require_once('model/frontend/Comment.php');
require_once('model/frontend/PostManager.php');
require_once('model/frontend/UserManager.php');
require_once('model/frontend/CommentManager.php');


class Frontend
{
    private $twig;

    /**
     * On configure Twig pour qu'il charge les templates
     */
    public function __construct(){
       $loader = new Twig_Loader_Filesystem('D:\wamp64\www\Projet 5\Blog\view\frontend\templates');
       $this->twig = new Twig_Environment($loader); array('cache' => false);
    }

    /**
     * Affiche 404.twig
     */
    public function not_found(){
        return $this->twig->render('404.twig');
    }

    /**
     * Affiche blog.twig
     * On appelle la méthode getListPost via la class PostManager
     */
    public function blog(){
        $postManager = new OpenClassrooms\Blog\Model\PostManager();
        $userManager = new OpenClassrooms\Blog\Model\UserManager();

        $renderData = [
            'post' => $postManager->getListPost(),
            'users' => $userManager->getListUser(),
            // $pseudo = (condition) ?? si faux;
            'pseudo' => $_SESSION['pseudo'] ?? ''
        ];

        return $this->twig->render('blog.twig', $renderData);
    }

    /**
     * Affiche index.twig
     */
    public function index(){
        $renderData = [
            'pseudo' => $_SESSION['pseudo'] ?? ''
        ];
        return $this->twig->render('index.twig', $renderData);
    }

    /**
     * Affiche page_register.twig
     */
    public function register($messageErreur="", $messageValidation=""){

        $renderData = [
            'message' => $messageErreur,
            'messageValidation' => $messageValidation,
            // $pseudo = (condition) ?? si faux;
            'pseudo' => $_SESSION['pseudo'] ?? ''
        ];
        return $this->twig->render('page_register.twig', $renderData);
    }

    public function login($messageErreur="", $messageValidation=""){

        $renderData = [
            'message' => $messageErreur,
            'messageValidation' => $messageValidation,
            // $pseudo = (condition) ?? si faux;
            'pseudo' => $_SESSION['pseudo'] ?? ''
        ];
        return $this->twig->render('page_login.twig', $renderData);
    }

    /**
     * Affiche articles.twig
     * On appelle la variable $article depuis la méthode getPost via la class PostManager
     */
    public function article($messageErreur=""){
        $postManager = new \OpenClassrooms\Blog\Model\PostManager();
        $userManager = new OpenClassrooms\Blog\Model\UserManager();
        $commentManager = new OpenClassrooms\Blog\Model\CommentManager();

        $renderData = [
            'message' => $messageErreur,
            'article' => $postManager->getPost($_GET['id']),
            'users' => $userManager->getListUser(),
            'comment' => $commentManager->getComment($_GET['id']),
            // $pseudo = (condition) ?? si faux;
            'pseudo' => $_SESSION['pseudo'] ?? ''
        ];
        return $this->twig->render('articles.twig', $renderData);

    }

    public function add_user($pseudo, $email, $password, $confirmPassword){

        if (empty($pseudo) || empty($email) || empty($password) || empty($confirmPassword)) {
            return $this->register("Un champs n'est pas renseigné.");
        }
        if($confirmPassword !== $password) {
            return $this->register("Les mots de passe ne sont pas identiques !");
        }
        if (!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $email)) {
            return $this->register("L'email est invalide");
        }

        $getUser = new \OpenClassrooms\Blog\Model\UserManager();
        $userData = $getUser->getUser($pseudo);

        if ($userData != false) {
            return $this->register("Le pseudo est déjà utilisé !");
        }

        $getUserMail = new \OpenClassrooms\Blog\Model\UserManager();
        $getMailData = $getUserMail->getUserMail($email);

        if ($getMailData != false) {
            return $this->register("L'email est déjà utilisé");
        }

        if (!empty($errorList)) {
            return $this->register($errorList);
        }

        $passHash = password_hash($password, PASSWORD_DEFAULT);

        $registerManager = new \OpenClassrooms\Blog\Model\UserManager();
        $registerManager->addUser($pseudo, $email, $passHash);

        return $this->register("", "Bravo ! Votre demande d'enregistrement a été envoyée et est en attente d'approbation par un administrateur.");
    }

    public function connect_user($pseudo, $password){

        if (empty($pseudo) || empty($password)) {
            return $this->login("Un champs n'est pas renseigné.");
        }

        $getUser = new \OpenClassrooms\Blog\Model\UserManager();
        $userData = $getUser->getUser($pseudo);

        if ($userData == false) {
            return $this->login("Ce pseudo n'existe pas !");
        }

        $getPass = new \OpenClassrooms\Blog\Model\UserManager();
        $passData = $getPass->getUser($pseudo);

        $isPasswordCorrect = password_verify($password, $passData->getPassword());

        if (!$passData) {
            return $this->login("Mauvais identifiant ou mot de passe !");
        }

        if ($isPasswordCorrect) {

            $getUser = new \OpenClassrooms\Blog\Model\UserManager();
            $idUserData = $getUser->getUser($pseudo);

            $_SESSION['id_user'] = $idUserData->getIdUser();
            $_SESSION['pseudo'] = $idUserData->getPseudo();

            return $this->login("", "Vous êtes connecté ");
        }

        else{
            return $this->login("Mauvais identifiant ou mot de passe !");
        }
    }

    public function add_comment($comment, $idPost){

        if (empty($comment) || empty($idPost)) {
            return $this->article("Un champs n'est pas renseigné.");
        }

        if (!$_SESSION['id_user']){
            return $this->article(" Il faut vous connecter pour pouvoir poster un commentaire !");
        }

        $addComment = new \OpenClassrooms\Blog\Model\CommentManager();
        $postManager = new \OpenClassrooms\Blog\Model\PostManager();

        ['article' => $postManager->getPost($idPost)];

        $addComment->addComment($comment, $idPost, $_SESSION['id_user']);

        return $this->article("");
    }

    public function sign_out(){
        session_destroy();
        header('Location: ?');
    }

    public function dashboard(){
        $renderData = [
            'pseudo' => $_SESSION['pseudo'] ?? ''
        ];

        return $this->twig->render('dashboard.twig', $renderData);
    }
}