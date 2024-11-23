<?php

use Core\User;

$router->map("GET","/",function(){
    global $twig;

    $user = User::getLoggedInUser();

    echo $twig->render('shelter/index.twig',["user"=>$user]);
});

$router->map("GET","/o-nas",function(){
    global $twig;

    $user = User::getLoggedInUser();

    echo $twig->render('shelter/about.twig',["user"=>$user]);
});