<ul class="list-unstyled">

    <?php foreach ($messagesList as $message):
        $username = '';

        if ($message["from_user_id"] == $from_user_id):
            $username = '<b class="text-success">You</b>';
        else:
            $username = '<b class="text-danger">' . User::getUserName($message['from_user_id'], $db).'</b>';
        endif; ?>

        <li style="border-bottom:1px dotted #ccc">
            <p><?php echo ($username . ' - ' . $message['chat_message']); ?>
                <div align="right">
                    - <small><em><?php echo $message['timestamp']; ?></em></small>
                </div>
            </p>
        </li>

    <?php endforeach; ?>
</ul>