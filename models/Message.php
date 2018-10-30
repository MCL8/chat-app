<?php

class Message
{
    public static function getUserChatHistory($from_user_id, $to_user_id)
    {
        $db = DB::getConnection();

        $sql = "
            SELECT * FROM chat_message 
            WHERE (from_user_id = '".$from_user_id."' 
            AND to_user_id = '".$to_user_id."') 
            OR (from_user_id = '".$to_user_id."' 
            AND to_user_id = '".$from_user_id."') 
            ORDER BY timestamp DESC
            ";

        $queryResult = $db->prepare($sql);
        $queryResult->execute();

        self::updateChatHistory($from_user_id, $to_user_id, $db);

        return $queryResult->fetchAll();
    }

    public static function getGroupChatHistory()
    {
        $db = DB::getConnection();

        $sql = "
            SELECT * FROM chat_message 
            WHERE to_user_id = '0'  
            ORDER BY timestamp DESC
            ";

        $queryResult = $db->prepare($sql);
        $queryResult->execute();

        return $queryResult->fetchAll();
    }

    public static function insertMessage()
    {
        $db = DB::getConnection();

        $data = array(
            ':to_user_id' => $_POST['to_user_id'],
            ':from_user_id' => $_SESSION['user_id'],
            ':chat_message' => $_POST['chat_message'],
            ':status' => '1'
        );

        $sql = "
                INSERT INTO chat_message 
                (to_user_id, from_user_id, chat_message, status) 
                VALUES (:to_user_id, :from_user_id, :chat_message, :status)
                ";

        $queryResult = $db->prepare($sql);
        if ($queryResult->execute($data)) {
            return true;
        }

        return false;
    }

    public static function insertGroupMessage()
    {
        if($_POST["action"] == "insert_data")
        {
            $db = DB::getConnection();

            $data = array(
                ':from_user_id'	=> $_SESSION["user_id"],
                ':chat_message'	=> $_POST['chat_message'],
                ':status' => '1'
            );

            $sql = "
                INSERT INTO chat_message 
                (from_user_id, chat_message, status) 
                VALUES (:from_user_id, :chat_message, :status)
                ";

            $queryResult = $db->prepare($sql);

            if ($queryResult->execute($data)) {
                return true;
            }

        }

        return false;
    }

    private static function  updateChatHistory($from_user_id, $to_user_id, $db)
    {
        $sql = "
            UPDATE chat_message 
            SET status = '0' 
            WHERE from_user_id = '".$to_user_id."' 
            AND to_user_id = '".$from_user_id."' 
            AND status = '1'
            ";

        $queryResult = $db->prepare($sql);
        $queryResult->execute();
    }
}
