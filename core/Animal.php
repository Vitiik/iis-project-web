<?php

namespace Core;

class Animal{

    public static function getAllAnimals(){
        global $db;
        return $db->select("zvire(z)", [   
                "[>]fotka_zvirete(fz)" => ["id" => "zvire_id"]
            ],[
                "z.id",
                "z.jmeno",
                "z.zivocisny_druh",
                "z.plemeno",
                "z.pohlavi",
                "z.datum_narozeni",
                "z.popis",
                "fz.url_mala",
            ]);
    }

    public static function getAnimalById(int $zvire_id){
        global $db;
        return $db->get("zvire",[
            "id",
            "jmeno",
            "zivocisny_druh",
            "plemeno",
            "pohlavi",
            "datum_narozeni",
            "popis"
        ],[
            "id" => $zvire_id
        ]);
    }

    public static function getImagesById(int $zvire_id){
        global $db;
        $images = $db->select("fotka_zvirete",[
            "id",
            "url_velka",
            "url_stredni",
            "url_mala",
            "priorita"
        ],[
            "zvire_id" => $zvire_id,
            "ORDER" => ["priorita" => "DESC"]
        ]);
        if (!$images) {
            return array(array(
                    "id" => 0,
                    "url_velka" => "no-image.jpg",
                    "url_stredni" => "no-image.jpg",
                    "url_mala" => "no-image.jpg",
                    "priorita" => 1
                    ));
        }
        return $images;
    }

    public static function getDateOfLastOckovaniForAllAnimals(){
        global $db;
        return $db->query("
            SELECT 
                p1.zvire_id, 
                p1.cas
            FROM 
                prohlidka p1
            INNER JOIN (
                SELECT 
                    zvire_id, 
                    MAX(cas) AS max_cas
                FROM 
                    prohlidka
                WHERE 
                    vakcina IS NOT NULL
                GROUP BY 
                    zvire_id
            ) p2 
            ON 
                p1.zvire_id = p2.zvire_id 
                AND p1.cas = p2.max_cas
        ")->fetchAll();
    }

    public static function getOckovaniById($id){
        global $db;
        return $db->select("prohlidka", [   
                "cas",
                "vakcina"
            ],[
                "zvire_id" => $id,
                "vakcina[!]" => NULL,
                "ORDER" => ["cas" => "DESC"]
            ]
        );
    }

    public static function getAllMereniById($id){
        global $db;
        return $db->select("prohlidka", [   
                "cas",
                "vyska",
                "delka",
                "hmotnost"
            ],[
                "zvire_id" => $id,
                "OR" => [
                    "vyska[!]" => NULL,
                    "delka[!]" => NULL,
                    "hmotnost[!]" => NULL
                ],
                "ORDER" => ["cas" => "DESC"]
            ]
        );
    }

    public static function getHmotnostById($id){
        global $db;
        return $db->get("prohlidka", ["hmotnost"],[
                "zvire_id" => $id,
                "hmotnost[!]" => NULL,
                "ORDER" => ["cas" => "DESC"]
            ]
        );
    }

    public static function createAnimal($jmeno,$zivocisny_druh,$plemeno,$pohlavi,$datum_narozeni){
        global $db;
        $db->insert("zvire",[
            "jmeno" => $jmeno,
            "zivocisny_druh" => $zivocisny_druh,
            "plemeno" => $plemeno,
            "pohlavi" => $pohlavi,
            "datum_narozeni" => $datum_narozeni
        ]);
    }

}