<?php
/**
 * Creates a template to load in the page for the editing of employees viewed by employees and managers
 */

error_reporting(E_ALL); //turn on error reporting
ini_set("display_errors", 1);

require_once(dirname(__FILE__).'/../scripts/controllers/authController.php');
$authController = new authController();
$authController->verifyRole(['employee','manager']);
$authController->redirectPage();
?>

<!DOCTYPE html>
<html>
    <head>
        <?php require_once(dirname(__FILE__).'/importBoostrap.php'); ?>
    </head>

    <body>
        <?php require_once(dirname(__FILE__) . '/../scripts/views/navBarView.php'); ?>

        <div class="container-fluid">
            <?php require_once(dirname(__FILE__) . '/../scripts/views/displayError.php'); ?>

            <div class="row">
                <div class="col-sm-2 col-sm-offset-2">
                    <?php require_once(dirname(__FILE__) . '/../scripts/views/sideBarView.php'); ?>
                </div>
                <div class="col-sm-6">
                    <?php require_once(dirname(__FILE__).'/../scripts/views/editEmployeeView.php'); ?>
                </div>
            </div>
        </div>

        <?php require_once(dirname(__FILE__) . '/../scripts/views/footerView.php'); ?>
    </body>
</html>

