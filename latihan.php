<?php
require_once __DIR__ . "/classes/Blastoise.php";
require_once __DIR__ . "/classes/TrainingHistory.php";

$storage = __DIR__ . "/storage/pokemon.json";

if (!file_exists($storage)) {
    file_put_contents($storage, json_encode([
        "level" => 10,
        "hp" => 120,
        "exp" => 0,
        "unlockedSkills" => []
    ], JSON_PRETTY_PRINT));
}

$state = json_decode(file_get_contents($storage), true);
$unlocked = $state["unlockedSkills"] ?? [];

$blast = new Blastoise($state["level"], $state["hp"], $state["exp"], $unlocked);
$data = $blast->getData();

$hasil = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $jenis = $_POST["jenis"] ?? "Attack";
    $intensitas = max(1, intval($_POST["intensitas"] ?? 1));

    $hasil = $blast->buatTraining($jenis, $intensitas);

    $newData = $blast->getData();

    file_put_contents($storage, json_encode([
        "level" => $newData["level"],
        "hp" => $newData["hp"],
        "exp" => $newData["exp"],
        "unlockedSkills" => $newData["skillUnlocked"]
    ], JSON_PRETTY_PRINT));

    TrainingHistory::save([
        "pokemon" => $newData["nama"],
        "type" => $jenis,
        "intensity" => $intensitas,
        "before" => $hasil["before"],
        "after" => $hasil["after"],
        "skillsUnlocked" => $newData["skillUnlocked"],
        "specialUsed" => $hasil["special"],
        "time" => date("Y-m-d H:i:s")
    ]);

    $data = $blast->getData();
}

