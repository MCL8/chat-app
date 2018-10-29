<?php

class UserController
{
	public function actionLogin()
	{
		$message = '';

		if(isset($_SESSION['user_id'])) {
		    header('location: /');
		}

		if (isset($_POST['login'])) {
		   $message = User::login($_POST['username'], $_POST["password"]);
		}

		require_once ROOT . '/views/login/index.php';

		return true;
	}

	public function actionLogout()
    {
        session_destroy();

        header('location: login');

        return true;
    }
}