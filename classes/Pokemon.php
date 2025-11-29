<?php
require_once __DIR__ . "/Training.php";

abstract class Pokemon implements Training {

    protected $nama;
    protected $tipe;
    protected $level;
    protected $hp;
    protected $exp = 0;
    protected $kemampuanUnik;

    public function __construct($nama, $tipe, $level, $hp, $exp, $kemampuanUnik){
        $this->nama = $nama;
        $this->tipe = $tipe;
        $this->level = $level;
        $this->hp = $hp;
        $this->exp = $exp;
        $this->kemampuanUnik = $kemampuanUnik;
    }

    public function getData(){
        return [
            "nama" => $this->nama,
            "tipe" => $this->tipe,
            "level" => $this->level,
            "hp" => $this->hp,
            "exp" => $this->exp,
            "kemampuanUnik" => $this->kemampuanUnik
        ];
    }

    public function expNeeded(){
        return $this->level * 50;
    }

    protected function addExp($amount){
        $this->exp += $amount;

        while($this->exp >= $this->expNeeded()){
            $this->exp -= $this->expNeeded();
            $this->level++;
            $this->hp += rand(3, 8);
        }
    }

    public function kemampuanUnik(){
        return "Pokemon ini menggunakan serangan khusus!";
    }

    public function buatTraining($tipe, $intensitas){
        $before = [
            "level" => $this->level,
            "hp" => $this->hp,
            "exp" => $this->exp
        ];

        $this->hp += round($intensitas * 0.5);
        $this->addExp($intensitas * 5);

        $after = [
            "level" => $this->level,
            "hp" => $this->hp,
            "exp" => $this->exp
        ];

        return [
            "before" => $before,
            "after" => $after,
            "special" => $this->kemampuanUnik()
        ];
    }
}