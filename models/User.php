<?php

class User
{
    public static function auth($userId)
    {
        $_SESSION['user'] = $userId;
    }

    public static function checkUserData(string $email, string $password)
    {
        $db = DB::getConnection();

        $sql = 'SELECT * FROM users WHERE email = :email';

        $queryResult = $db->prepare($sql);
        $queryResult->bindParam(':email', $email, PDO::PARAM_INT);
        $queryResult->execute();

        $user = $queryResult->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user['id'];
        }
        return false;
    }

    public static function getUser()
    {
        $db = DB::getConnection();

        $sql = "
            SELECT * FROM login 
            WHERE user_id != '".$_SESSION['user_id']."' 
            ";

        $queryResult = $db->prepare($sql);
        $queryResult->execute();
        $queryResult = $queryResult->fetchAll();

        return $queryResult;
    }

    public static function getUserLastActivity($user_id)
    {
        $db = DB::getConnection();

        $sql = "
            SELECT * FROM login_details 
            WHERE user_id = '$user_id' 
            ORDER BY last_activity DESC 
            LIMIT 1
            ";

        $queryResult = $db->prepare($sql);
        $queryResult->execute();

        return $queryResult->fetch()['last_activity'];
    }

    public static function getUserName($user_id, $connect)
    {
        $sql = "SELECT username FROM login WHERE user_id = '$user_id'";
        $statement = $connect->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();

        foreach($result as $row)
        {
            return $row['username'];
        }
    }

    public static function login($username, $password)
    {
        $db = DB::getConnection();
        $message = '';
        $sql = 'SELECT * FROM login ' .
            'WHERE username = :username';

        $queryResult = $db->prepare($sql);
        $queryResult->bindParam(':username', $username, PDO::PARAM_STR);
        $queryResult->execute();

        if($queryResult->rowCount() > 0) {
            $row = $queryResult->fetch();

                if(password_verify($password, $row["password"]))
                {
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['username'] = $row['username'];

                    self::insertLastActivity($row['user_id'], $db);

                    header('location: /');
                } else {
                    $message = '<label>Wrong Password</label>';
                }
        } else {
            $message = '<label>Wrong Username</label>';
        }

        return $message;
    }

    public static function countNewMessages($from_user_id, $to_user_id, $connect)
    {
        $from_user_id = intval($from_user_id);
        $to_user_id = intval($to_user_id);

        $sql = "
            SELECT * FROM chat_message 
            WHERE from_user_id = '$from_user_id' 
            AND to_user_id = '$to_user_id' 
            AND status = '1'
            ";

        $queryResult = $connect->prepare($sql);
        $queryResult->execute();
        $count = $queryResult->rowCount();

        if ($count > 0) {
            return $queryResult->rowCount();
        }

        return null;
    }

    public static function getIsType($user_id, $connect)
    {
        $user_id = intval($user_id);

        $sql = "
            SELECT is_type FROM login_details 
            WHERE user_id = '".$user_id."' 
            ORDER BY last_activity DESC 
            LIMIT 1
            ";

        $queryResult = $connect->prepare($sql);
        $queryResult->execute();
        $result = $queryResult->fetchAll();
        $output = '';

        foreach($result as $row)
        {
            if ($row["is_type"] == 'yes') {
                $output = ' - <small><em><span class="text-muted">Typing...</span></em></small>';
            }
        }

        return $output;
    }

    private static function insertLastActivity($user_id, $db)
    {
        $user_id = intval($user_id);

        $sql = "
                INSERT INTO login_details 
                (user_id) 
                VALUES ('" . $user_id . "')
                ";

        $queryResult = $db->prepare($sql);
        $queryResult->execute();

        $_SESSION['login_details_id'] = $db->lastInsertId();

        return true;
    }

    public static function updateIsType()
    {
        $db = DB::getConnection();

        $sql = "
            UPDATE login_details 
            SET is_type = '".$_POST["is_type"]."' 
            WHERE login_details_id = '".$_SESSION["login_details_id"]."'
            ";

        $queryResult = $db->prepare($sql);
        $queryResult->execute();
    }

    public static function updateLastActivity()
    {
        $db = DB::getConnection();

        session_start();

        $sql = "
            UPDATE login_details 
            SET last_activity = now() 
            WHERE login_details_id = '" . $_SESSION["login_details_id"]."'
        ";

        $queryResult = $db->prepare($sql);

        $queryResult->execute();
    }
}