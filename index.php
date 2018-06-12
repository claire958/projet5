<?php
require_once('vendor/autoload.php');
require_once ('controller/frontend/frontend.php');

 if (isset($_GET['page']) && $_GET['page'] == "blog") {
     $frontend = new Frontend();
     $render = $frontend->blog();

 }elseif (isset($_GET['page']) && $_GET['page'] == "home") {
     $frontend = new Frontend();
     $render = $frontend->index();
 }else {
    $frontend = new Frontend();
    $render = $frontend->login_register();
}
echo $render;