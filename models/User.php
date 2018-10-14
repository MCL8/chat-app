<?php

class User
{
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
    
    public static function auth($userId)
    {
        $_SESSION['user'] = $userId;
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

    public static function getUser()
    {
        $db = DB::getConnection();

        session_start();

        $query = "
            SELECT * FROM login 
            WHERE user_id != '".$_SESSION['user_id']."' 
            ";

        $statement = $db->prepare($query);

        $statement->execute();

        $result = $statement->fetchAll();

        $output = '
            <table class="table table-bordered table-striped">
                <tr>
                    <th width="70%">Username</td>
                    <th width="20%">Status</td>
                    <th width="10%">Action</td>
                </tr>
            ';

        foreach($result as $row)
        {
            $status = '';
            $current_timestamp = strtotime(date("Y-m-d H:i:s") . '- 10 second');
            $current_timestamp = date('Y-m-d H:i:s', $current_timestamp);
            $user_last_activity = fetch_user_last_activity($row['user_id'], $db);
            if($user_last_activity > $current_timestamp)
            {
                $status = '<span class="label label-success">Online</span>';
            }
            else
            {
                $status = '<span class="label label-danger">Offline</span>';
            }
            $output .= '
                <tr>
                    <td>'.$row['username'].' '.count_unseen_message($row['user_id'], $_SESSION['user_id'], $db).' '.fetch_is_type_status($row['user_id'], $db).'</td>
                    <td>'.$status.'</td>
                    <td><button type="button" class="btn btn-info btn-xs start_chat" data-touserid="'.$row['user_id'].'" data-tousername="'.$row['username'].'">Start Chat</button></td>
                </tr>
                ';
        }

        $output .= '</table>';

        echo $output;
    }

    public static function  getUserLastActivity($user_id)
    {
        $db = DB::getConnection();
        
        $query = "
            SELECT * FROM login_details 
            WHERE user_id = '$user_id' 
            ORDER BY last_activity DESC 
            LIMIT 1
            ";
        
        $queryResult = $db->prepare($query);
        $queryResult->execute();
        $result = $queryResult->fetchAll();

        foreach($result as $row)
        {
            return $row['last_activity'];
        }
    }

    public static function getUserChatHistory($from_user_id, $to_user_id)
    {
        session_start();

        $db = DB::getConnection();

        $query = "
            SELECT * FROM chat_message 
            WHERE (from_user_id = '".$from_user_id."' 
            AND to_user_id = '".$to_user_id."') 
            OR (from_user_id = '".$to_user_id."' 
            AND to_user_id = '".$from_user_id."') 
            ORDER BY timestamp DESC
            ";

        $queryResult = $db->prepare($query);
        $queryResult->execute();
        $result = $queryResult->fetchAll();
        $output = '<ul class="list-unstyled">';

        foreach($result as $row)
        {
            $user_name = '';
            if($row["from_user_id"] == $from_user_id)
            {
                $user_name = '<b class="text-success">You</b>';
            }
            else
            {
                $user_name = '<b class="text-danger">'.get_user_name($row['from_user_id'], $db).'</b>';
            }
            $output .= '
		<li style="border-bottom:1px dotted #ccc">
			<p>'.$user_name.' - '.$row["chat_message"].'
				<div align="right">
					- <small><em>'.$row['timestamp'].'</em></small>
				</div>
			</p>
		</li>
		';
        }
        $output .= '</ul>';
        $query = "
            UPDATE chat_message 
            SET status = '0' 
            WHERE from_user_id = '".$to_user_id."' 
            AND to_user_id = '".$from_user_id."' 
            AND status = '1'
            ";
        $queryResult = $db->prepare($query);
        $queryResult->execute();

        return $output;
    }

}