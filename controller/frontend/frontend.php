<?php

/**
 * Les fichiers suivant sont inclus dans frontend.php :
 */
require_once 'model/frontend/Post.php';
require_once 'model/frontend/User.php';
require_once 'model/frontend/Comment.php';
require_once 'model/frontend/PostManager.php';
require_once 'model/frontend/UserManager.php';
require_once 'model/frontend/CommentManager.php';


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
    public function index($pseudo, $role){
        $renderData = [
            'pseudo' => $pseudo,
            'role' => $role
        ];
        return $this->twig->render('index.twig', $renderData);
    }

    /**
     * Affiche blog.twig
     */
    public function blog($page, $pseudo, $role){
        $postManager = new OpenClassrooms\Blog\Model\PostManager();
        $userManager = new OpenClassrooms\Blog\Model\UserManager();

        $nombreUtilisateurParPage = 5;
        $nombrePostParPage = 5;

        $totalDesMessagesPost = $postManager->countPost();

        // On calcule le nombre de pages à créer
        $nombreDePagesPosts  = ceil($totalDesMessagesPost / $nombreUtilisateurParPage);

        // On calcule le numéro du premier message qu'on prend pour le LIMIT de MySQL
        $premierMessageAafficher = ($page - 1) * $nombreUtilisateurParPage;

        $renderData = [
            'post' => $postManager->getListPost($premierMessageAafficher, $nombrePostParPage),
            'users' => $userManager->getListUser($premierMessageAafficher,$nombreUtilisateurParPage),
            // $pseudo = (condition) ?? si faux;
            'pseudo' => $pseudo,
            'role' => $role,
            'nombreDePagesPosts' => $nombreDePagesPosts
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
            'pseudo' => $_SESSION['pseudo'] ?? '',
            'role' => $_SESSION['role'] ?? ''
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
            'pseudo' => $_SESSION['pseudo'] ?? '',
            'role' => $_SESSION['role'] ?? ''
        ];
        return $this->twig->render('page_login.twig', $renderData);
    }

    /**
     * Affiche articles.twig
     */
    public function article($messageErreur, $name, $idComment, $idPost, $page){
        $postManager = new \OpenClassrooms\Blog\Model\PostManager();
        $userManager = new OpenClassrooms\Blog\Model\UserManager();
        $commentManager = new OpenClassrooms\Blog\Model\CommentManager();

        $nombreCommmentaireParPage = 5;

        $totalDesMessages = $commentManager->countCommentForPost($idPost);

        // On calcule le nombre de pages à créer
        $nombreDePagesCommentaires  = ceil($totalDesMessages / $nombreCommmentaireParPage);

        // On calcule le numéro du premier message qu'on prend pour le LIMIT de MySQL
        $premierMessageAafficher = ($page - 1) * $nombreCommmentaireParPage;

        $pseudo = $_SESSION['pseudo'] ?? '';

        $renderData = [
            'message' => $messageErreur,
            'article' => $postManager->getPost($idPost),
            'users' => $userManager->getInfoUser(),
            'comment' => $commentManager->getComment($idPost, $premierMessageAafficher, $nombreCommmentaireParPage),
            // $pseudo = (condition) ?? si faux;
            'pseudo' => $pseudo,
            'role' => $_SESSION['role'] ?? '',
            'idUser' => $userManager->getUser($pseudo),
            'name' => $name,
            'nombreDePagesCommentaires' => $nombreDePagesCommentaires
        ];


        if ($name == "form_comment_update_blog"){
            $renderData = [
                'pseudo' => $pseudo,
                'name' => $name,
                'message' => $messageErreur,
                'commentUpdate' => $commentManager->getCommentUpdate($idComment)
            ];
        }

        return $this->twig->render('articles.twig', $renderData);
    }

    /**
     * Affiche dashboard.twig
     */
    public function dashboard($name, $messageDashboard, $page){
        $postManager = new \OpenClassrooms\Blog\Model\PostManager();
        $userManager = new \OpenClassrooms\Blog\Model\UserManager();
        $commentManager = new \OpenClassrooms\Blog\Model\CommentManager();

        $pseudo = $_SESSION['pseudo'] ?? '';

        //PAGE HOME DASHBOARD
        $renderData = ['pseudo' => $pseudo, 'name' => $name, 'message' => $messageDashboard,];

        //PAGE COMMENTAIRES
        if($name == "comments_list_dashboard"){
            $nombreCommmentaireParPage = 5;
            $totalDesMessages = $commentManager->countComment();

            // On calcule le nombre de pages à créer
            $nombreDePagesCommentaires  = ceil($totalDesMessages / $nombreCommmentaireParPage);
            // On calcule le numéro du premier message qu'on prend pour le LIMIT de MySQL
            $premierMessageAafficher = ($page - 1) * $nombreCommmentaireParPage;

            $renderData = [
                'pseudo' => $pseudo,
                'name' => $name,
                'comment' => $commentManager->getListComment($premierMessageAafficher, $nombreCommmentaireParPage),
                'post' => $postManager->getInfoPost(),
                'users' => $userManager->getInfoUser(),
                'message' => $messageDashboard,
                'nombreDePagesCommentaires' => $nombreDePagesCommentaires
            ];
            return $this->twig->render('dashboard.twig', $renderData);
        }

        //PAGE UTILISATEURS
        if($name == "list_users"){
            $nombreUtilisateurParPage = 5;
            $totalDesMessages = $userManager->countUser();

            // On calcule le nombre de pages à créer
            $nombreDePagesUsers  = ceil($totalDesMessages / $nombreUtilisateurParPage);
            // On calcule le numéro du premier message qu'on prend pour le LIMIT de MySQL
            $premierMessageAafficher = ($page - 1) * $nombreUtilisateurParPage;

            $renderData = [
                'pseudo' => $pseudo,
                'name' => $name,
                'users' => $userManager->getListUser($premierMessageAafficher,$nombreUtilisateurParPage),
                'message' => $messageDashboard,
                'nombreDePagesUsers' => $nombreDePagesUsers
            ];
            return $this->twig->render('dashboard.twig', $renderData);
        }

        //PAGE POSTS
        if($name == "articles_list_dashboard"){
            $nombrePostParPage = 5;
            $totalDesMessages = $postManager->countPost();

            // On calcule le nombre de pages à créer
            $nombreDePagesPosts  = ceil($totalDesMessages / $nombrePostParPage);
            // On calcule le numéro du premier message qu'on prend pour le LIMIT de MySQL
            $premierMessageAafficher = ($page - 1) * $nombrePostParPage;

            $renderData = [
                'pseudo' => $pseudo,
                'name' => $name,
                'post' => $postManager->getListPost($premierMessageAafficher, $nombrePostParPage),
                'users' => $userManager->getInfoUser(),
                'message' => $messageDashboard,
                'nombreDePagesPosts' => $nombreDePagesPosts
            ];
            return $this->twig->render('dashboard.twig', $renderData);
        }

        //PAGE MODIFIER POST
        if ($name == "form_update_post"){
            $renderData = [
                'pseudo' => $pseudo,
                'name' => $name,
                'message' => $messageDashboard,
                'article' => $postManager->getPost(filter_input(INPUT_GET, 'id')),
            ];
            return $this->twig->render('dashboard.twig', $renderData);
        }

        //PAGE MODIFIER COMMENTAIRE
        if ($name == "form_update_comment"){
            $renderData = [
                'pseudo' => $pseudo,
                'name' => $name,
                'message' => $messageDashboard,
                'commentUpdate' => $commentManager->getCommentUpdate(filter_input(INPUT_GET, 'id'))
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

            $validation = $newUser->getValidation();

            if ($validation == "non") {
                return $this->login("Votre compte n'a pas encore été validé !");
            }
            $_SESSION['id_user'] = $newUser->getIdUser();
            $_SESSION['pseudo'] = $newUser->getPseudo();
            $_SESSION['role'] = $newUser->getRole();

            if ($newUser->getRole() != "user" ){
                return $this->dashboard("dashboard","", "");
            }
            return $this->index($newUser->getPseudo(), $newUser->getRole());
        }

        return $this->login("Mauvais identifiant ou mot de passe !");
    }

    /**
     * Validation d'un utilisateur
     */
    public function validationUser($idUser, $page){

        $user = new \OpenClassrooms\Blog\Model\User("");
        $user->setIdUser($idUser);
        $user->setValidation("oui");

        $userManager = new \OpenClassrooms\Blog\Model\UserManager();
        $userManager->validationUser($user);

        return $this->dashboard("list_users", "Votre utilisateur a bien été validé !", $page);
    }

    /**
     * Supprimer un utilisateur
     */
    public function deleteUser($idUser, $page){

        $user = new \OpenClassrooms\Blog\Model\User("");
        $user->setIdUser($idUser);

        $userManager = new \OpenClassrooms\Blog\Model\UserManager();
        $userManager->deleteUser($user);

        return $this->dashboard("list_users", "Votre utilisateur a bien été supprimé !", $page);
    }



    //COMMENTAIRES

    /**
     * Ajouter un commentaire
     */
    public function addComment($comment, $idPost, $name , $page){

        if (empty($comment) || empty($idPost)) {
            return $this->article("Un champs n'est pas renseigné.", $name, -1, $idPost, $page);
        }

        $idUser = $_SESSION['id_user'];
        if (!$idUser){
            return $this->article(" Il faut vous connecter pour pouvoir poster un commentaire !", $name, -1, $idPost,$page);
        }

        $newComment = new \OpenClassrooms\Blog\Model\Comment("");
        $newComment->setComment($comment);
        $newComment->setIdPost($idPost);
        $newComment->setIdUser($idUser);
        $newComment->setValidation("non");

        $commentManager = new \OpenClassrooms\Blog\Model\CommentManager();
        $commentManager->addComment($newComment);

        $postManager = new \OpenClassrooms\Blog\Model\PostManager();
        ['article' => $postManager->getPost($idPost)];

        return $this->article("Votre commentaire a bien été ajouté. Il est en attente d'approbation par un administrateur.", $name, -1, $idPost, $page);
    }

    /**
     * Modifier un commentaire
     */
    public function updateComment($comment, $idComment, $name, $idPost, $page){

        $newComment = new \OpenClassrooms\Blog\Model\Comment("");
        $newComment->setComment($comment);
        $newComment->setIdComment($idComment);
        $newComment->setIdUser($_SESSION['id_user']);
        $newComment->setIdPost($idPost);

        $commentManager = new \OpenClassrooms\Blog\Model\CommentManager();
        $commentManager->updateComment($newComment);

        if($name == "update_comment_dashboard"){
            return $this->dashboard("comments_list_dashboard", "Votre commentaire a bien été modifié !", $page);
        }

        if($name == "update_comment_blog") {
            return $this->article("Votre commentaire a bien été modifié !", "comment", $idComment, $idPost, $page);
        }
    }

    /**
     * Valider un commentaire
     */
    public function validationComment($idComment, $page){

        $newComment = new \OpenClassrooms\Blog\Model\Comment("");
        $newComment->setValidation("oui");
        $newComment->setIdComment($idComment);

        $commentManager = new \OpenClassrooms\Blog\Model\CommentManager();
        $commentManager->validationComment($newComment);

        return $this->dashboard("comments_list_dashboard", "Votre commentaire a bien été validé !", $page);
    }

    /**
     * Supprimer un commentaire
     */
    public function deleteComment($idComment, $page){

        $newComment = new \OpenClassrooms\Blog\Model\Comment("");
        $newComment->setIdComment($idComment);

        $commentManager = new \OpenClassrooms\Blog\Model\CommentManager();
        $commentManager->deleteComment($newComment);

        return $this->dashboard("comments_list_dashboard", "Votre commentaire a bien été supprimé !", $page);
    }



    //ARTICLES

    /**
     * Ajouter un article
     */
    public function addPost($titre, $introduction, $contenu, $page){

        $post = new \OpenClassrooms\Blog\Model\post("");
        $post->setTitle($titre);
        $post->setIntroduction($introduction);
        $post->setContent($contenu);
        $post->setIdUser($_SESSION['id_user']);

        $postManager = new \OpenClassrooms\Blog\Model\PostManager();
        $postManager->addPost($post);

        return $this->dashboard("articles_list_dashboard", "Votre article a bien été ajouté !", $page);
    }

    /**
     * Supprimer un article
     */
    public function deletePost($idPost, $page){

        $post = new \OpenClassrooms\Blog\Model\post("");
        $post->setIdPost($idPost);

        $postManager = new \OpenClassrooms\Blog\Model\PostManager();
        $postManager->deletePost($post);

        return $this->dashboard("articles_list_dashboard", "Votre article a bien été supprimé !", $page);
    }

    /**
     * Modifier un article
     */
    public function updatePost($titre, $introduction, $contenu, $idPost, $page){

        $post = new \OpenClassrooms\Blog\Model\post("");
        $post->setTitle($titre);
        $post->setIntroduction($introduction);
        $post->setContent($contenu);
        $post->setIdPost($idPost);
        $post->setIdUser($_SESSION['id_user']);

        $postManager = new \OpenClassrooms\Blog\Model\PostManager();
        $postManager->updatePost($post);

        return $this->dashboard("articles_list_dashboard", "Votre article a bien été modifié !", $page);
    }
}
