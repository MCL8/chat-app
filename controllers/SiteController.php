<?php

class SiteController
{
	public function actionIndex()
	{
		session_start();

		if(!isset($_SESSION['user_id'])) {
			header("location:login.php");
		}

		include ROOT . '/views/site/index.php';
	}
}