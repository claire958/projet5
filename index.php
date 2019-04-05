<?php
session_start();
/**
 * Les fichiers suivant sont inclus dans index.php :
 */
require_once 'vendor/autoload.php';
require_once 'controller/frontend/frontend.php';
require_once 'config.php';

$page = filter_input(INPUT_GET, 'page');

//BLOG
 if ($page === "blog") {
     $idPage = filter_input(INPUT_GET, 'idPage') ?? 1;
     $pseudo = filter_input(INPUT_SESSION, 'pseudo') ?? "";
     $role = filter_input(INPUT_SESSION, 'role') ?? "";

     $frontend = new Frontend();
     $render = $frontend->blog($idPage, $pseudo, $role);


//HOME
 }elseif ($page === "home") {
     $pseudo = filter_input(INPUT_SESSION, 'pseudo') ?? "";
     $role = filter_input(INPUT_SESSION, 'role') ?? "";

     $frontend = new Frontend();
     $render = $frontend->index($pseudo, $role);


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
     $render = $frontend->updatePost(htmlspecialchars(filter_input(INPUT_POST,'titre')), htmlspecialchars(filter_input(INPUT_POST,'introduction')), htmlspecialchars(filter_input(INPUT_POST,'contenu')), filter_input(INPUT_GET,'id_post'), $idPage);


//MODIFIER UN COMMENTAIRE
 }elseif ($page === "update_comment") {
     $name = "";
     $enteredName = filter_input(INPUT_GET, 'name');
     $idPage = filter_input(INPUT_GET, 'idPage') ?? 1;
     //BLOG
     if ($enteredName === "update_comment_blog") {
         $name="update_comment_blog";
     }
     //DASHBOARD
     if ($enteredName === "update_comment_dashboard") {
         $name="update_comment_dashboard";
     }
     $frontend = new Frontend();
     $render = $frontend->updateComment(htmlspecialchars(filter_input(INPUT_POST,'comment')), filter_input(INPUT_GET,'id_comment'), $name, filter_input(INPUT_GET,'id_post'), $idPage);


//DASHBAORD - SUPPRIMER UN COMMENTAIRE
 }elseif ($page === "delete_comment") {
     $idPage = filter_input(INPUT_GET, 'idPage') ?? 1;

     $frontend = new Frontend();
     $render = $frontend->deleteComment(filter_input(INPUT_GET, 'id'), $idPage);


//DASHBAORD - AJOUTER UN COMMENTAIRE
 }elseif ($page === "add_comment") {
     $idPage = filter_input(INPUT_GET, 'idPage') ?? 1;

     $name="comment";
     $frontend = new Frontend();
     $render = $frontend->addComment(htmlspecialchars(filter_input(INPUT_POST,'comment')), filter_input(INPUT_GET,'id_post'), $name, $idPage);


//DASHBOARD - VALIDER UN COMMENTAIRE
 }elseif ($page === "validation_comment") {
     $idPage = filter_input(INPUT_GET, 'idPage') ?? 1;

     $frontend = new Frontend();
     $render = $frontend->validationComment(filter_input(INPUT_GET,'id'), $idPage);


//DASHBOARD - VALIDER UN UTILISATEUR
 }elseif ($page === "validation_user") {
     $idPage = filter_input(INPUT_GET, 'idPage') ?? 1;

     $frontend = new Frontend();
     $render = $frontend->validationUser(filter_input(INPUT_GET,'id'), $idPage);


//DASHBOARD - SUPPRIMER UN UTILISATEUR
 }elseif ($page === "delete_user") {
     $idPage = filter_input(INPUT_GET, 'idPage') ?? 1;

     $frontend = new Frontend();
     $render = $frontend->deleteUser(filter_input(INPUT_GET, 'id'), $idPage);


//SESSION
 }elseif ($page === "sign_out") {
     $frontend = new Frontend();
     $frontend->signOut();


//AFFICHAGE DASHBOARD
 }elseif ($page === "dashboard") {
     $idPage = filter_input(INPUT_GET, 'idPage') ?? 1;
     $enteredName = filter_input(INPUT_GET, 'name');

     $name="dashboard";
     if ($enteredName === "articles_list_dashboard") {
         $name="articles_list_dashboard";
     }
     if ($enteredName === "add_article") {
         $name="add_article";
     }
     if ($enteredName === "form_update_post") {
         $name="form_update_post";
     }
     if ($enteredName === "form_update_comment") {
         $name="form_update_comment";
     }
     if ($enteredName === "comments_list_dashboard") {
         $name="comments_list_dashboard";
     }
     if ($enteredName === "list_users") {
         $name="list_users";
     }

     $frontend = new Frontend();
     $render = $frontend->dashboard($name, "", $idPage);

 }else {
     $pseudo = filter_input(INPUT_SESSION, 'pseudo') ?? "";
     $role = filter_input(INPUT_SESSION, 'role') ?? "";

     $frontend = new Frontend();
     $render = $frontend->index($pseudo, $role);
}

echo $render;
