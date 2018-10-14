<?php

class UserController
{
	public function actionLogin()
	{
	    $db = DB::getConnection();

		session_start();

		$message = '';

		if(isset($_SESSION['user_id']))
		{
			header('location:index.php');
		}

		if(isset($_POST['login']))
		{
		    $usename = $_POST['username'];

			$sql = 'SELECT * FROM login ' .
                   'WHERE username = :username';

			$queryResult = $db->prepare($sql);
            $queryResult->bindParam(':username', $name, PDO::PARAM_STR);
			$queryResult->execute();

			$count = $queryResult->rowCount();
			
			if($count > 0)
			{
				$result = $queryResult->fetchAll();
				foreach($result as $row)
				{
					if(password_verify($_POST["password"], $row["password"]))
					{
						$_SESSION['user_id'] = $row['user_id'];
						$_SESSION['username'] = $row['username'];
						$sub_query = "
						INSERT INTO login_details 
			     		(user_id) 
			     		VALUES ('".$row['user_id']."')
						";
						$queryResult = $db->prepare($sub_query);
						$queryResult->execute();
						$_SESSION['login_details_id'] = $db->lastInsertId();
						header('location:index.php');
					}
					else
					{
						$message = '<label>Wrong Password</label>';
					}
				}
			}
			else
			{
				$message = '<label>Wrong Username</labe>';
			}
		}

		require_once ROOT . '/views/login/index.php';
	}
}