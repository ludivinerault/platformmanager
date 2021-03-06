<?php include 'Modules/core/View/layout.php' ?>

<!-- body -->
<?php startblock('content') ?>
<div class="col-xs-12 col-md-10 col-md-offset-1" id="pm-table">
    <div class="col-md-12">
        <div class="col-md-10">
            <h1>
                <?php echo CoreTranslator::Users($lang) ?>
            </h1>
        </div>
        <div class="col-md-2" style="margin-top: 20px;">
            <button type="button" onclick="location.href = 'coreusersedit/0'" class="btn btn-primary"><?php echo CoreTranslator::Add_User($lang) ?></button>
        </div>
    </div>
    <div>À utiliser avec précautions !!! La suppression d'utilisateurs ne supprime pas les données associées. N'utiliser qu'en cas de doublon ou de cas exotiques.</div>
    <div class="col-md-12">
        <?php echo $tableHtml ?>
    </div>
</div> <!-- /container -->
<?php
endblock();
