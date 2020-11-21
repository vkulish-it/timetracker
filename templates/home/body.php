<?php
$user = new \App\Models\User();
$config = new \App\Models\HomePageConfig();
$description = $config->getDescription();
$sliders = $config->getSliderItems();
?>
<div id="slider-1" class="owl-carousel owl-theme">
    <?php foreach ($sliders as $slider): ?>
        <div class="item">
            <img src="<?php echo $slider['img'] ?>" alt="">
            <div class="img-label"><?php echo $slider['txt'] ?></div>
        </div>
    <?php endforeach; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('.owl-carousel').owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 3
                },
                1024: {
                    items: 4
                }
            }
        })
    });
</script>

<p><?php echo $description ?></p>

<?php $quantity = new \App\Models\Quantity(); ?>
<?php echo "Quantity of registered users: " . $quantity->getQuantity() ?>

<?php if (!$user->isLoggedIn()) { ?>
    <h2>Login Form</h2>
    <?php include ROOT_DIR . "/templates/login/form.php"; ?>
<?php } ?>
