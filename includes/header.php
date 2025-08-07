<?php
// header.php
$page = basename($_SERVER['PHP_SELF']);
?>
<header>
  <a href="index.php">
    <img src="logo.png" alt="TCCINE" class="logo-img">
  </a>

  <nav>
    <ul class="nav-links">
      <?php if (isset($_SESSION['user_id'])): ?>
          <li><a href="perfil.php"><i class="fas fa-user-plus"></i> Meu Perfil</a></li>
      <?php endif; ?>
      <?php if (!isset($_SESSION['user_id'])): ?>
          <li><a href="signup.php"><i class="fas fa-user-plus"></i> Cadastro</a></li>
          <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
      <?php endif; ?>
      <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </nav>
</header>