<?php

class UserController
{
    /**
     * @return bool
     */
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

    /**
     * @return bool
     */
	public function actionLogin()
	{
		$message = '';

		if(isset($_SESSION['user_id'])) {
		    header('location: /');
		}

		if (isset($_POST['login'])) {
		   $message = User::login($_POST['username'], $_POST["password"]);

            if (strlen($message) == 0) {
                header('location: /');
            }
		}

		require_once ROOT . '/views/login/index.php';

		return true;
	}

    /**
     * @return bool
     */
	public function actionLogout()
    {
        session_destroy();

        header('location: login');

        return true;
    }

    public function actionRegister()
    {
        $username = false;
        $password = false;
        $result = false;

        if (isset($_POST['submit'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $errors = false;

            if (User::checkNameExists($username)) {
                $errors[] = 'This name is already taken';
            }

            if ($errors == false) {
                $result = User::register($username, $password);
            }

            if ($result) {
                User::login($username, $password);
            }
        }

        require_once (ROOT . '/views/login/register.php');
        return true;
    }

    public function actionUpdateLastActivity()
    {
        User::updateLastActivity();
        return true;
    }

    /**
     * @return bool
     */
    public function actionUpdateIsType()
    {
        User::updateIsType();
        return true;
    }
}