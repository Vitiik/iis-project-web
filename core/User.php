<?php

namespace Core;

class User{

    public static function getById(int $user_id){
        global $db;
        return $db->get("uzivatel","*",["id"=>$user_id]);
    }

    public static function getByEmail(string $user_email){
        global $db;
        return $db->get("uzivatel","*",["email"=>$user_email]);
    }

    public static function getLoggedInUser(){
        if(isset($_SESSION["user_email"])){
            return User::getByEmail($_SESSION["user_email"]);
        }
        return null;
    }

}