<?php

namespace Core;

class Animal{

    public static function getAllAnimals(){
        global $db;
        return $db->select("zvire(z)", [   
                "[>]fotka_zvirete(fz)" => ["id" => "zvire_id","AND" => ["priorita[=]" => "max(priorita)"]]
            ],[
                "z.id",
                "z.jmeno",
                "z.zivocisny_druh",
                "z.plemeno",
                "z.pohlavi",
                "z.datum_narozeni",
                "z.popis",
                "fz.url_mala",
            ],[
                "ORDER" => ["z.id" => "DESC"]
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
            "ORDER" => ["priorita" => "ASC"]
        ]);
        if (!$images) {
            return array(array(
                    "id" => 0,
                    "url_velka" => "/media/images/no-image.jpg",
                    "url_stredni" => "/media/images/no-image.jpg",
                    "url_mala" => "/media/images/no-image.jpg",
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
                "vakcina",
                "zdravotni_stav",
                "zverolekar_id"
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
                "hmotnost",
                "zdravotni_stav",
                "zverolekar_id"
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

    public static function getNalezeniById($id){
        global $db;
        return $db->get("nalezeni", [
                "jmeno_nalezce",
                "kontakt_na_nalezce",
                "misto_nalezeni",
                "cas"
            ],[
                "zvire_id" => $id,
                "ORDER" => ["cas" => "DESC"]
            ]
        );
    }

    public static function getProdejById($id){
        global $db;
        return $db->get("prodej", [
                "jmeno_zakaznika",
                "telefon_zakaznika",
                "cena",
                "cas"
            ],[
                "zvire_id" => $id,
                "ORDER" => ["cas" => "DESC"]
            ]
        );
    }

    public static function getUmrtiById($id){
        global $db;
        return $db->get("umrti", [
                "pricina",
                "cas"
            ],[
                "zvire_id" => $id,
                "ORDER" => ["cas" => "DESC"]
            ]
        );
    }

    public static function getAllProhlidkyBezMereniOckovaniById($id){
        global $db;
        return $db->select("prohlidka", [   
            "cas",
            "zdravotni_stav",
            "zverolekar_id"
        ],[
                "zvire_id" => $id,
                "vakcina" => NULL,
                "vyska" => NULL,
                "delka" => NULL,
                "hmotnost" => NULL,
                "ORDER" => ["cas" => "DESC"]
            ]
        );
    }

    public static function getZvireJeVolneById($id){
        global $db;

        // Step 1: Fetch all conflicting rezervace records for the specified zvire_id
        $conflicting_reservations = $db->select("rezervace", [
            "cas_zacatku",
            "cas_konce"
        ], [
            "zvire_id" => $id
        ]);

        // Step 2: Prepare conditions to exclude conflicting intervals
        $conflict_conditions = [];
        foreach ($conflicting_reservations as $conflict) {
            $conflict_conditions[] = [
                "cas_zacatku" => $conflict["cas_zacatku"],
                "cas_konce" => $conflict["cas_konce"]
            ];
        }

        // Step 3: Fetch available times excluding conflicting intervals
        if ($conflicting_reservations != NULL){
            $available_times = $db->select("zvire_je_volne", "*", [
                "zvire_id" => $id,
                "AND" => [
                    "OR # Exclude conflicting intervals" => [
                        "cas_zacatku[!]" => array_column($conflict_conditions, 'cas_zacatku'),
                        "cas_konce[!]" => array_column($conflict_conditions, 'cas_konce')
                    ],
                ],
            ]);
        } else {
            $available_times = $db->select("zvire_je_volne", "*", ["zvire_id" => $id]);
        }

        return $available_times;
    }

    public static function getManipulaceById($id){
        global $db;

        $manipulace = array();

        // Očkování
        $ockovani = Animal::getOckovaniById($id);
        foreach ($ockovani as $ock){
            array_push($manipulace,array(
                "cas" => $ock["cas"],
                "typ_manipulace" => "ockovani",
                "detail" => $ock
            ));
        }
        // Měření
        $mereni = Animal::getAllMereniById($id);
        foreach ($mereni as $mer){
            array_push($manipulace,array(
                "cas" => $mer["cas"],
                "typ_manipulace" => "mereni",
                "detail" => $mer
            ));
        }
        // Prohlídka
        $prohlidky = Animal::getAllProhlidkyBezMereniOckovaniById($id);
        foreach ($prohlidky as $proh){
            array_push($manipulace,array(
                "cas" => $proh["cas"],
                "typ_manipulace" => "prohlidka",
                "detail" => $proh
            ));
        }
        // Nalezení
        $nalezeni = Animal::getNalezeniById($id);
        if (isset($nalezeni)){
            array_push($manipulace,array(
                "cas" => $nalezeni["cas"],
                "typ_manipulace" => "nalezeni",
                "detail" => $nalezeni
            ));
        }

        // Prodej
        $prodej = Animal::getProdejById($id);
        if (isset($prodej)){
            array_push($manipulace,array(
                "cas" => $prodej["cas"],
                "typ_manipulace" => "prodej",
                "detail" => $prodej
            ));
        }

        // Umrtí
        $umrti = Animal::getUmrtiById($id);
        if (isset($umrti)){
            array_push($manipulace,array(
                "cas" => $umrti["cas"],
                "typ_manipulace" => "umrti",
                "detail" => $umrti
            ));
        }

        usort($manipulace, function($a, $b) {
            return strtotime($b['cas']) - strtotime($a['cas']);
        });
        
        return $manipulace;
    }

    public static function editAnimal(){
        global $db;
        return $db->update("zvire",$_POST,["id" => $_POST["id"]]);
    }

    public static function createAnimal($jmeno,$zivocisny_druh,$plemeno,$pohlavi,$datum_narozeni){
        global $db;
        return $db->insert("zvire",[
            "jmeno" => $jmeno,
            "zivocisny_druh" => $zivocisny_druh,
            "plemeno" => $plemeno,
            "pohlavi" => $pohlavi,
            "datum_narozeni" => $datum_narozeni
        ]);
    }

}