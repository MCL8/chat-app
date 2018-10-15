<html>  
    <head>  
        <title>Chat App</title>  
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    </head>  
    <body>  
        <div class="container">
			<br/>
			<br/>
			<div class="panel panel-default">
  				<div class="panel-heading">Chat Application Login</div>
				<div class="panel-body">
					<p class="text-danger"><?php echo $message; ?></p>
					<form method="post">
						<div class="form-group">
							<label>Enter Username</label>
							<input type="text" name="username" class="form-control" required />
						</div>
						<div class="form-group">
							<label>Enter Password</label>
							<input type="password" name="password" class="form-control" required />
						</div>
						<div class="form-group">
							<input type="submit" name="login" class="btn btn-info" value="Login" />
						</div>
					</form>
					<br/>
					<br/>
					<p><b>User 1</b></p>
					<p><b>Username</b> - johnsmith<br/><b>Password</b> - password</p>
					<p><b>Username</b> - peterParker<br/><b>Password</b> - password</p>
					<p><b>Username</b> - davidMoore<br/><b>Password</b> - password</p>
					<br/>
					<br/>
				</div>
			</div>
		</div>
    </body>  
</html>