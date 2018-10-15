<?php

class UpdateController
{
    public function updateLastActivity()
    {
        User::updateLastActivity();
        return true;
    }

    public function getUser()
    {
        User::getUser();
        return true;
    }

    public function getUserChatHistory()
    {
        User::getUserChatHistory($_SESSION['user_id'], $_POST['to_user_id']);
        return true;
    }
}