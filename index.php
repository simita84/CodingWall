<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>CodingDojo Wall</title>
		<link rel="stylesheet" 
			  href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
		<link rel="stylesheet" 
			  href="styles.css">
	</head>
	<body class="container">		
		<div class="row">
			<h1 class="page-header">CodingDojo Wall</h1>
			<div class="error_message">
				<?php
					if(isset($_SESSION["error"])){
						echo $_SESSION["error"];
						unset($_SESSION["error"]);							
					}
				?>
			</div>
			<div class="col-md-5">
				<h4>Sign In</h4>
				<form action="process.php" method="post" 
				                           enctype="multipart/form-data"
				                           class="well" id="register">		 
					<div class="form-group">
						<label for="email"> * Email</label>
						<input type="email" name="email" 
							   placeholder="email@xxx.com" class="form-control">
					</div>
					<div class="form-group">
						<label for="password"> * Password</label>
						<input type="password" name="password" 
							   placeholder="password" class="form-control">
					</div>
						<input type="hidden" name="login" value="login">
					<div class="form-group">
						<input type="submit" value="Submit" class="btn btn-primary">
					</div>
				</form>		
			</div>
			<div class="col-md-5">
				<h4>Registration</h4>
				<form action="process.php" method="post" 
				                           enctype="multipart/form-data"
				                           class="well" id="register">		 
					<div class="form-group">
						<label for="name"> * Name</label>
						<input type="name" name="name" 
							   placeholder="email@xxx.com" class="form-control">
					</div>
					<div class="form-group">
						<label for="email"> * Email</label>
						<input type="email" name="email" 
							   placeholder="email" class="form-control">
					</div>
					<div class="form-group">
						<label for="password"> * Password</label>
						<input type="password" name="password" 
							   placeholder="password" class="form-control">
					</div>
						<input type="hidden" name="register" value="register">
					<div class="form-group">
						<input type="submit" value="Submit" class="btn btn-primary">
					</div>
				</form>		
			</div>
		</div>
	</body>
</html>