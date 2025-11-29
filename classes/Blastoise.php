<?php
require_once __DIR__ . "/PokemonAir.php";

class Blastoise extends PokemonAir {

    private $armorShell = true;
    private $baseDamage = 90;

    private $skillPool = [
        "Water Pulse",
        "Aqua Tail",
        "Shell Smash",
        "Flash Cannon",
        "Hydro Pump",
        "Skull Bash",
        "Water Spout"
    ];

    private $unlockedSkills = [];

    public function __construct($level, $hp, $exp, $unlockedSkills = []){
        parent::__construct(
            "Blastoise",
            "Water",
            $level,
            $hp,
            $exp,
            "HYDRO CANNON"
        );

        $this->unlockedSkills = $unlockedSkills;
    }

    public function getData(){
        return [
            "nama" => $this->nama,
            "tipe" => $this->tipe,
            "level" => $this->level,
            "hp" => $this->hp,
            "exp" => $this->exp,
            "kemampuanUnik" => $this->kemampuanUnik,
            "skillUnlocked" => $this->unlockedSkills,
            "skillLocked" => array_values(array_diff($this->skillPool, $this->unlockedSkills))
        ];
    }

    private function openNewSkill(){
        $locked = array_values(array_diff($this->skillPool, $this->unlockedSkills));

        if (!empty($locked)) {
            $newSkill = $locked[0];
            $this->unlockedSkills[] = $newSkill;
        }
    }

    public function buatTraining($tipe, $intensitas){

        $before = [
            "level" => $this->level,
            "hp" => $this->hp,
            "exp" => $this->exp
        ];

        $oldLevel = $this->level;

        $expGain = $intensitas * 8;

        switch($tipe){
            case "Attack":
                $this->level += 1;
                $this->hp += 7;
                break;

            case "Defense":
                $this->level += 0.5;
                $this->hp += 12;
                break;

            case "Speed":
                $this->hp += 4;
                break;

            case "Stamina":
                $this->hp += 10;
                break;

            case "Hydropower":
                $this->hp += 6;
                $expGain *= 1.4;
                break;
        }

        $this->level = round($this->level);
        $this->hp = round($this->hp);

        $this->addExp($expGain);

        if ($this->level > $oldLevel) {
            $this->openNewSkill();
        }

        return [
            "before" => $before,
            "after" => [
                "level" => $this->level,
                "hp" => $this->hp,
                "exp" => $this->exp
            ],
            "special" => $this->kemampuanUnik(),
            "skillsUnlocked" => $this->unlockedSkills,
            "skillsLocked" => array_values(array_diff($this->skillPool, $this->unlockedSkills))
        ];
    }
}