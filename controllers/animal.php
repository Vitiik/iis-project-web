<?php

$router->map("GET","/zvire",function(){
    global $twig;
    echo $twig->render('shelter/animal.twig');
});