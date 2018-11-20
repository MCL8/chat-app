<html>  
    <head>
		<meta charset="UTF-8">
        <title>Chat App</title>  
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
              integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.rawgit.com/mervick/emojionearea/master/dist/emojionearea.min.css">
        <link rel="stylesheet" href="/template/css/style.css">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  		<script src="https://cdn.rawgit.com/mervick/emojionearea/master/dist/emojionearea.min.js"></script>
  		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
        <script src="/template/js/ajax.js"></script>
    </head>
    <body>
    <div class="container">
        <br/>

        <div class="row">
            <div class="col-md-8 col-sm-6">
                <h4>Users</h4>
            </div>
            <div class="col-md-2 col-sm-3">
                <input type="hidden" id="is_active_group_chat_window" value="no" />
                <button type="button" name="group_chat" id="group_chat" class="btn btn-primary btn-sm">Group Chat</button>
            </div>
            <div class="col-md-2 col-sm-3">
                <p class="align-right"><?php echo $_SESSION['username']; ?> - <a href="logout">Logout</a></p>
            </div>
        </div>
        <div class="table-responsive">
            <div id="user_details"></div>
            <div id="user_model_details"></div>
        </div>
        <br/>
        <br/>
        <br/>
    </div>
    </body>
</html>

<?php include ROOT . '/views/layouts/group_chat_window.php'; ?>