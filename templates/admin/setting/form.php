<?php
use App\Factory;
use App\Models\HomePageConfig;

/** @var HomePageConfig $configModel */
$configModel = Factory::getSingleton(HomePageConfig::class);
$description = $configModel->getDescription();
$sliders = $configModel->getSliderItems();
?>

<form action="/admin/setting/save" method="post" enctype='multipart/form-data'>
    <div>
        <label for="description">Description:</label>
        <textarea name="description" id="description" placeholder="Enter Description" title="Enter Description">
            <?php echo $description ?>
        </textarea>
    </div>

    <?php
    for ($i = 0; $i <= 3; $i++) { ?>
        <fieldset>
            <div>Slider <?php echo $i + 1 ?>:</div>
            <div>
                <label for="image_<?php echo $i ?>">Image:</label>
                <input type="file" name="sliders[<?php echo $i ?>][image]" id="image_<?php echo $i ?>" placeholder="Select Image" title="Select Image" alt="Image">
                <img src="<?php echo $sliders[$i]['img'] ?>" alt="Image" width="64">
            </div>
            <div>
                <label for="txt_<?php echo $i ?>">Label:</label>
                <textarea name="sliders[<?php echo $i ?>][txt]" id="txt_<?php echo $i ?>" placeholder="Enter Label" title="Enter Label">
                    <?php echo $sliders[$i]['txt'] ?>
                </textarea>
            </div>
            <label for="">Show Slider:</label>
            <?php $show = $sliders[$i]['show'] ?>
            <select name="sliders[<?php echo $i ?>][show]">
                <option value="0" <?php echo ($show == 0) ? "selected" : "" ?>>No</option>
                <option value="1" <?php echo ($show == 1) ? "selected" : "" ?>>Yes</option>
            </select>
        </fieldset>
    <?php } ?>

    <input type="submit" value="Save">
</form>
