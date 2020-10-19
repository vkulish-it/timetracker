<div>HEADER: login/logout link, user name, current datetime, user logo</div>


<div id="current_date_time_block2"></div>

<script type="text/javascript">
    setInterval(function () {
        let today = new Date();
        document.getElementById('current_date_time_block2').innerHTML = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds(;
    }, 1000);
</script>

<!--<form enctype="multipart/form-data" method="post">-->
<!--    <p>Download user logo</p>-->
<!--    <p><input type="file" name="photo" multiple accept="image/*,image/jpeg">-->
<!--        <input type="submit" value="Download"></p>-->
<!--</form>-->
