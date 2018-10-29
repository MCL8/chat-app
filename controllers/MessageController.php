<?php

class MessageController
{
    public function actionInsertMessage()
    {
        if (isset($_POST)) {
            Message::insertMessage();
        }

        return true;
    }
    
    public function actionInsertGroupMessage()
    {
        if (isset($_POST)) {
            Message::insertGroupMessage();
        }

        return true;
    }

}