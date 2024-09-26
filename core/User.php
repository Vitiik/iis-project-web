<?php

namespace Core;

class User{

    public static function getById(int $user_id){
        global $db;
        return $db->get("shop_user","*",["id"=>$user_id]);
    }

    public static function getUserOrCreateNew($post){
        global $db;
        $user_id = null;
        $user = $db->get("shop_user","*",["firstname"=>$post["firstname"],"lastname"=>$post["lastname"],"telephone"=>$post["telephone"],"email"=>$post["email"]]);
        
        if($user == null){
            $db->insert("shop_user",["firstname"=>$post["firstname"],"lastname"=>$post["lastname"],"telephone"=>$post["telephone"],"email"=>$post["email"],"newsletter"=>$post["newsletter"]]);
            $user_id = $db->id();
        }else{
            $user_id = $user["id"];
            $db->update("shop_user",["newsletter"=>$post["newsletter"]],["id"=>$user_id]);
        }

        return User::getById($user_id);
    }

}