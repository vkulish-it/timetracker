<?php
use App\Factory;
use App\Models\User;

/** @var User $usersModel */
$usersModel = Factory::getSingleton(User::class);
$users = $usersModel->getAllUsersData();
?>
<table border='1'>
    <thead>
        <td>id</td>
        <td>email</td>
        <td>phone</td>
        <td>firstname</td>
        <td>lastname</td>
        <td>logo</td>
        <td>enabled</td>
        <td>created at</td>
        <td>update at</td>
        <td>action</td>
    </thead>
    <?php foreach ($users as $id => $user) { ?>
        <tr>
            <td><?php echo $user['id'] ?></td>
            <td><?php echo $user['email'] ?></td>
            <td><?php echo $user['phone'] ?></td>
            <td><?php echo $user['firstname'] ?></td>
            <td><?php echo $user['lastname'] ?></td>
            <td>
                <?php if ($user['logo_img_url']) { ?>
                    <img src="<?php echo $user['logo_img_url'] ?>" alt="User Logo" width="64">
                <? } ?>
            </td>
            <td><?php echo $user['enabled'] ? "Yes" : "No" ?></td>
            <td><?php echo $user['created_at'] ?></td>
            <td><?php echo $user['update_at'] ?></td>
            <td>
                <a href="/admin/users/edit-form?id=<?php echo $user['id'] ?>">Edit</a>
            </td>
        </tr>
    <?php } ?>
</table>
