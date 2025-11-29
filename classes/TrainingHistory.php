<?php

class TrainingHistory {

    private static function historyFile(){
        return __DIR__ . "/../storage/history.json";
    }

    private static function pokemonFile(){
        return __DIR__ . "/../storage/pokemon.json";
    }

    public static function save($entry){
        $file = self::historyFile();

        if(!file_exists(dirname($file))){
            mkdir(dirname($file), 0777, true);
        }

        if(!file_exists($file)){
            file_put_contents($file, json_encode([], JSON_PRETTY_PRINT));
        }

        $data = json_decode(file_get_contents($file), true);
        $data[] = $entry;
        file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    }

    public static function getAll(){
        $file = self::historyFile();
        if(!file_exists($file)) return [];
        return json_decode(file_get_contents($file), true);
    }

    public static function reset(){

        file_put_contents(self::historyFile(), json_encode([], JSON_PRETTY_PRINT));

        file_put_contents(self::pokemonFile(), json_encode([
            "level" => 10,
            "hp" => 120,
            "exp" => 0
        ], JSON_PRETTY_PRINT));
    }
}