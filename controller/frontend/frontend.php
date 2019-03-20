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



    //SESSIONS

    /**
     * Déconnexion session
     */
    public function signOut(){
        session_destroy();
        header('Location: ?');
    }



    //AFFICHAGE DES PAGES

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
     * Affiche blog.twig
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

    /**
     * Affiche page_login.twig
     */
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

    /**
     * Affiche dashboard.twig
     */
    public function dashboard($name, $messageDashboard=""){

        $postManager = new \OpenClassrooms\Blog\Model\PostManager();
        $userManager = new \OpenClassrooms\Blog\Model\UserManager();
        $commentManager = new \OpenClassrooms\Blog\Model\CommentManager();

        $renderData = [
            'pseudo' => $_SESSION['pseudo'] ?? '',
            'name' => $name,
            'post' => $postManager->getListPost(),
            'users' => $userManager->getListUser(),
            'comment' => $commentManager->getListComment(),
            'message' => $messageDashboard,
        ];

        if ($name == "form_update_post"){
            $renderData = [
                'pseudo' => $_SESSION['pseudo'] ?? '',
                'name' => $name,
                'message' => $messageDashboard,
                'article' => $postManager->getPost($_GET['id']),
            ];
            return $this->twig->render('dashboard.twig', $renderData);
        }

        if ($name == "form_update_comment"){
            $renderData = [
                'pseudo' => $_SESSION['pseudo'] ?? '',
                'name' => $name,
                'message' => $messageDashboard,
                'commentUpdate' => $commentManager->getCommentUpdate($_GET['id'])
            ];
            return $this->twig->render('dashboard.twig', $renderData);
        }

        return $this->twig->render('dashboard.twig', $renderData);
    }



    // UTILISATEURS

    /**
     * Ajouter un utilisateur à la bdd
     */
    public function addUser($pseudo, $email, $password, $confirmPassword){

        if (empty($pseudo) || empty($email) || empty($password) || empty($confirmPassword)) {
            return $this->register("Un champs n'est pas renseigné.");
        }
        if($confirmPassword !== $password) {
            return $this->register("Les mots de passe ne sont pas identiques !");
        }
        if (!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $email)) {
            return $this->register("L'email est invalide");
        }

        $userManager = new \OpenClassrooms\Blog\Model\UserManager();
        $userData = $userManager->getUser($pseudo);

        if ($userData != false) {
            return $this->register("Ce pseudo est déjà utilisé !");
        }

        $userManager = new \OpenClassrooms\Blog\Model\UserManager();
        $getMailData = $userManager->getUserMail($email);

        if ($getMailData != false) {
            return $this->register("Cet email est déjà utilisé !");
        }

        if (!empty($errorList)) {
            return $this->register($errorList);
        }

        $passHash = password_hash($password, PASSWORD_DEFAULT);

        $user = new \OpenClassrooms\Blog\Model\User("");
        $user->setPseudo($pseudo);
        $user->setEmail($email);
        $user->setPassword($passHash);
        $user->setRole("user");
        $user->setValidation("non");

        $userManager = new \OpenClassrooms\Blog\Model\UserManager();
        $userManager->addUser($user);

        return $this->register("", "Bravo ! Votre demande d'enregistrement a été envoyée et est en attente d'approbation par un administrateur.");
    }

    /**
     * Connexion d'un utilisateur au dashboard
     */
    public function connectUser($pseudo, $password){

        if (empty($pseudo) || empty($password)) {
            return $this->login("Un champs n'est pas renseigné.");
        }

        $userManager = new \OpenClassrooms\Blog\Model\UserManager();
        $userData = $userManager->getUser($pseudo);

        if ($userData == false) {
            return $this->login("Ce pseudo n'existe pas !");
        }

        $userManager = new \OpenClassrooms\Blog\Model\UserManager();
        $passData = $userManager->getUser($pseudo);

        $isPasswordCorrect = password_verify($password, $passData->getPassword());

        if (!$passData) {
            return $this->login("Mauvais identifiant ou mot de passe !");
        }

        if ($isPasswordCorrect) {

            $userManager = new \OpenClassrooms\Blog\Model\UserManager();
            $newUser = $userManager->getUser($pseudo);

            $_SESSION['id_user'] = $newUser->getIdUser();
            $_SESSION['pseudo'] = $newUser->getPseudo();

            return $this->dashboard("dashboard","");
        }

        else{
            return $this->login("Mauvais identifiant ou mot de passe !");
        }
    }

    /**
     * Validation d'un utilisateur
     */
    public function validationUser($idUser){

        $user = new \OpenClassrooms\Blog\Model\User("");
        $user->setIdUser($idUser);
        $user->setValidation("oui");

        $userManager = new \OpenClassrooms\Blog\Model\UserManager();
        $userManager->validationUser($user);

        return $this->dashboard("list_users", "Votre utilisateur a bien été validé !");
    }

    /**
     * Supprimer un utilisateur
     */
    public function deleteUser($idUser){

        $user = new \OpenClassrooms\Blog\Model\User("");
        $user->setIdUser($idUser);

        $userManager = new \OpenClassrooms\Blog\Model\UserManager();
        $userManager->deleteUser($user);

        return $this->dashboard("list_users", "Votre utilisateur a bien été supprimé !");
    }



    //COMMENTAIRES

    /**
     * Ajouter un commentaire
     */
    public function addComment($comment, $idPost){

        if (empty($comment) || empty($idPost)) {
            return $this->article("Un champs n'est pas renseigné.");
        }

        if (empty ($_SESSION['id_user'])){
            return $this->article(" Il faut vous connecter pour pouvoir poster un commentaire !");
        }

        $newComment = new \OpenClassrooms\Blog\Model\Comment("");
        $newComment->setComment($comment);
        $newComment->setIdPost($idPost);
        $newComment->setIdUser($_SESSION['id_user']);
        $newComment->setValidation("non");

        $commentManager = new \OpenClassrooms\Blog\Model\CommentManager();
        $commentManager->addComment($newComment);

        $postManager = new \OpenClassrooms\Blog\Model\PostManager();
        ['article' => $postManager->getPost($idPost)];

        return $this->article("");
    }

    /**
     * Modifier un commentaire
     */
    public function updateComment($comment, $idComment){

        $newComment = new \OpenClassrooms\Blog\Model\Comment("");
        $newComment->setComment($comment);
        $newComment->setIdComment($idComment);
        $newComment->setIdUser($_SESSION['id_user']);

        $commentManager = new \OpenClassrooms\Blog\Model\CommentManager();
        $commentManager->updateComment($newComment);

        return $this->dashboard("comments_list_dashboard", "Votre commentaire a bien été modifié !");
    }

    /**
     * Valider un commentaire
     */
    public function validationComment($idComment){

        $newComment = new \OpenClassrooms\Blog\Model\Comment("");
        $newComment->setValidation("oui");
        $newComment->setIdComment($idComment);

        $commentManager = new \OpenClassrooms\Blog\Model\CommentManager();
        $commentManager->validationComment($newComment);

        return $this->dashboard("comments_list_dashboard", "Votre commentaire a bien été validé !");
    }

    /**
     * Supprimer un commentaire
     */
    public function deleteComment($idComment){

        $newComment = new \OpenClassrooms\Blog\Model\Comment("");
        $newComment->setIdComment($idComment);

        $commentManager = new \OpenClassrooms\Blog\Model\CommentManager();
        $commentManager->deleteComment($newComment);

        return $this->dashboard("comments_list_dashboard", "Votre commentaire a bien été supprimé !");
    }



    //ARTICLES

    /**
     * Ajouter un article
     */
    public function addPost($titre, $introduction, $contenu){

        $post = new \OpenClassrooms\Blog\Model\post("");
        $post->setTitle($titre);
        $post->setIntroduction($introduction);
        $post->setContent($contenu);
        $post->setIdUser($_SESSION['id_user']);

        $postManager = new \OpenClassrooms\Blog\Model\PostManager();
        $postManager->addPost($post);

        return $this->dashboard("", "Votre article a bien été ajouté !");
    }

    /**
     * Supprimer un article
     */
    public function deletePost($idPost){

        $post = new \OpenClassrooms\Blog\Model\post("");
        $post->setIdPost($idPost);

        $postManager = new \OpenClassrooms\Blog\Model\PostManager();
        $postManager->deletePost($post);

        return $this->dashboard("articles_list_dashboard", "Votre article a bien été supprimé !");
    }

    /**
     * Modifier un article
     */
    public function updatePost($titre, $introduction, $contenu, $idPost){

        $post = new \OpenClassrooms\Blog\Model\post("");
        $post->setTitle($titre);
        $post->setIntroduction($introduction);
        $post->setContent($contenu);
        $post->setIdPost($idPost);
        $post->setIdUser($_SESSION['id_user']);

        $postManager = new \OpenClassrooms\Blog\Model\PostManager();
        $postManager->updatePost($post);

        return $this->dashboard("articles_list_dashboard", "Votre article a bien été modifié !");
    }
}