<div id="current_date_time_block"></div>

<?php $config = include_once ROOT_DIR . '/config.php'; ?>
<?php if ($config['logged in'] === true) { ?>
    <p><a href="#">Logout</a></p>
<?php } else { ?>
    <p><a href="/templates/login/login.php">Login</a></p>
<?php } ?>
<?php echo ($config['user_name']); ?>
<img src="<?php echo $config['logo_path']; ?>" alt="#" width="50" height="50" />

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
