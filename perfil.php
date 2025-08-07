<?php
session_start();

include("includes/config.php");
include("includes/functions.php");

$user_data = check_login($con);

$favorites = [];
$history_items = [];

// Busca filmes favoritos do usuário
if (!empty($user_data['id'])) {
    $sqlFav = "
        SELECT v.id AS video_id,
               v.youtube_video_id,
               v.title,
               v.description,
               f.favorited_at
        FROM favorites f
        JOIN videos v ON f.video_id = v.id
        WHERE f.user_id = ?
        ORDER BY f.favorited_at DESC
        LIMIT 200
    ";
    if ($stmt = mysqli_prepare($con, $sqlFav)) {
        mysqli_stmt_bind_param($stmt, 'i', $user_data['id']);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($res)) {
            $favorites[] = $row;
        }
        mysqli_stmt_close($stmt);
    }

    // Busca histórico do usuário
    $sqlHist = "
        SELECT v.id AS video_id,
               v.youtube_video_id,
               v.title,
               v.description,
               h.watched_at
        FROM history h
        JOIN videos v ON h.video_id = v.id
        WHERE h.user_id = ?
        ORDER BY h.watched_at DESC
        LIMIT 200
    ";
    if ($stmt2 = mysqli_prepare($con, $sqlHist)) {
        mysqli_stmt_bind_param($stmt2, 'i', $user_data['id']);
        mysqli_stmt_execute($stmt2);
        $res2 = mysqli_stmt_get_result($stmt2);
        while ($row = mysqli_fetch_assoc($res2)) {
            $history_items[] = $row;
        }
        mysqli_stmt_close($stmt2);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>Perfil do Usuário</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/perfil.css">
</head>
<body>
  <?php include 'includes/header.php'; ?>

  <main class="perfil-container">
    <section class="perfil-info">
      <img src="<?php echo !empty($user_data['foto_perfil']) ? htmlspecialchars($user_data['foto_perfil'], ENT_QUOTES) : 'https://cdn-icons-png.freepik.com/512/9967/9967422.png'; ?>" alt="Foto de Perfil" class="perfil-foto" />
      <div class="perfil-detalhes">
        <h2>Olá, <?php echo htmlspecialchars($user_data['user_name'], ENT_QUOTES); ?>!</h2>
        <p>Membro desde: <?php echo htmlspecialchars($user_data['date'], ENT_QUOTES); ?></p>
      </div>
    </section>

    <section class="catalogo-section">
      <h3 class="catalogo-titulo">Filmes Favoritos</h3>
      <div class="itens">
        <?php if (empty($favorites)): ?>
          <p class="sem-favoritos">Você ainda não adicionou nenhum filme aos favoritos.</p>
        <?php else: ?>
          <?php foreach ($favorites as $fav): 
            $yt = htmlspecialchars($fav['youtube_video_id'], ENT_QUOTES);
            $title = htmlspecialchars($fav['title'], ENT_QUOTES);
            $favorited_at = !empty($fav['favorited_at']) ? date('d/m/Y H:i', strtotime($fav['favorited_at'])) : '';
          ?>
            <div class="filme-item-wrapper">
              <div class="filme-item">
                <a class="video-link" href="video.php?id=<?php echo urlencode($yt); ?>" title="<?php echo $title; ?>">
                  <img src="https://img.youtube.com/vi/<?php echo $yt; ?>/hqdefault.jpg" alt="<?php echo $title; ?>">
                </a>
              </div>
              <p class="filme-titulo"><?php echo $title; ?></p>
              <?php if ($favorited_at): ?>
                <p class="filme-data">Favoritado em: <?php echo $favorited_at; ?></p>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </section>

    <section class="catalogo-section">
      <h3 class="catalogo-titulo">Histórico</h3>
      <div class="itens">
        <?php if (empty($history_items)): ?>
          <p class="sem-historico">Você ainda não assistiu a nenhum vídeo.</p>
        <?php else: ?>
          <?php foreach ($history_items as $h): 
            $yt = htmlspecialchars($h['youtube_video_id'], ENT_QUOTES);
            $title = htmlspecialchars($h['title'], ENT_QUOTES);
            $watched_at = !empty($h['watched_at']) ? date('d/m/Y H:i', strtotime($h['watched_at'])) : '';
          ?>
            <div class="filme-item-wrapper">
              <div class="filme-item">
                <a class="video-link" href="video.php?id=<?php echo urlencode($yt); ?>" title="<?php echo $title; ?>">
                  <img src="https://img.youtube.com/vi/<?php echo $yt; ?>/hqdefault.jpg" alt="<?php echo $title; ?>">
                </a>
              </div>
              <p class="filme-titulo"><?php echo $title; ?></p>
              <?php if ($watched_at): ?>
                <p class="filme-data">Assistido em: <?php echo $watched_at; ?></p>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <?php include 'includes/footer.php'; ?>
  <script src="js/app.js"></script>
</body>
</html>
