<?php

class UpdateController
{
    public function updateLastActivity()
    {
        User::updateLastActivity();
    }

    public function getUser()
    {
        User::getUser();
    }

    public function getUserChatHistory()
    {
        User::getUserChatHistory($_SESSION['user_id'], $_POST['to_user_id']);
    }
}