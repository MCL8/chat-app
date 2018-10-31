<?php

class Message
{
    /**
     * @param int $from_user_id
     * @param int $to_user_id
     * @return array
     */
    public static function getUserChatHistory(int $from_user_id, int $to_user_id)
    {
        $db = DB::getConnection();

        $sql = "SELECT * FROM message 
                WHERE (from_user_id = '".$from_user_id."' 
                AND to_user_id = '".$to_user_id."') 
                OR (from_user_id = '".$to_user_id."' 
                AND to_user_id = '".$from_user_id."') 
                ORDER BY timestamp DESC";

        $queryResult = $db->prepare($sql);
        $queryResult->execute();

        self::updateChatHistory($from_user_id, $to_user_id, $db);

        return $queryResult->fetchAll();
    }

    /**
     * @return array
     */
    public static function getGroupChatHistory()
    {
        $db = DB::getConnection();

        $sql = "SELECT * FROM message 
                WHERE to_user_id = '0'  
                ORDER BY timestamp DESC";

        $queryResult = $db->prepare($sql);
        $queryResult->execute();

        return $queryResult->fetchAll();
    }

    /**
     * @return bool
     */
    public static function insertMessage()
    {
        $db = DB::getConnection();

        $data = array(
            ':to_user_id' => $_POST['to_user_id'],
            ':from_user_id' => $_SESSION['user_id'],
            ':chat_message' => $_POST['chat_message'],
            ':status' => '1'
        );

        $sql = "INSERT INTO message 
                (to_user_id, from_user_id, chat_message, status) 
                VALUES (:to_user_id, :from_user_id, :chat_message, :status)";

        $queryResult = $db->prepare($sql);
        if ($queryResult->execute($data)) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
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

            $sql = "INSERT INTO message 
                    (from_user_id, chat_message, status) 
                    VALUES (:from_user_id, :chat_message, :status)";

            $queryResult = $db->prepare($sql);

            if ($queryResult->execute($data)) {
                return true;
            }

        }

        return false;
    }

    /**
     * @param int $from_user_id
     * @param int $to_user_id
     * @param PDO $db
     * @return bool
     */
    private static function  updateChatHistory(int $from_user_id, int $to_user_id, PDO $db)
    {
        $sql = "UPDATE message 
                SET status = '0' 
                WHERE from_user_id = '" . $to_user_id . "' 
                AND to_user_id = '" . $from_user_id . "' 
                AND status = '1'";

        $queryResult = $db->prepare($sql);
        $queryResult->execute();

        return true;
    }
}
