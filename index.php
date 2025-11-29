<?php
require_once __DIR__ . "/classes/Blastoise.php";

$storage = __DIR__ . "/storage/pokemon.json";
if(!file_exists($storage)){
    file_put_contents($storage, json_encode([
        "level" => 10,
        "hp" => 120,
        "exp" => 0
    ], JSON_PRETTY_PRINT));
}
$state = json_decode(file_get_contents($storage), true);

$blast = new Blastoise($state["level"], $state["hp"], $state["exp"]);
$data = $blast->getData();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Blastoise - Pokédex Overview</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-b from-[#102A6B] via-[#8281E6] to-[#8DB9E3] text-white p-6">

<div class="max-w-4xl mx-auto bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl shadow-2xl p-8">

    <!-- Header -->
    <div class="flex items-center gap-6">
        <img src="assets/blastoise.png" class="w-52 drop-shadow-xl" alt="Blastoise">

        <div>
            <h1 class="text-4xl font-extrabold tracking-wide drop-shadow">
                Blastoise — Pokédex #009
            </h1>
            <p class="mt-2 text-[#F0F9FF]/80">
                Shellfish Pokémon • Tipe Air
            </p>
        </div>
    </div>

    <!-- Description -->
    <div class="mt-6 text-[#F0F9FF] leading-relaxed text-lg">
        <p>
            Blastoise adalah evolusi terakhir dari Squirtle. Pokémon ini memiliki 
            dua meriam air raksasa di punggungnya yang dapat menembakkan semburan 
            air bertekanan tinggi hingga mampu menghancurkan baja.
        </p>

        <p class="mt-3">
            Cangkang tebalnya memberikan pertahanan luar biasa, menjadikannya 
            salah satu Pokémon tipe Air paling kokoh. Blastoise juga mampu 
            mengarahkan semburan air dengan presisi tinggi untuk menyerang lawan 
            dari jarak jauh.
        </p>

        <p class="mt-3">
            Serangan andalannya, <strong>Hydro Cannon</strong>, adalah jurus 
            tingkat tinggi dengan daya hancur sangat besar. Selain itu, Blastoise 
            juga dikenal karena sifatnya yang tenang dan protektif.
        </p>
    </div>

    <div class="mt-8 p-5 bg-white/10 rounded-2xl border border-white/20">
        <h2 class="text-2xl font-bold mb-3">Status Pokémon</h2>

        <div class="grid grid-cols-3 gap-4 text-center text-[#F0F9FF]">
            <div>
                <p class="text-sm opacity-75">Level</p>
                <p class="text-3xl font-bold"><?= $data["level"] ?></p>
            </div>
            <div>
                <p class="text-sm opacity-75">HP</p>
                <p class="text-3xl font-bold"><?= $data["hp"] ?></p>
            </div>
            <div>
                <p class="text-sm opacity-75">EXP</p>
                <p class="text-3xl font-bold"><?= $data["exp"] ?></p>
            </div>
        </div>
    </div>

    <div class="mt-8 flex gap-4">
        <a href="latihan.php" 
           class="flex-1 bg-[#F0F9FF] text-[#012A4A] font-bold py-3 text-center rounded-xl shadow hover:bg-white transition">
            Mulai Latihan
        </a>

        <a href="riwayat.php" 
           class="flex-1 bg-white/20 text-white font-bold py-3 text-center rounded-xl shadow hover:bg-white/30 transition">
            Riwayat Latihan
        </a>
    </div>

</div>

</body>
</html>