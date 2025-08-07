<?php
  session_start();

	include("includes/config.php");
	include("includes/functions.php");


	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		//something was posted
		$user_name = $_POST['user_name'];
		$password = $_POST['password'];

		if(!empty($user_name) && !empty($password) && !is_numeric($user_name))
		{

			//save to database
			$user_id = random_num(20);
			$query = "insert into users (user_id,user_name,password) values ('$user_id','$user_name','$password')";

			mysqli_query($con, $query);

			header("Location: login.php");
			die;
		}else
		{
			echo "Caracteres inválidos!";
		}
	}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro - StreamDark</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php include 'includes/header.php'; ?>
  <div class="form-container">
    <form class="form-box">
      <h2 class="form-titulo">Criar Conta</h2>
      <div class="input-group">
        <label for="nome">Nome</label>
        <input type="text" id="nome" name="nome" required>
      </div>
      <div class="input-group">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="input-group">
        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" required>
      </div>
      <button type="submit" class="btn">Cadastrar</button>
      <div class="links-uteis">
        <span>Já possui uma conta?</span>
        <a href="login.php">Entrar</a>
      </div>
    </form>
  </div>
  <?php include 'includes/footer.php'; ?>
</body>
</html>
