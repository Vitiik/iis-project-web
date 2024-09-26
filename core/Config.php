<?php

namespace Core;

class Config{
    public static function get(){
        global $db;
        $config = $db->select("config","*");
        $config = array_combine(array_column($config, 'keyword'), array_column($config,"value"));
        return $config;
    }

    public static function update(string $key, mixed $value){
        global $db;
        $db->update("config",["value"=>$value],["keyword"=>$key]);
    }
}