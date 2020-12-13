<?php
/** @var App\Controller\Admin\Users\EditForm $this */
use App\Factory;
use App\Models\User;


/** @var User $userModel */
$userModel = Factory::getSingleton(User::class);
$userId = $this->request->getParam('id');
$user = $userModel->getUserDataById($userId);
?>
<h3>User Id: <?php echo $userId ?></h3>

<div class="container">
    <form action="/admin/users/edit" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $userId ?>">
        <div>
            <label for="firstname">Fist name</label>
            <input type="text" placeholder="Enter Fist name" name="firstname" id="firstname" value="<?php echo $user['firstname'] ?>" required>
        </div>
        <div>
            <label for="lastname">Last name</label>
            <input type="text" placeholder="Enter Last name" name="lastname" id="lastname" value="<?php echo $user['lastname'] ?>" required>
        </div>
        <div>
            <label for="phone">Phone</label>
            <input type="phone" placeholder="Enter Phone Number" name="phone" id="phone" value="<?php echo $user['phone'] ?>" required>
        </div>
        <?php if ($user['logo_img_url']) { ?>
            <div>
                <div>
                    <span>User logo:</span>
                    <img src="<?php echo $user['logo_img_url']?>" alt="User Logo" width="256">
                    <div>
                        <span>Remove user logo:</span>
                        <input type="checkbox" name="remove-user-logo">
                    </div>
                </div>
            </div>
        <?php } ?>
        <div>
            <label for="enabled">Enabled:</label>
            <select name="enabled" id="enabled">
                <option value="1" <?php echo ($user['enabled'] == 1) ? "selected" : "" ?>>Yes</option>
                <option value="0" <?php echo ($user['enabled'] == 0) ? "selected" : "" ?>>No</option>
            </select>
        </div>
        <div>
            <button type="button" value="Back" onclick="window.location.href='/admin/users'">Back</button>
            <input type="submit" value="Save">
        </div>
    </form>
</div>
