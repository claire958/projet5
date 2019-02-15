<?php
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
 }elseif (isset($_GET['page']) && $_GET['page'] == "login_register") {
     $frontend = new Frontend();
     $render = $frontend->login_register();

 }elseif (isset($_GET['page']) && $_GET['page'] == "dashboard") {
     $frontend = new Frontend();
     $render = $frontend->add_user($_POST['pseudo'], $_POST['email'], $_POST['password'], $_POST['confirm-password']);


 }else {
    $frontend = new Frontend();
    $render = $frontend->index();
}

/**
 * On affiche la variable render.
 */
echo $render;