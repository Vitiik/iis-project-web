<?php

namespace Core;

class User{

    public static function getById(int $user_id){
        global $db;
        $user = $db->get("uzivatel","*",["id"=>$user_id]);
        $role = User::getRole($user["id"]);
        $user["role"] = $role;
        return $user;
    }

    public static function getAllUsers(){
        global $db;
        return $db->select("uzivatel(u)", [
            "[>]uzivatel_ma_role(umr)" => ["u.id" => "uzivatel_id"],
            "[>]role(r)" => ["umr.role_id" => "id"]
        ], [
            "u.id",
            "u.jmeno",
            "u.prijmeni",
            "r.jmeno(role)"
        ]);
    }

    public static function getByEmail(string $user_email){
        global $db;
        $user = $db->get("uzivatel","*",["email"=>$user_email]);
        if ($user == null) return null;
        $role = User::getRole($user["id"]);
        $user["role"] = $role;
        return $user;
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

    public static function getRole($uzivatel_id){
        global $db;
        return $db->select("role",["[>]uzivatel_ma_role"=>["id"=>"role_id"]],["role.id(role_id)","jmeno(role_jmeno)"],["uzivatel_id"=>$uzivatel_id]);
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