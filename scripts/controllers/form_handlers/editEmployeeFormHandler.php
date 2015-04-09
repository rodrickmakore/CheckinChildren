<?php
/**
 * The form handler when a employee submits form to edit their account
 * Determines if submitted employee is valid and updates record in employeeDAO and userDAO and redirects to index page
 * If employee information is not valid, redirects to editEmployee page with error
 */

require_once(dirname(__FILE__) . '/../../cookieManager.php');
require_once(dirname(__FILE__) . '/../../models/dao/employeeDAO.php');
require_once(dirname(__FILE__) . '/../../models/dao/logDAO.php');
require_once(dirname(__FILE__) . '/../../errorManager.php');

//Read in POST data from form
$employeeID = $_COOKIE[cookieManager::$userId];

//Store the contact preferences in a string
$employee = new employeeModel($_POST["employee_name"],"", 0, $_POST["email"], "", $employeeID);

$lDAO=new logDAO();

$error_code = $employee->isUpdateValid();
if ($error_code == 0) {
    $employeeDAO = new employeeDAO();
    $employeeDAO->update($employee);

    $lDAO->insert($_COOKIE[cookieManager::$userId], null, null, logDAO::$employeeEdited);
    header("Location: ../../../public/index.php"); //send browser to the page for newly created facility

    exit();
} else {
    $lDAO->insert($_COOKIE[cookieManager::$userId], null, null, logDAO::$employeeEdited, "Error: ".errorManager::getErrorMessage($error_code));
    header("Location: ../../../public/editEmployee.php?error=".$error_code); //redirect to employee creation page with error message
    exit();
}