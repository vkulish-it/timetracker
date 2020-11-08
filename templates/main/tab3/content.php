<?php ?>
<?php $user = new \App\Models\User(); ?>
<h3>Account data settings</h3>

<div class="row" id="user-data">
    <button class="row-btn-collapsible collapsed">Account Data</button>
    <div class="container">
        <form action="/main/account/edit" method="post" enctype="multipart/form-data">
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
            <div>
                <span>Select image to upload:</span>
                <input type="file" name="user-logo" id="fileToUpload">
            </div>
            <div>
                <input type="submit" value="Save">
            </div>
        </form>
    </div>
</div>

<div class="row" id="user-design">
    <button class="row-btn-collapsible collapsed">Design</button>
    <div class="container">
        <form action="/main/account/design" method="post" enctype="multipart/form-data">
            <fieldset>
                <legend>Background Colors</legend>
                <div>
                    <label for="bkg_color">Header and Footer</label>
                    <input type="color" name="bkg_color" id="bkg_color"
                           value="<?php echo $user->getAccountSettings('bkg_color') ?>" required>
                </div>
                <div>
                    <label for="main_bkg_color">Content</label>
                    <input type="color" name="main_bkg_color" id="main_bkg_color"
                           value="<?php echo $user->getAccountSettings('main_bkg_color') ?>" required>
                </div>
                <div>
                    <label for="prep_task_color">Non Billable Issue</label>
                    <input type="color" name="prep_task_color" id="prep_task_color"
                           value="<?php echo $user->getAccountSettings('prep_task_color') ?>" required>
                </div>
            </fieldset>
            <div>
                <label for="border_color">Border Color</label>
                <input type="color" name="border_color" id="border_color"
                       value="<?php echo $user->getAccountSettings('border_color') ?>" required>
            </div>
            <div>
                <label for="font_color">Font Color</label>
                <input type="color" name="font_color" id="font_color"
                       value="<?php echo $user->getAccountSettings('font_color') ?>" required>
            </div>
            <div>
                <label for="font_size">Font Size</label>
                <input type="number" name="font_size" id="font_size"
                       value="<?php echo $user->getAccountSettings('font_size') ?>" required>
            </div>
            <div>
                <input type="submit" value="Save">
            </div>
        </form>
    </div>
</div>

<div class="row" id="user-tracker">
    <button class="row-btn-collapsible">Tracker</button>
    <div class="container">
        <form action="/main/account/time" method="post" enctype="multipart/form-data">
            <div>
                <label for="prep_task_name">Preparation time (Non Billable Issue)</label>
                <input type="text" name="prep_task_name" id="prep_task_name"
                       value="<?php echo $user->getAccountSettings('prep_task_name') ?>" required>
            </div>
            <fieldset>
                <legend>Default Time (hours) Per:</legend>
                <div>
                    <label for="time-day">Day</label>
                    <input type="number" name="wkg_hour_day" id="wkg_hour_day'"
                           value="<?php echo $user->getAccountSettings('wkg_hour_day') ?>" required>
                </div>
                <div>
                    <label for="time-week">Week</label>
                    <input type="number" name="wkg_hour_week" id="wkg_hour_week"
                           value="<?php echo $user->getAccountSettings('wkg_hour_week') ?>" required>
                </div>
                <div>
                    <label for="time-month">Month</label>
                    <input type="number" name="wkg_hour_month" id="wkg_hour_month"
                           value="<?php echo $user->getAccountSettings('wkg_hour_month') ?>" required>
                </div>
            </fieldset>
            <div>
                <input type="submit" value="Save">
            </div>
        </form>
    </div>
</div>

<div class="row" id="user-remove">
    <button class="row-btn-collapsible collapsed">Remove current account</button>
    <div class="container">
        <button onclick="deleteAccount()">Remove account</button>
    </div>
</div>
