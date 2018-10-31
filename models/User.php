<?php

class User
{
    /**
     * @param int $userId
     */
    public static function auth(int $userId)
    {
        $_SESSION['user'] = $userId;
    }

    /**
     * @param string $username
     * @return bool
     */
    public static function checkNameExists(string $username)
    {
        $db = Db::getConnection();

        $sql = 'SELECT COUNT(*) FROM users WHERE username = :username';

        $queryResult = $db->prepare($sql);
        $queryResult->bindParam(':username', $username, PDO::PARAM_STR);
        $queryResult->execute();

        if ($queryResult->fetchColumn()) {
            return true;
        }

        return false;
    }

    /**
     * @param string $email
     * @param string $password
     * @return bool
     */
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

    /**
     * @return array|null|PDOStatement
     */
    public static function getUser()
    {
        if (isset( $_SESSION['user_id'])) {
            $db = DB::getConnection();

            $sql = "SELECT * FROM users 
                WHERE user_id != '" . $_SESSION['user_id'] . "' ";

            $queryResult = $db->prepare($sql);
            $queryResult->execute();
            $queryResult = $queryResult->fetchAll();

            return $queryResult;
        }

        return null;
    }

    /**
     * @param int $user_id
     * @return mixed
     */
    public static function getUserLastActivity(int $user_id)
    {
        $db = DB::getConnection();

        $sql = " SELECT * FROM user_activity 
                WHERE user_id = '$user_id' 
                ORDER BY last_activity DESC 
                LIMIT 1";

        $queryResult = $db->prepare($sql);
        $queryResult->execute();

        return $queryResult->fetch()['last_activity'];
    }

    /**
     * @param $user_id
     * @param $connect
     * @return mixed
     */
    public static function getUserName($user_id, $connect)
    {
        $sql = "SELECT username FROM users WHERE user_id = '$user_id'";
        $statement = $connect->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();

        foreach($result as $row)
        {
            return $row['username'];
        }
    }

    /**
     * @param string $username
     * @param string $password
     * @return string
     */
    public static function login(string $username, string $password)
    {
        $db = DB::getConnection();
        $message = '';
        $sql = 'SELECT * FROM users WHERE username = :username';

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
                } else {
                    $message = '<label>Wrong Password</label>';
                }
        } else {
            $message = '<label>Wrong Username</label>';
        }

        return $message;
    }


    /**
     * @param string $username
     * @param string $password
     * @return bool
     */
    public static function register(string $username, string $password)
    {
        $db = DB::getConnection();
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $sql = 'INSERT INTO users (username, password) 
                VALUES (:username, :password)';

        $queryResult = $db->prepare($sql);
        $queryResult->bindParam(':username', $username, PDO::PARAM_STR);
        $queryResult->bindParam(':password', $password, PDO::PARAM_STR);

        return $queryResult->execute();
    }

    /**
     * @param int $from_user_id
     * @param int $to_user_id
     * @param PDO $db
     * @return int|null
     */
    public static function countNewMessages(int $from_user_id, int $to_user_id, PDO $db)
    {
        $sql = "SELECT * FROM message 
                WHERE from_user_id = '$from_user_id' 
                AND to_user_id = '$to_user_id' 
                AND status = '1'";

        $queryResult = $db->prepare($sql);
        $queryResult->execute();
        $count = $queryResult->rowCount();

        if ($count > 0) {
            return $queryResult->rowCount();
        }

        return null;
    }

    /**
     * @param int $user_id
     * @param PDO $db
     * @return string
     */
    public static function getIsType(int $user_id, PDO $db)
    {
        $sql = "SELECT is_type FROM user_activity 
                WHERE user_id = '".$user_id."' 
                ORDER BY last_activity DESC 
                LIMIT 1";

        $queryResult = $db->prepare($sql);
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

    /**
     * @param int $user_id
     * @param PDO $db
     * @return bool
     */
    private static function insertLastActivity(int $user_id, PDO $db)
    {
        $user_id = intval($user_id);

        $sql = "INSERT INTO user_activity 
                (user_id) 
                VALUES ('" . $user_id . "')";

        $queryResult = $db->prepare($sql);
        $queryResult->execute();

        $_SESSION['login_details_id'] = $db->lastInsertId();

        return true;
    }

    /**
     * @return bool
     */
    public static function updateIsType()
    {
        $db = DB::getConnection();

        $sql = "UPDATE user_activity 
                SET is_type = '".$_POST["is_type"]."' 
                WHERE login_details_id = '" . $_SESSION["login_details_id"] . "'";

        $queryResult = $db->prepare($sql);
        return $queryResult->execute();
    }

    /**
     * @return bool
     */
    public static function updateLastActivity()
    {
        $db = DB::getConnection();

        session_start();

        $sql = "UPDATE user_activity 
                SET last_activity = now() 
                WHERE login_details_id = '" . $_SESSION["login_details_id"] . "'";

        $queryResult = $db->prepare($sql);

        return $queryResult->execute();

    }
}