<?php
require_once __DIR__ . "/Pokemon.php";

class PokemonAir extends Pokemon {

    protected $waterBoost = 1.2;

    public function kemampuanUnik(){
        return "Serangan Air Menggelegar!";
    }
}