$expNeeded = $blast->expNeeded();
$expPercent = $expNeeded > 0 ? min(100, round(($data["exp"] / $expNeeded) * 100)) : 0;

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Latihan Blastoise</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-b from-[#102A6B] via-[#8281E6] to-[#8DB9E3] p-6 text-white flex justify-center">

<div class="max-w-5xl w-full bg-white/10 backdrop-blur-xl p-8 rounded-3xl shadow-2xl border border-white/20">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Latihan Blastoise</h1>
        <div class="flex gap-3">
            <a href="index.php" class="px-4 py-2 rounded-lg bg-white/20 hover:bg-white/30">Beranda</a>
            <a href="riwayat.php" class="px-4 py-2 rounded-lg bg-white/20 hover:bg-white/30">Riwayat</a>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6">

        <div class="col-span-1 bg-white/10 rounded-2xl p-5 text-center border border-white/20">
            <img src="assets/blastoise.png" class="w-44 mx-auto drop-shadow-xl">

            <h2 class="text-2xl font-bold mt-3"><?= $data["nama"] ?></h2>
            <p class="text-white/70 text-sm"><?= $data["tipe"] ?> • Water-type</p>

            <div class="mt-4 space-y-2 text-left">

                <div class="text-sm flex justify-between">
                    <span>Level:</span>
                    <span class="font-bold"><?= $data["level"] ?></span>
                </div>

                <div>
                    <div class="flex justify-between text-sm">
                        <span>EXP</span>
                        <span><?= $data["exp"] ?>/<?= $expNeeded ?></span>
                    </div>
                    <div class="w-full bg-white/10 rounded-full h-3 mt-1 overflow-hidden">
                        <div class="h-3 rounded-full" style="width: <?= $expPercent ?>%; background: #F0F9FF;"></div>
                    </div>
                </div>

                <div class="text-sm flex justify-between">
                    <span>HP:</span>
                    <span class="font-bold"><?= $data["hp"] ?></span>
                </div>

                <div class="text-sm mt-3">
                    <strong>Special:</strong>
                    <p class="text-white/70"><?= $data["kemampuanUnik"] ?></p>
                </div>

                <div class="mt-4">
                    <strong class="text-sm">Skill Dimiliki:</strong>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <?php foreach ($data["skillUnlocked"] as $s): ?>
                            <span class="px-2 py-1 bg-blue-100 text-blue-900 rounded-lg text-xs font-semibold">
                                <?= $s ?>
                            </span>
                        <?php endforeach; ?>

                        <?php if (empty($data["skillUnlocked"])): ?>
                            <span class="text-xs text-white/50">Belum ada skill yang terbuka</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-1 bg-white/10 rounded-2xl p-6 border border-white/20">
            <h3 class="text-xl font-bold">Mulai Latihan</h3>

            <form method="POST" class="mt-4 space-y-4">

                <div>
                    <label class="text-sm text-white/80">Jenis Latihan</label>
                    <select name="jenis" class="w-full p-2 mt-1 bg-white/20 text-white rounded-lg">
                        <option value="Attack">Attack</option>
                        <option value="Defense">Defense</option>
                        <option value="Speed">Speed</option>
                        <option value="Stamina">Stamina</option>
                        <option value="Hydropower">Hydropower (Bonus EXP)</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm text-white/80">Intensitas (1–100)</label>
                    <input type="number" name="intensitas" min="1" max="100"
                           value="5" class="w-full p-2 mt-1 bg-white/20 text-white rounded-lg">
                </div>

                <button type="submit"
                        class="w-full py-2 bg-[#F0F9FF] text-[#012A4A] rounded-xl font-bold hover:bg-white transition">
                    Latihan Sekarang
                </button>

            </form>

            <?php if($hasil): ?>
            <div class="mt-6 p-4 bg-white/10 rounded-xl border border-white/20">

                <h4 class="font-bold">Hasil Latihan</h4>

                <div class="grid grid-cols-2 gap-4 mt-3">

                    <div class="bg-white/10 p-3 rounded-xl">
                        <p class="text-sm opacity-75">Level</p>
                        <p class="text-xl font-bold"><?= $hasil["after"]["level"] ?></p>
                        <p class="text-xs opacity-70">Sebelumnya: <?= $hasil["before"]["level"] ?></p>
                    </div>

                    <div class="bg-white/10 p-3 rounded-xl">
                        <p class="text-sm opacity-75">HP</p>
                        <p class="text-xl font-bold"><?= $hasil["after"]["hp"] ?></p>
                        <p class="text-xs opacity-70">Sebelumnya: <?= $hasil["before"]["hp"] ?></p>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-sm opacity-75">Special Attack</p>
                    <p class="mt-1 bg-white/10 p-2 rounded-lg"><?= $hasil["special"] ?></p>
                </div>

                <div class="mt-4">
                    <p class="text-sm opacity-75">Skill Baru Terbuka</p>

                    <?php
                    $lockedBefore = array_diff($hasil["skillsLockedBefore"] ?? [], $hasil["skillsLockedAfter"] ?? []);
                    $unlockedNow = array_diff($hasil["skillsUnlockedAfter"] ?? [], $hasil["skillsUnlockedBefore"] ?? []);
                    ?>

                    <div class="flex flex-wrap gap-2 mt-1">
                        <?php foreach ($hasil["skillsUnlocked"] as $s): ?>
                            <span class="px-2 py-1 bg-blue-200 text-blue-900 rounded-lg text-xs">
                                <?= $s ?>
                            </span>
                        <?php endforeach; ?>
                    </div>

                </div>

            </div>
            <?php endif; ?>
        </div>

        <div class="col-span-1 bg-white/10 rounded-2xl p-6 border border-white/20">
            <h3 class="text-xl font-bold">Skill Belum Terbuka</h3>

            <div class="mt-3 flex flex-wrap gap-2">
                <?php foreach ($data["skillLocked"] as $s): ?>
                    <span class="px-3 py-1 bg-white/10 border border-white/20 rounded-lg opacity-50 text-xs">
                        <?= $s ?>
                    </span>
                <?php endforeach; ?>

                <?php if (empty($data["skillLocked"])): ?>
                    <p class="text-white/70 text-sm">Semua skill sudah terbuka!</p>
                <?php endif; ?>
            </div>

        </div>
    </div>

</div>

</body>
</html>