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

    public static function createUser($jmeno,$prijmeni,$email,$heslo){
        global $db;
        $heslo_hash = password_hash($heslo,PASSWORD_BCRYPT);
        return $db->insert("uzivatel",[
            "jmeno" => $jmeno,
            "prijmeni" => $prijmeni,
            "email" => $email,
            "heslo" => $heslo_hash
        ]);
    }

    public static function overitUser($id,$cas){
        global $db;
        return $db->update("uzivatel",[
            "overen_kdy" => date("Y-m-d h:i:sa"),
        ],[
            "id" => $id
        ]);
    }

    public static function setRole($uzivatel_id,$role_id){
        global $db;
        return $db->update("uzivatel_ma_role",[
            "uzivatel_id" => $uzivatel_id,
            "role_id" => $role_id,
        ]);
    }

    public static function changePassword($id,$heslo_nove){
        global $db;
        $heslo_hash = password_hash($heslo_nove,PASSWORD_BCRYPT);

        return $db->update("uzivatel",[
            "heslo" => $heslo_nove,
        ],[
            "id" => $id
        ]);
    }

}