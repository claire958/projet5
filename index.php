<?php
session_start();
/**
 * Les fichiers suivant sont inclus dans index.php :
 */
require_once('vendor/autoload.php');
require_once ('controller/frontend/frontend.php');
require_once ('config.php');

/**
 * Si 'page' est définie et est différente de NULL
 * Si 'page' égal à "blog"
 * On instancie la classe Frontend()
 * Et, on appelle la fonction blog().
 */
 if (isset($_GET['page']) && $_GET['page'] == "blog") {
     $frontend = new Frontend();
     $render = $frontend->blog();

/**
* Sinon, si, 'page' est égal à "home"
* On instancie la classe Frontend()
* Et, on appelle la fonction index().
*/
 }elseif (isset($_GET['page']) && $_GET['page'] == "home") {
     $frontend = new Frontend();
     $render = $frontend->index();

/**
* Sinon, si, 'page' est égal à "article"
* On instancie la classe Frontend()
* Et, on appelle la fonction article().
*/
 }elseif (isset($_GET['page']) && $_GET['page'] == "article") {
     $frontend = new Frontend();
     $render = $frontend->article();

/**
* Sinon, si, 'page' est égal à "login_register"
* On instancie la classe Frontend()
* Et, on appelle la fonction login_register().
*/
 }elseif (isset($_GET['page']) && $_GET['page'] == "register") {
     $frontend = new Frontend();
     $render = $frontend->register();

 }elseif (isset($_GET['page']) && $_GET['page'] == "login") {
     $frontend = new Frontend();
     $render = $frontend->login();

 }elseif (isset($_GET['page']) && $_GET['page'] == "add_user") {
     $frontend = new Frontend();
     $render = $frontend->add_user(htmlspecialchars($_POST['pseudo']), htmlspecialchars($_POST['email']), htmlspecialchars($_POST['password']), htmlspecialchars($_POST['confirm-password']));

 }elseif (isset($_GET['page']) && $_GET['page'] == "connect_user") {
     $frontend = new Frontend();
     $render = $frontend->connect_user(htmlspecialchars($_POST['pseudo']), htmlspecialchars($_POST['password']));

 }elseif (isset($_GET['page']) && $_GET['page'] == "add_comment") {
     $frontend = new Frontend();
     $render = $frontend->add_comment(htmlspecialchars($_POST['comment']), $_GET['id']);

 }elseif (isset($_GET['page']) && $_GET['page'] == "sign_out") {
     $frontend = new Frontend();
     $frontend->sign_out();

 }elseif (isset($_GET['page']) && $_GET['page'] == "dashboard") {
     $frontend = new Frontend();
     $render = $frontend->dashboard();

 }else {
    $frontend = new Frontend();
    $render = $frontend->index();
}

/**
 * On affiche la variable render.
 */
echo $render;