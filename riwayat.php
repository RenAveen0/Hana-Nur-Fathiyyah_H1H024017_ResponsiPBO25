<?php
require_once __DIR__ . "/classes/TrainingHistory.php";

$history = TrainingHistory::getAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Latihan - Blastoise</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-b from-[#102A6B] via-[#8281E6] to-[#8DB9E3] text-white p-6 flex justify-center">

<div class="max-w-4xl w-full bg-white/10 backdrop-blur-xl p-8 rounded-3xl shadow-2xl border border-white/20">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold">Riwayat Latihan Blastoise</h1>

        <div class="flex gap-3">
            <a href="index.php" class="px-4 py-2 rounded-xl bg-white/20 hover:bg-white/30">
                Beranda
            </a>
            <a href="latihan.php" class="px-4 py-2 rounded-xl bg-[#F0F9FF] text-[#012A4A] font-bold hover:bg-white">
                Latihan
            </a>
        </div>
    </div>

    <div class="mt-8">

        <?php if (empty($history)): ?>
            <p class="text-center text-lg text-white/70 py-10">
                Belum ada riwayat latihan.<br>
                Ayo mulai melatih Blastoise!
            </p>

        <?php else: ?>

            <div class="space-y-5">
                <?php foreach (array_reverse($history) as $h): ?>
                    <div class="bg-white/10 border border-white/20 rounded-2xl p-5 shadow-lg backdrop-blur">

                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-white/70"><?= $h["time"] ?></p>
                                <p class="text-xl font-bold mt-1">
                                    <?= htmlspecialchars($h["type"]) ?> Training 
                                    <span class="text-sm opacity-80">(x<?= $h["intensity"] ?>)</span>
                                </p>
                            </div>

                            <div class="text-right text-sm">
                                <p>Level: 
                                    <span class="font-bold">
                                        <?= $h["before"]["level"] ?> → <?= $h["after"]["level"] ?>
                                    </span>
                                </p>
                                <p>HP: 
                                    <span class="font-bold">
                                        <?= $h["before"]["hp"] ?> → <?= $h["after"]["hp"] ?>
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="mt-3 bg-white/10 p-3 rounded-xl border border-white/10 text-sm">
                            <strong>Special:</strong> <?= htmlspecialchars($h["specialUsed"]) ?>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>

    </div>

    <div class="mt-10 text-center">
        <?php if (!empty($history)): ?>
            <form action="reset.php" method="POST"
                  onsubmit="return confirm('Yakin ingin menghapus seluruh riwayat dan mereset Blastoise ke status awal?')">
                <button class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-md">
                    Hapus Semua Riwayat & Reset Blastoise
                </button>
            </form>
        <?php endif; ?>
    </div>

</div>

</body>
</html>