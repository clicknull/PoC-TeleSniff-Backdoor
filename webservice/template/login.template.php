<!DOCTYPE html>
<html>
<head>
	<title>HijackGram 1.0</title>
	<?php require_once('includes/header/header.php'); ?>
</head>
<body>
	<div class="ui container" style="margin-top:20px">
		<div class="ui segment stacked">
			<div class="ui grid">
			  <div class="four wide column"><img class="ui small centered image" src="images/logo.png" width="150px"></div>
			  <div class="ten wide column">
			  	<form class="ui form" method="post" accept="#">
				  <div class="field">
				    <input type="text" name="username" placeholder="username">
				  </div>
				  <div class="field">
				    <input type="password" name="password" placeholder="password">
				  </div>
				  <button class="ui button basic small" name="login" type="submit">Login</button>
				</form>
			  </div>
			</div>
		</div>
	</div>
	<?php
	if(isset($_POST['login']))
	{
		$login = (object) $_POST;
		if(isset($login->username) && isset($login->password))
		{
			$pass = md5($login->password);
			$select = $this->bdd->prepare("SELECT * FROM utilisateurs WHERE username = :username AND password = :password");
			$select->bindParam(':username', $login->username);
			$select->bindParam(':password', $pass);
			$select->execute();
			$fetch = $select->fetch();
			if($fetch['username'] == trim($login->username))
			{
				$_SESSION['login'] = (object) $fetch;
				echo "<script>document.location.href='/?'</script>";
				return True;
			}
			echo "no";
			return False;
		}
	}
	?>
</body>
</html>