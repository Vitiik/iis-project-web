<?php

namespace Core;

class Manipulace{

    public static function createProdej($jmeno_zakaznika, $telefon_zakaznika, $cena, $cas, $zvire_id){
        global $db;
        return $db->insert("prodej",[
            "jmeno_zakaznika" => $jmeno_zakaznika,
            "telefon_zakaznika" => $telefon_zakaznika,
            "cena" => $cena,
            "cas" => $cas,
            "zvire_id" => $zvire_id
        ]);
    }

    public static function createNalezeni($jmeno_nalezce, $kontakt_na_nalezce, $misto_nalezeni, $cas, $zvire_id){
        global $db;
        return $db->insert("nalezeni",[
            "jmeno_nalezce" => $jmeno_nalezce,
            "kontakt_na_nalezce" => $kontakt_na_nalezce,
            "misto_nalezeni" => $misto_nalezeni,
            "cas" => $cas,
            "zvire_id" => $zvire_id
        ]);
    }

    public static function createUmrti($pricina, $cas, $zvire_id){
        global $db;
        return $db->insert("umrti",[
            "pricina" => $pricina,
            "cas" => $cas,
            "zvire_id" => $zvire_id
        ]);
    }

    public static function createProhlidka($zdravotni_stav, $vakcina, $vyska, $delka, $hmotnost, $pozadavek_id, $zvire_id, $zverolekar_id){
        global $db;
        return $db->insert("prohlidka",[
            "zdravotni_stav" => $zdravotni_stav,
            "vakcina" => $vakcina,
            "vyska" => $vyska,
            "delka" => $delka,
            "hmotnost" => $hmotnost,
            "pozadavek_id" => $pozadavek_id,
            "zvire_id" => $zvire_id,
            "zverolekar_id" => $zverolekar_id
        ]);
    }

}