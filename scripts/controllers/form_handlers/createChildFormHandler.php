<?php
/**
 * The form handler when a employee/manager submits form to create a child
 * Determines if submitted child is valid and adds to childDAO
 * If child is not valid, redirects to createChild page with error
 */

error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once(dirname(__FILE__) . '/../authController.php');
require_once(dirname(__FILE__) . '/../../errorManager.php');
require_once(dirname(__FILE__) . '/../../models/dao/childDAO.php');
require_once(dirname(__FILE__) . '/../../models/dao/logDAO.php');
require_once(dirname(__FILE__) . '/../../models/childModel.php');
require_once(dirname(__FILE__) . '/../../cookieManager.php');
require_once(dirname(__FILE__) . '/../../dateTimeProvider.php');
require_once(dirname(__FILE__) . '/../managerController.php');

$authController = new authController();
$authController->verifyRole(['employee','manager']);
$authController->redirectPage('../../../public/');

$manCon=new managerController();
$cookieManager = new cookieManager();
$cookies = $cookieManager->getCookies();
$facility_id=$manCon->getFacilityID($cookies[cookieManager::$userId]);

$child=new childModel($_POST['PID'], $_POST['name'],  $_POST['aller'], $_POST['trusted_parties'], $facility_id);
for ($i=0; $i<7; $i++){
    $child->expect_checkin[$i] = dateTimeProvider::minutesFromMidnight($_POST['ci-'.$i]);
    $child->expect_checkout[$i] = dateTimeProvider::minutesFromMidnight($_POST['co-'.$i]);
}

$lDAO= new logDAO();
$error_code = $child->isValid();
if ($error_code === 0) {
    $childDAO = new childDAO();
    $childId = $childDAO->insert($child);
    $lDAO->insert($cookies[cookieManager::$userId], $child->child_id, $child->child_name, logDAO::$childCreated);
    header("Location: ../../../public/index.php");
    exit();
} else {
    $lDAO->insert($cookies[cookieManager::$userId], $child->child_id, $child->child_name, logDAO::$childCreated, 'Error: '.errorManager::getErrorMessage($error_code));
    header("Location: ../../../public/createChild.php?error=".$error_code);
}