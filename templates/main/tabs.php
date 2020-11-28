<div class="tabs">
    <div class="tab">
        <button id="tab-1-btn" class="tablinks" onclick="openTab('tab-1', this)">Tracker</button>
        <button class="tablinks" onclick="openTab('tab-2', this)">Statistic</button>
        <button class="tablinks" onclick="openTab('tab-3', this)">Account data settings</button>
    </div>
    <div class="tabcontent tab-tracker" id="tab-1">
        <?php include ROOT_DIR . "/templates/main/tab1/content.php"; ?>
    </div>
    <div class="tabcontent" id="tab-2">
        <?php include ROOT_DIR . "/templates/main/tab2/content.php"; ?>
    </div>
    <div class="tabcontent" id="tab-3">
        <?php include ROOT_DIR . "/templates/main/tab3/content.php"; ?>
    </div>
    <div class="clearfix"></div>
</div>