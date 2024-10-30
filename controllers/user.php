<?php

use Core\User;

$router->map("GET","/muj-profil",function(){
    global $twig;

    $user = User::getLoggedInUser();

    if($user == null){
        header("/");
        exit();
    }

    echo $twig->render('shelter/user.twig',["user"=>$user]);
});