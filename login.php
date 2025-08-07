<?php
session_start();

	include("includes/config.php");
	include("includes/functions.php");


	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		//something was posted
		$user_name = $_POST['usuario'];
		$password = $_POST['senha'];

		if(!empty($user_name) && !empty($password) && !is_numeric($user_name))
		{

			//read from database
			$query = "select * from users where user_name = '$user_name' limit 1";
			$result = mysqli_query($con, $query);

			if($result)
			{
				if($result && mysqli_num_rows($result) > 0)
				{

					$user_data = mysqli_fetch_assoc($result);
					
					if($user_data['password'] === $password)
					{

						$_SESSION['user_id'] = $user_data['user_id'];
						header("Location: index.php");
						die;
					}
				}
			}
			
			echo "Usuário ou senha incorretos!";
		}else
		{
			echo "Usuário ou senha incorretos!";
		}
	}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php include 'includes/header.php'; ?>
  <div class="form-container">
    <form class="form-box" method="POST">
      <h2 class="form-titulo">Entrar</h2>
      <div class="input-group">
        <label for="usuario">E-mail ou Nome</label>
        <input type="text" id="usuario" name="usuario" required>
      </div>
      <div class="input-group">
        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" required>
      </div>
      <button type="submit" class="btn">Entrar</button>
      <div class="links-uteis">
        <a href="signup.php">Criar nova conta</a>
      </div>
    </form>
  </div>
  <?php include 'includes/footer.php'; ?>
</body>
</html>
