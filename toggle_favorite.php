<?php
session_start();
include("includes/config.php");
include("includes/functions.php");

$user_data = check_login($con);

$userId = (int) $user_data['id'];
$videoId = (int) ($_POST['video_id'] ?? 0);

if ($videoId > 0) {
    // Verifica se já está favoritado
    $check = mysqli_query($con, "SELECT 1 FROM favorites WHERE user_id = $userId AND video_id = $videoId");
    if (mysqli_num_rows($check) > 0) {
        // Remove
        mysqli_query($con, "DELETE FROM favorites WHERE user_id = $userId AND video_id = $videoId");
    } else {
        // Adiciona
        mysqli_query($con, "INSERT INTO favorites (user_id, video_id) VALUES ($userId, $videoId)");
    }
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
