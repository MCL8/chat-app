<table class="table table-bordered table-striped">
    <tr>
        <th width="70%">Username</td>
        <th width="20%">Status</td>
        <th width="10%">Action</td>
    </tr>

    <?php foreach ($usersList as $user):
        $status = '';
        if (User::getUserLastActivity($user['user_id']) > $currentTime):
            $status = '<span class="label label-success">Online</span>';
        else:
            $status = '<span class="label label-danger">Offline</span>';
        endif; ?>

        <tr>
            <td><?php echo ($user['username'] . ' ' .  '<span class="label label-success">' . User::countNewMessages($user['user_id'], $_SESSION['user_id'], $db) .
                    '</span>' . ' ' . User::getIsType($user['user_id'], $db)); ?></td>
            <td><?php echo $status; ?></td>
            <td><button type="button" class="btn btn-info btn-xs start_chat" data-touserid="<?php echo $user['user_id']; ?>"
                data-tousername="<?php echo $user['username']; ?>">Start Chat</button></td>
        </tr>

    <?php endforeach; ?>

</table>