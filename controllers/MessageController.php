<?php

class MessageController
{
    /**
     * @return bool
     */
    public function actionInsertMessage()
    {
        $insert = false;

        if (isset($_POST)) {
            $insert = Message::insertMessage();
        }

        if ($insert) {
            $this->userChatHistory();
        }

        return true;
    }

    /**
     * @return bool
     */
    public function actionInsertGroupMessage()
    {
        $insert = false;

        if (isset($_POST)) {
            $insert = Message::insertGroupMessage();
        }

        if ($insert) {
            $this->groupChatHistory();
        }

        return true;
    }

    /**
     * @return bool
     */
    public function actionGetUserChatHistory()
    {
        $this->userChatHistory();

        return true;
    }

    /**
     * @return bool
     */
    public function actionGetGroupChatHistory()
    {
        $this->groupChatHistory();

        return true;
    }

    /**
     * @return bool
     */
    private function userChatHistory()
    {
        $db = DB::getConnection();
        $from_user_id = $_SESSION['user_id'];
        $to_user_id = $_POST['to_user_id'];
        $messagesList = Message::getUserChatHistory($from_user_id, $to_user_id);

        include ROOT . '/views/layouts/chat_history.php';

        return true;
    }

    /**
     * @return bool
     */
    private function groupChatHistory()
    {
        $db = DB::getConnection();
        $from_user_id = $_SESSION['user_id'];
        $messagesList = Message::getGroupChatHistory();

        include ROOT . '/views/layouts/chat_history.php';

        return true;
    }

}