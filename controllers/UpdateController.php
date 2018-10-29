<?php

class UpdateController
{
    public function actionUpdateLastActivity()
    {
        User::updateLastActivity();
        return true;
    }

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

    public function actionGetUserChatHistory()
    {
        $db = DB::getConnection();
        $from_user_id = $_SESSION['user_id'];
        $to_user_id = $_POST['to_user_id'];
        $messagesList = Message::getUserChatHistory($from_user_id, $to_user_id);

        include ROOT . '/views/layouts/chat_history.php';

        return true;
    }

    public function actionGetGroupChatHistory()
    {
        $db = DB::getConnection();
        $from_user_id = $_SESSION['user_id'];
        $messagesList =  Message::getGroupChatHistory();

        include ROOT . '/views/layouts/chat_history.php';

        return true;
    }

    public function actionUpdateIsType()
    {
        User::updateIsType();
        return true;
    }
}