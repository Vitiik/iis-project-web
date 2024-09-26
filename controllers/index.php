<?php

$router->map("GET","/",function(){
    global $twig;
    echo $twig->render('shelter/index.twig');
});
