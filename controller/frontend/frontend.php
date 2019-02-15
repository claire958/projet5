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

        $renderData = ['post' => $postManager->getListPost(), 'users' => $userManager->getListUser()];

        return $this->twig->render('blog.twig', $renderData);

    }

    /**
     * Affiche index.twig
     */
    public function index(){
        return $this->twig->render('index.twig');
    }

    /**
     * Affiche login_register.twig
     */
    public function login_register($messageErreur="", $messageValidation=""){

        $renterData = ['message' => $messageErreur, 'messageValidation' => $messageValidation];

        return $this->twig->render('login_register.twig', $renterData);
    }

    /**
     * Affiche articles.twig
     * On appelle la variable $article depuis la méthode getPost via la class PostManager
     */
    public function article(){
        $postManager = new \OpenClassrooms\Blog\Model\PostManager();
        $userManager = new OpenClassrooms\Blog\Model\UserManager();
        $commentManager = new OpenClassrooms\Blog\Model\CommentManager();

        $renderData = ['article' => $postManager->getPost($_GET['id']), 'users' => $userManager->getListUser(), 'comment' => $commentManager->getComment($_GET['id'])];

        return $this->twig->render('articles.twig', $renderData);

    }

    public function add_user($pseudo, $email, $password, $confirmPassword){
        if (!empty($pseudo) && !empty($email) && !empty($password) && !empty($confirmPassword)) {
            if($confirmPassword === $password){
                $passHache = password_hash($password, PASSWORD_DEFAULT);
                if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $email)) {

                    $getUser = new \OpenClassrooms\Blog\Model\UserManager();
                    $userData = $getUser->getUser($pseudo);

                    $getUserMail = new \OpenClassrooms\Blog\Model\UserManager();
                    $getUserMail->getUserMail($email);


                    if ($userData['pseudo'] != $pseudo) {
                        if ($getUserMail != $email){

                            $registerManager = new \OpenClassrooms\Blog\Model\UserManager();
                            $registerManager->addUser($pseudo, $email, $passHache);

                            echo "vous etes enregistré";
                            $this->login_register("", "Bravo ! Vous êtes enregistré ! Vous allez bientôt recevoir un email de confirmation.");
                        }
                        else{
                            echo "email déjà utilisé";
                        }
                    }
                    else{
                        echo "pseudo déjà utilisé";
                        $this->login_register("Le pseudo est déjà utilisé !");
                    }
                }
                else{
                    $this->login_register("L'email est invalide");
                }
            }
            else{
                return $this->login_register("Les mots de passe ne sont pas identiques !");

            }
        }
        else {
            echo "Un champs n'est pas renseigné.";
        }
    }
}