<?php include 'Modules/booking/View/layout.php' ?>

<!-- body -->     
<?php startblock('content') ?>

<div class="col-md-12" id="pm-content">
<div class="col-md-12" id="pm-form">
    
    <?php echo $form->htmlOpen() ?>
    <?php echo $form->getHtml($lang, false) ?>

    <?php
    $checked = "";
    if ($packageChecked) {
        $checked = "checked";
    }
    ?>
    
    <?php if($use_packages){ ?>
        <div class="checkbox col-xs-8 col-xs-offset-4">
            <label>
                <input id="use_package" type="checkbox" name="use_package" value="yes" <?php echo $checked ?>> <?php echo BookingTranslator::Use_Package($lang) ?>
            </label>
        </div>

        <div id="package_div">
            <?php echo $formPackage ?>
        </div>
    <?php } ?>
    <div id="resa_time_div">
        <?php echo $formEndDate ?>
    </div>
    
    <div class="col-xs-4 col-xs-offset-8">
    <?php if ($userCanEdit){ ?>	
        <input type="submit" class="btn btn-primary" value="Save" />
        <?php if ($id_reservation > 0){?>
        <button type="button" onclick="ConfirmDelete();" class="btn btn-danger"><?php echo CoreTranslator::Delete($lang) ?></button>
	<?php }} ?>
	<button type="button" class="btn btn-default" onclick="location.href='booking/<?php echo $id_space ?>'"><?php echo CoreTranslator::Cancel($lang) ?></button>
    </div>
    <?php echo $form->htmlClose() ?>
    
</div>
</div>
<script>
    var php_var = "<?php echo $packageChecked; ?>";
    if (php_var === 0){
        document.getElementById('resa_time_div').style.display = 'none';
    }
    else{
        document.getElementById('package_div').style.display = 'none';
    }
    document.getElementById('use_package').onchange = function () {
    document.getElementById('package_div').style.display = this.checked ? 'block' : 'none';
    document.getElementById('resa_time_div').style.display = !this.checked ? 'block' : 'none';

    };
</script>

<script type="text/javascript">
    	function ConfirmDelete()
    	{
            if (confirm("Are you sure you want to Delete this reseration ?")){
                location.href='bookingeditreservationdefaultdelete/<?php echo $id_space ?>/<?php echo $id_reservation ?>';
            }
    	}
</script> 
<?php
endblock();