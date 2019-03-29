<?php
session_start();
/**
 * Les fichiers suivant sont inclus dans index.php :
 */
require_once('vendor/autoload.php');
require_once ('controller/frontend/frontend.php');
require_once ('config.php');


 if (isset($_GET['page']) && $_GET['page'] == "blog") {
     if (isset($_GET['idPage'])){
         $page = ($_GET['idPage']);
     }
     else {
         $page = 1;
     }
     $frontend = new Frontend();
     $render = $frontend->blog($page);

 }elseif (isset($_GET['page']) && $_GET['page'] == "home") {
     $frontend = new Frontend();
     $render = $frontend->index();

 }elseif (isset($_GET['page']) && $_GET['page'] == "article") {
     if (isset($_GET['idPage'])){
         $page = ($_GET['idPage']);
     }
     else {
         $page = 1;
     }
     $name="comment";
     if (isset($_GET['name']) && $_GET['name'] == "form_comment_update_blog") {
         $name="form_comment_update_blog";
     }
     $frontend = new Frontend();
     $render = $frontend->article("", $name, $_GET['id_comment'] ?? -1, $_GET['id_post'], $page);

 }elseif (isset($_GET['page']) && $_GET['page'] == "register") {
     $frontend = new Frontend();
     $render = $frontend->register();

 }elseif (isset($_GET['page']) && $_GET['page'] == "login") {
     $frontend = new Frontend();
     $render = $frontend->login();

 }elseif (isset($_GET['page']) && $_GET['page'] == "add_user") {
     $frontend = new Frontend();
     $render = $frontend->addUser(htmlspecialchars($_POST['pseudo']), htmlspecialchars($_POST['email']), htmlspecialchars($_POST['password']), htmlspecialchars($_POST['confirm-password']));

 }elseif (isset($_GET['page']) && $_GET['page'] == "add_post") {
     if (isset($_GET['idPage'])){
         $page = ($_GET['idPage']);
     }
     else {
         $page = 1;
     }
     $frontend = new Frontend();
     $render = $frontend->addPost(htmlspecialchars($_POST['titre']), htmlspecialchars($_POST['introduction']), htmlspecialchars($_POST['contenu']), $page);

 }elseif (isset($_GET['page']) && $_GET['page'] == "delete_post") {
     if (isset($_GET['idPage'])){
         $page = ($_GET['idPage']);
     }
     else {
         $page = 1;
     }
     $frontend = new Frontend();
     $render = $frontend->deletePost($_GET['id_post'], $page);

 }elseif (isset($_GET['page']) && $_GET['page'] == "update_post") {
     if (isset($_GET['idPage'])){
         $page = ($_GET['idPage']);
     }
     else {
         $page = 1;
     }
     $frontend = new Frontend();
     $render = $frontend->updatePost(htmlspecialchars($_POST['titre']), htmlspecialchars($_POST['introduction']), htmlspecialchars($_POST['contenu']), $_GET['id_post'], $page);

 }elseif (isset($_GET['page']) && $_GET['page'] == "update_comment") {
     $name="";
     if (isset($_GET['name']) && $_GET['name'] == "update_comment_blog") {
         $name="update_comment_blog";
     }
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

 }elseif (isset($_GET['page']) && $_GET['page'] == "delete_comment") {
     if (isset($_GET['idPage'])){
         $page = ($_GET['idPage']);
     }
     else {
         $page = 1;
     }
     $frontend = new Frontend();
     $render = $frontend->deleteComment($_GET['id'], $page);

 }elseif (isset($_GET['page']) && $_GET['page'] == "connect_user") {
     $frontend = new Frontend();
     $render = $frontend->connectUser(htmlspecialchars($_POST['pseudo']), htmlspecialchars($_POST['password']));

 }elseif (isset($_GET['page']) && $_GET['page'] == "add_comment") {
     if (isset($_GET['idPage'])){
         $page = ($_GET['idPage']);
     }
     else {
         $page = 1;
     }
     $name="comment";
     $frontend = new Frontend();
     $render = $frontend->addComment(htmlspecialchars($_POST['comment']), $_GET['id_post'], $name, $page);

 }elseif (isset($_GET['page']) && $_GET['page'] == "validation_comment") {
     if (isset($_GET['idPage'])){
         $page = ($_GET['idPage']);
     }
     else {
         $page = 1;
     }
     $frontend = new Frontend();
     $render = $frontend->validationComment($_GET['id'], $page);

 }elseif (isset($_GET['page']) && $_GET['page'] == "validation_user") {
     if (isset($_GET['idPage'])){
         $page = ($_GET['idPage']);
     }
     else {
         $page = 1;
     }
     $frontend = new Frontend();
     $render = $frontend->validationUser($_GET['id'], $page);

 }elseif (isset($_GET['page']) && $_GET['page'] == "delete_user") {
     if (isset($_GET['idPage'])){
         $page = ($_GET['idPage']);
     }
     else {
         $page = 1;
     }
     $frontend = new Frontend();
     $render = $frontend->deleteUser($_GET['id'], $page);

 }elseif (isset($_GET['page']) && $_GET['page'] == "sign_out") {
     $frontend = new Frontend();
     $frontend->signOut();

 }elseif (isset($_GET['page']) && $_GET['page'] == "dashboard") {
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