<table class="table table-border table-hover">
        <tr class="thead-light">
            <th width="65%">Name</td>
            <th class="text-center" width="10%">Status</td>
            <th width="25%">Action</td>
        </tr>

    <?php foreach ($usersList as $user):
        $status = '';
        if (User::getUserLastActivity($user['user_id']) > $currentTime):
            $status = '<span class="badge badge-pill badge-success">Online</span>';
        else:
            $status = '<span class="badge badge-pill badge-danger">Offline</span>';
        endif; ?>

        <tr>
            <td><?php echo ($user['username'] . ' ' .  '<span class="label label-success">' .
                    User::countNewMessages($user['user_id'], $_SESSION['user_id'], $db) .
                    '</span>' . ' ' . User::getIsType($user['user_id'], $db)); ?></td>
            <td class="text-center"><?php echo $status; ?></td>
            <td><button type="button" class="btn btn-outline-primary btn-sm start_chat" data-touserid="<?php echo $user['user_id']; ?>"
                data-tousername="<?php echo $user['username']; ?>">Start Chat</button></td>
        </tr>

    <?php endforeach; ?>
</table>
