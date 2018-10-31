<?php

class SiteController
{
    /**
     * @return bool
     */
	public function actionIndex()
	{
		//session_start();

		if(!isset($_SESSION['user_id'])) {
			header("location: login");
		}

		include ROOT . '/views/site/index.php';

		return true;
	}
}