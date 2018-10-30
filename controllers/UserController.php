<?php

class UserController
{
    public function actionGetUser()
    {
        $db = DB::getConnection();
        $usersList = User::getUser();
        $status = '';

        $currentTime = strtotime(date("Y-m-d H:i:s") . '- 10 second');
        $currentTime = date('Y-m-d H:i:s', $currentTime);

        include ROOT . '/views/layouts/users_table.php';

        return true;
    }

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

    public function actionUpdateLastActivity()
    {
        User::updateLastActivity();
        return true;
    }

    public function actionUpdateIsType()
    {
        User::updateIsType();
        return true;
    }
}