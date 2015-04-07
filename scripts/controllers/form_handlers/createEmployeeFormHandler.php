<?php
/**
 * The form handler when a employee submits form to create a new employee account
 * Determines if submitted employee is valid and adds to employeeDAO and redirects to displayEmployees page
 * If employee account is not valid, redirects to createEmployee page with error
 */

require_once(dirname(__FILE__) . '/../../models/dao/employeeDAO.php');
require_once(dirname(__FILE__) . '/../../models/dao/logDAO.php');
require_once(dirname(__FILE__) . '/../../models/employeeModel.php');
require_once(dirname(__FILE__) . '/../../cookieManager.php');
require_once(dirname(__FILE__) . '/../managerController.php');

$manCon=new managerController();
$facility_id=$manCon->getFacilityID($_COOKIE[cookieManager::$userId]);
$hashedPassword = employeeModel::genHashPassword($_POST['password']);

$employee=new employeeModel($_POST['name'], $hashedPassword, $facility_id, $_POST['email'], $_POST['role']); //retreieve submitted POST data
$lDAO=new logDAO();

if ($employee->isValid()) {
    $employeeDAO=new employeeDAO();
    $empId=$employeeDAO->create_DCP($employee);
    $lDAO->insert($_COOKIE[cookieManager::$userId], $empId, $employee->emp_name, logDAO::$employeeCreated);
    header("Location: ../../../public/displayEmployees.php");
    exit();
} else {
    $lDAO->insert($_COOKIE[cookieManager::$userId], null, $employee->emp_name, logDAO::$employeeCreated, "Failure");
    header("Location: ../../../public/createEmployee.php?error=1");
    exit();
}
