<?php
require_once __DIR__ . "/classes/TrainingHistory.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    TrainingHistory::reset();

    header("Location: riwayat.php");
    exit;
}

header("Location: riwayat.php");
exit;