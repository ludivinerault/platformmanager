<?php include 'Modules/invoices/View/layout.php' ?>

<!-- body -->     
<?php startblock('content') ?>

<div class="col-md-12 pm-table">
    <h3><?php echo BookingTranslator::Invoice_booking($lang) ?> </h3>
    <div class="col-md-12">
        <?php echo $formByProjects ?>
    </div> 
    <div class="col-md-12">
        <?php echo $formByPeriod ?>
    </div>
        
</div>

<?php endblock();