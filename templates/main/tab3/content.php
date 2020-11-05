<?php ?>
<?php $user = new \App\Models\User(); ?>
<h3>Account data settings</h3>
<p>Template 3</p>
<form action="TODO" method="post">
    <p>Account Data:</p>
    <div>
        <label for="firstname">Fist name</label>
        <input type="text" placeholder="Enter Fist name" name="firstname" id="firstname" value="<?php echo $user->getAccountData('firstname') ?>" required>
    </div>
    <div>
        <label for="lastname">Last name</label>
        <input type="text" placeholder="Enter Last name" name="lastname" id="lastname" value="<?php echo $user->getAccountData('lastname') ?>" required>
    </div>
    <div>
        <label for="phone">Phone</label>
        <input type="phone" placeholder="Enter Phone Number" name="phone" id="phone" value="<?php echo $user->getAccountData('phone') ?>" required>
    </div>
    <input type="submit" value="Save">
</form>
