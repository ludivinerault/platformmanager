<?php include 'Modules/core/View/spacelayout.php' ?>

<!-- body -->     
<?php startblock('content') ?>

<div class="col-md-12" style="margin-top: 7px; margin-bottom: -14px;">
    <?php
    if (isset($_SESSION["message"])) {
        ?>
        <div class="alert alert-info">
            <?php echo $_SESSION["message"] ?>
        </div>
        <?php
        unset($_SESSION["message"]);
    }
    ?>
</div>

<div class="col-md-12">

    <div class="col-md-7">
        <div class="col-md-12 pm-table-short">
            
            <?php echo $formUploadImages ?>
        </div>
        <div class="col-md-12 pm-table-short">
            <?php echo $tableHtml ?>
        </div>
    </div>
    <div class="col-md-5 pm-form">
        <?php echo $formDownload ?>
        <?php echo $formUpload ?>
    </div>


</div>

<?php
endblock();
