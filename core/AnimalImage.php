<?php

namespace Core;

class AnimalImage{

    public static function createAnimalImage($zvire_id, $image_paths){
        global $db;
        
        $max_next_order = $db->max("fotka_zvirete", "priorita", ["zvire_id" => $zvire_id]);

        if($max_next_order == ""){
            $next_order = 0;
        }else{
            $next_order = intval($max_next_order) + 1;
        }

        $db->insert("fotka_zvirete",["zvire_id"=>$zvire_id,"url_velka"=>$image_paths[0],"url_stredni"=>$image_paths[1],"url_mala"=>$image_paths[2],"priorita"=>$next_order]);
    }

    public static function deleteById(int $image_id){
        global $db;

        $image = $db->get("fotka_zvirete","*",["id"=>$image_id]);

        if($image["url_velka"][0] == "/"){
            if($image["id"] == -1) return;
            //Local
            unlink(substr($image["url_velka"],1));
            unlink(substr($image["url_stredni"],1));
            unlink(substr($image["url_mala"],1));
        }

        $db->delete("fotka_zvirete",["id"=>$image_id]);
    }

    public static function moveRight(int $zvire_id,int $image_id){
        global $db;
        $images = Animal::getImagesById($zvire_id);
        $image = $db->get("fotka_zvirete","*",["id"=>$image_id]);
        $new_order = $image["priorita"] + 1;
        if($new_order < count($images)){
            $db->update("fotka_zvirete",["priorita"=>$image["priorita"]],["priorita"=>$new_order,"zvire_id"=>$zvire_id]);
            $db->update("fotka_zvirete",["priorita"=>$new_order],["id"=>$image_id,"zvire_id"=>$zvire_id]);
        }
    }

    public static function moveLeft(int $zvire_id,int $image_id){
        global $db;
        $image = $db->get("fotka_zvirete","*",["id"=>$image_id]);
        $new_order = $image["priorita"] - 1;
        if($new_order >= 0){
            $db->update("fotka_zvirete",["priorita"=>$image["priorita"]],["priorita"=>$new_order,"zvire_id"=>$zvire_id]);
            $db->update("fotka_zvirete",["priorita"=>$new_order],["id"=>$image_id,"zvire_id"=>$zvire_id]);
        }
    }

    public static function setMainImage(int $zvire_id,int $image_id){
        global $db;

        $image = $db->get("fotka_zvirete","*",["id"=>$image_id]);
        $new_order = 0;
        $db->update("fotka_zvirete",["priorita"=>$image["priorita"]],["priorita"=>$new_order,"zvire_id"=>$zvire_id]);
        $db->update("fotka_zvirete",["priorita"=>$new_order],["id"=>$image_id,"zvire_id"=>$zvire_id]);
    }
}