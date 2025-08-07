<?php
session_start();

include("includes/config.php");
include("includes/functions.php");

$user_data = check_login($con);

$yt_id = $_GET['id'] ?? '';
$yt_id = mysqli_real_escape_string($con, $yt_id);

$query = "SELECT * FROM videos WHERE youtube_video_id = '$yt_id' LIMIT 1";
$result = mysqli_query($con, $query);

if (!$result || mysqli_num_rows($result) == 0) {
  echo "Vídeo não encontrado.";
  exit;
}

$video = mysqli_fetch_assoc($result);

$videoId = (int) $video['id'];
$userId = (int) $user_data['id'];

$favQuery = "SELECT 1 FROM favorites WHERE user_id = $userId AND video_id = $videoId LIMIT 1";
$favResult = mysqli_query($con, $favQuery);
$isFavorited = mysqli_num_rows($favResult) > 0;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($video['title']) ?></title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/about.css">
</head>

<body>
  <?php include 'includes/header.php'; ?>
  <section class="hero-filme">
    <div class="overlay"></div>
    <div class="fundo-filme"
      style="background-image: url('https://img.youtube.com/vi/<?= htmlspecialchars($video['youtube_video_id']) ?>/hqdefault.jpg');">
    </div>


    <div class="conteudo-filme">
      <h1 class="titulo-filme"><?= htmlspecialchars($video['title']) ?></h1>
      <p class="descricao-filme">
        <?= nl2br(htmlspecialchars($video['description'] ?? 'Descrição indisponível.')) ?>
      </p>

      <div class="detalhes-filme">
        <span><strong>Lançamento:</strong>
          <?= htmlspecialchars($video['release_date'] ?? 'Data não informada') ?></span>
        <span><strong>Duração:</strong> <?= htmlspecialchars($video['duration'] ?? 'Desconhecida') ?></span>
        <span><strong>Gêneros:</strong> <?= htmlspecialchars($video['categories'] ?? 'Não informado') ?></span>
        <span><strong>Classificação:</strong> <?= htmlspecialchars($video['rating'] ?? 'Livre') ?></span>
      </div>

      <div class="acoes">
        <a href="video.php?id=<?= urlencode($video['youtube_video_id']) ?>" class="btn-play">▶ Assistir</a>
        <form action="toggle_favorite.php" method="POST" style="display:inline;">
          <input type="hidden" name="video_id" value="<?= $video['id'] ?>">
          <button type="submit" class="btn-play">
            <?= $isFavorited ? '★ Remover dos Favoritos' : '☆ Adicionar aos Favoritos' ?>
          </button>
        </form>
      </div>
    </div>
  </section>
</body>

</html>