<?php include 'Modules/estore/View/layout.php' ?>

<!-- body -->     
<?php startblock('content') ?>
<div class="col-md-12 pm-table">
<div class="page-header">
    <h3><?php echo EstoreTranslator::ContactTypes($lang) ?></h3>
</div>    
<a class="btn btn-default" href="escontacttypeedit/<?php echo $id_space ?>"><?php echo EstoreTranslator::_New($lang) ?></a>
    <?php echo $tableHtml ?>
</div>
<?php
endblock();