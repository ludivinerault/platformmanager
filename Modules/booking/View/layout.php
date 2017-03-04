<!doctype html>
<?php require_once 'Modules/layout.php' ?>

<!-- header -->
    <?php startblock('title') ?>
    Core - Platform-Manager
    <?php endblock() ?>
        
    <?php startblock('stylesheet') ?>
    <link rel="stylesheet" href="externals/bootstrap/css/bootstrap.min.css">
    <link href="data/core/theme/navbar-fixed-top.css" rel="stylesheet">
    <link rel="stylesheet" href="Modules/core/Theme/core.css">
    <link href="externals/datepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <link rel='stylesheet' type='text/css' href='Modules/core/Theme/spacemenu.css' />
    
    <?php endblock() ?>
            
<!-- content -->

    <?php startblock('navbar') ?>
    <?php
    require_once 'Modules/core/Controller/CorenavbarController.php';
    $navController = new CorenavbarController();
    echo $navController->navbar();
    
    
    ?>
    <?php //include 'Modules/core/View/spacebar.php'; ?>
    <?php include 'Modules/booking/View/navbarbooking.php'; ?>
    <?php endblock();
