<?php /** @var App\Controller\HttpController $this */ ?>
<meta charset="UTF-8">
<link rel="icon" sizes="48x48" href="/favicon.ico">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=yes, shrink-to-fit=no">
<link rel="stylesheet" href="/media/css/default.css">

<?php if ($this->user->isLoggedIn()) { ?>
    <style>
        body {
            background-color: <?php echo $this->user->getAccountSettings('main_bkg_color') ?>;
            color: <?php echo $this->user->getAccountSettings('font_color') ?>;
            font-size: <?php echo $this->user->getAccountSettings('font_size') ?>px;
        }
        body footer, body #header {
            background-color: <?php echo $this->user->getAccountSettings('bkg_color') ?>
        }
        body .tab-tracker .task-group {
            border-style: solid;
            border-color: <?php echo $this->user->getAccountSettings('border_color') ?>;
        }
        body .tab-tracker .prepare {
            background-color: <?php echo $this->user->getAccountSettings('prep_task_color') ?>;
        }
    </style>
<?php } ?>