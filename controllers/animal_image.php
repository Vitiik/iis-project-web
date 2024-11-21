<?php

use Core\AnimalImage;
use Core\ImageUploader;
use Core\UploadException;

$router->map("POST","/nahratObrazekZvirete",function(){
    global $db;
    $images = reArrayFiles($_FILES["image_files"]);

    foreach ($images as $key => $image) {
            
        $nextImageId = $db->query("SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'fotka_zvirete';")->fetchObject()->AUTO_INCREMENT;
        
        try{
            $newImagePaths = ImageUploader::uploadZvireImage($_POST["zvire_id"],$nextImageId,$image);
            AnimalImage::createAnimalImage($_POST["zvire_id"],$newImagePaths);
        }catch (UploadException $e){
            dump($e);
        }
    }
});

function reArrayFiles(&$file_post) {
    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}


$router->map("GET","/posunoutObrazekZvirete",function(){
    global $twig, $db;

    if($_GET["smer"] == "doprava"){ //Priorita doprava
        AnimalImage::moveRight($_GET["zvire_id"],$_GET["obrazek_id"]);
    }
    if($_GET["smer"] == "doleva"){ //Priorita doleva
        AnimalImage::moveLeft($_GET["zvire_id"],$_GET["obrazek_id"]);
    }
    if($_GET["smer"] == "nahoru"){ //Nastavit jako hlavní
        AnimalImage::setMainImage($_GET["zvire_id"],$_GET["obrazek_id"]);
    }
    if($_GET["smer"] == "dolu"){ //Smazat
        AnimalImage::deleteById($_GET["obrazek_id"]);
    }
});