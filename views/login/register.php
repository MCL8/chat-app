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
        <?php if ($result): ?>
            <div class="panel-body">
                <p class="text-success">You have successfully registered!</p>
                <a href="/">START CHAT</a>
            </div>
        <?php else: ?>

        <div class="panel-heading">Chat Application Register</div>
        <div class="panel-body">

            <?php if (isset($errors) && is_array($errors)): ?>
                <?php foreach ($errors as $error): ?>
                    <p class="text-danger"> - <?php echo $error; ?></p>
                <?php endforeach; ?>
            <?php endif; ?>

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
                    <input type="submit" name="submit" class="btn btn-info" value="Register" />
                </div>
            </form>
        </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>


