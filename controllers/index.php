<?php

use Core\User;

$router->map("GET","/",function(){
    global $twig;

    $user = User::getLoggedInUser();

    echo $twig->render('shelter/index.twig',["user"=>$user]);
});

$router->map("GET","/zvire",function(){
    global $twig;
    echo $twig->render('shelter/animal.twig');
});
