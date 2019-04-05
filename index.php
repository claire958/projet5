<?php
session_start();
/**
 * Les fichiers suivant sont inclus dans index.php :
 */
require_once ('vendor/autoload.php');
require_once ('controller/frontend/frontend.php');
require_once ('config.php');

$page = filter_input(INPUT_GET, 'page');

//BLOG
 if ($page === "blog") {
     $idPage = filter_input(INPUT_GET, 'idPage') ?? 1;

     $frontend = new Frontend();
     $render = $frontend->blog($idPage);


//HOME
 }elseif ($page === "home") {
     $frontend = new Frontend();
     $render = $frontend->index();


//PAGE ARTICLE
 }elseif ($page === "article") {
     $idPage = filter_input(INPUT_GET, 'idPage') ?? 1;

     $enteredName = filter_input(INPUT_GET, 'name');
     $name = "comment";
     //FORMULAIRE - MODIFIER UN COMMENTAIRE SUR PAGE ARTICLE
     if ($enteredName === "form_comment_update_blog") {
         $name = "form_comment_update_blog";
     }

     $frontend = new Frontend();
     $render = $frontend->article("", $name, filter_input(INPUT_GET, 'id_comment') ?? -1, filter_input(INPUT_GET, 'id_post'), $idPage);


//LOGIN & REGISTER
 }elseif ($page === "register") {
     $frontend = new Frontend();
     $render = $frontend->register();

 }elseif ($page === "login") {
     $frontend = new Frontend();
     $render = $frontend->login();

 }elseif ($page === "add_user") {
     $frontend = new Frontend();
     $render = $frontend->addUser(htmlspecialchars(filter_input(INPUT_POST, 'pseudo')), htmlspecialchars(filter_input(INPUT_POST, 'email')), htmlspecialchars(filter_input(INPUT_POST, 'password')), htmlspecialchars(filter_input(INPUT_POST, 'confirm-password')));

 }elseif ($page === "connect_user") {
     $frontend = new Frontend();
     $render = $frontend->connectUser(htmlspecialchars(filter_input(INPUT_POST, 'pseudo')), htmlspecialchars(filter_input(INPUT_POST, 'password')));


//DASHBOARD - AJOUTER UN ARTICLE
 }elseif ($page === "add_post") {
     $idPage = filter_input(INPUT_GET, 'idPage') ?? 1;

     $frontend = new Frontend();
     $render = $frontend->addPost(htmlspecialchars(filter_input(INPUT_POST,'titre')), htmlspecialchars(filter_input(INPUT_POST,'introduction')), htmlspecialchars(filter_input(INPUT_POST,'contenu')), $idPage);


//DASHBOARD - SUPPRIMER UN ARTICLE
 }elseif ($page === "delete_post") {
     $idPage = filter_input(INPUT_GET, 'idPage') ?? 1;

     $frontend = new Frontend();
     $render = $frontend->deletePost(filter_input(INPUT_GET,'id_post'), $idPage);


//DASHBOARD - MODIFIER UN ARTICLE
 }elseif ($page === "update_post") {
     $idPage = filter_input(INPUT_GET, 'idPage') ?? 1;

     $frontend = new Frontend();
     $render = $frontend->updatePost(htmlspecialchars($_POST['titre']), htmlspecialchars($_POST['introduction']), htmlspecialchars($_POST['contenu']), filter_input(INPUT_GET,'id_post'), $idPage);


//MODIFIER UN COMMENTAIRE
 }elseif ($page === "update_comment") {
     $name="";
     //BLOG
     if (isset($_GET['name']) && $_GET['name'] == "update_comment_blog") {
         if (isset($_GET['idPage'])){
             $page = ($_GET['idPage']);
         }
         else {
             $page = 1;
         }
         $name="update_comment_blog";
     }
     //DASHBOARD
     if (isset($_GET['name']) && $_GET['name'] == "update_comment_dashboard") {
         if (isset($_GET['idPage'])){
             $page = ($_GET['idPage']);
         }
         else {
             $page = 1;
         }
         $name="update_comment_dashboard";
     }
     $frontend = new Frontend();
     $render = $frontend->updateComment(htmlspecialchars($_POST['comment']), $_GET['id_comment'], $name, $_GET['id_post'], $page);


//DASHBAORD - SUPPRIMER UN COMMENTAIRE
 }elseif ($page === "delete_comment") {
     if (isset($_GET['idPage'])){
         $page = ($_GET['idPage']);
     }
     else {
         $page = 1;
     }
     $frontend = new Frontend();
     $render = $frontend->deleteComment($_GET['id'], $page);


//DASHBAORD - AJOUTER UN COMMENTAIRE
 }elseif ($page === "add_comment") {
     if (isset($_GET['idPage'])){
         $page = ($_GET['idPage']);
     }
     else {
         $page = 1;
     }
     $name="comment";
     $frontend = new Frontend();
     $render = $frontend->addComment(htmlspecialchars($_POST['comment']), $_GET['id_post'], $name, $page);


//DASHBOARD - VALIDER UN COMMENTAIRE
 }elseif ($page === "validation_comment") {
     if (isset($_GET['idPage'])){
         $page = ($_GET['idPage']);
     }
     else {
         $page = 1;
     }
     $frontend = new Frontend();
     $render = $frontend->validationComment($_GET['id'], $page);


//DASHBOARD - VALIDER UN UTILISATEUR
 }elseif ($page === "validation_user") {
     if (isset($_GET['idPage'])){
         $page = ($_GET['idPage']);
     }
     else {
         $page = 1;
     }
     $frontend = new Frontend();
     $render = $frontend->validationUser($_GET['id'], $page);


//DASHBOARD - SUPPRIMER UN UTILISATEUR
 }elseif ($page === "delete_user") {
     if (isset($_GET['idPage'])){
         $page = ($_GET['idPage']);
     }
     else {
         $page = 1;
     }
     $frontend = new Frontend();
     $render = $frontend->deleteUser($_GET['id'], $page);


//SESSION
 }elseif ($page === "sign_out") {
     $frontend = new Frontend();
     $frontend->signOut();


//AFFICHAGE DASHBOARD
 }elseif ($page === "dashboard") {
     if (isset($_GET['idPage'])){
         $page = ($_GET['idPage']);
     }
     else {
         $page = 1;
     }
     $name="dashboard";
         if (isset($_GET['name']) && $_GET['name'] == "articles_list_dashboard") {
         $name="articles_list_dashboard";
         }
         if (isset($_GET['name']) && $_GET['name'] == "add_article") {
             $name="add_article";
         }
         if (isset($_GET['name']) && $_GET['name'] == "form_update_post") {
             $name="form_update_post";
         }
         if (isset($_GET['name']) && $_GET['name'] == "form_update_comment") {
             $name="form_update_comment";
         }
         if (isset($_GET['name']) && $_GET['name'] == "comments_list_dashboard") {
             $name="comments_list_dashboard";
         }
         if (isset($_GET['name']) && $_GET['name'] == "list_users") {
             $name="list_users";

     }
     $frontend = new Frontend();
     $render = $frontend->dashboard($name, "", $page);

 }else {
    $frontend = new Frontend();
    $render = $frontend->index();
}

echo $render;
