<?php
session_start();

include("includes/config.php");
include("includes/functions.php");

$user_data = check_login($con);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>TCCINE</title>
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <?php include 'includes/header.php'; ?>

  <main id="mainContent"></main>

  <?php include 'includes/footer.php'; ?>
  <script src="js/app.js"></script>
</body>

</html>