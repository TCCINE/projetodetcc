<?php
session_start();
include("includes/config.php");
include("includes/functions.php");

$user_data = check_login($con);
if (empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$ytId = $_GET['id'];

$stmt = $pdo->prepare("
    SELECT id, title, description, youtube_video_id, published_at
    FROM videos
    WHERE youtube_video_id = :yt
");
$stmt->execute(['yt' => $ytId]);
$video = $stmt->fetch();
if (!$video) {
    echo '<p>Vídeo não encontrado.</p>';
    exit;
}

$userId = (int) $user_data['id'];
$videoId = (int) $video['id'];
$pdo->prepare("INSERT INTO history (user_id, video_id) VALUES (?, ?)")->execute([$userId, $videoId]);

$stmt = $pdo->prepare("
    SELECT c.name
    FROM categories c
    JOIN video_categories vc ON vc.category_id = c.id
    JOIN videos v ON vc.video_id = v.id
    WHERE v.youtube_video_id = :yt
");
$stmt->execute(['yt' => $ytId]);
$cats = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title><?= htmlspecialchars($video['title'], ENT_QUOTES) ?> – Detalhes</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <main class="video-detail">

  <h1 style="text-align: center; padding-top:1em; padding-bottom:1em;"><?= htmlspecialchars($video['title'], ENT_QUOTES) ?></h1>
    <iframe
      class="hero"
      src="https://www.youtube.com/embed/<?= $video['youtube_video_id'] ?>?rel=0"
      allowfullscreen
    ></iframe>

    <article>
      

      <div class="actions">
        <a href="about.php?id=<?= urlencode($video['youtube_video_id']) ?>" class="btn back">← Voltar</a>
        <a
          href="https://www.youtube.com/watch?v=<?= $video['youtube_video_id'] ?>"
          target="_blank"
          rel="noopener"
          class="btn play"
        >
          ▶ Assistir no YouTube
        </a>
      </div>
    </article>

  </main>
</body>
</html>
