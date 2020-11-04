<div id="current_date_time_block"></div>

<?php include_once ROOT_DIR . '/App/Models/User.php'; ?>
<?php $user = new \App\Models\User(); ?>
<?php if ($user->isLoggedIn()) { ?>
    <span><a href="/logout">Logout</a></span>
<?php } else { ?>
    <span><a href="/login">Login</a></span>
<?php } ?>
<span><?php echo $user->getName(); ?></span>

<img src="<?php echo $user->getLogoUrl(); ?>" alt="#" width="50" height="50" />


<script type="text/javascript">
    function currentTime() {
        let today = new Date();
        document.getElementById('current_date_time_block').innerHTML = fixFormat(today.getHours()) + ":" + fixFormat(today.getMinutes()) + ":" + fixFormat(today.getSeconds()) + " " + today.getFullYear() + "-" + fixFormat(today.getMonth()+1) + "-" + fixFormat(today.getDate());
    }
    function fixFormat(value) {
        if (value.toString().length === 1) {
            value = "0" + value;
        }
        return value;
    }
    setInterval(currentTime, 1000);
</script>
