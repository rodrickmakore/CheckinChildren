<?php
error_reporting(E_ALL);
ini_set("display_errors",1);

require_once(dirname(__FILE__).'/../controllers/authController.php');
require_once(dirname(__FILE__) . '/../models/dao/companyDAO.php');
require_once(dirname(__FILE__).'/../models/companyModel.php');

$companyDAO=new companyDAO();
$company=$companyDAO->find($_COOKIE[cookieManager::$userId]);
?>

<h1>Account Profile</h1>
<table id="company_info_table" class="table">
    <tr>
        <th>Name</th>
        <td><?php echo $company->company_name; ?></td>
    </tr>
    <tr>
        <th>Address</th>
        <td><?php echo $company->address; ?></td>
    </tr>
    <tr>
        <th>Email</th>
        <td><?php echo $company->email; ?></td>
    </tr>
    <tr>
        <th>Phone Number</th>
        <td><?php echo $company->phone; ?></td>
    </tr>
</table>

<br>
<a class="btn btn-success" id="edit_company" href="editCompany.php"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit Information</a>
<a class="btn btn-success" id="update_password" href="updatePassword.php"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Change Password</a>

<hr>

<a id="home" href="index.php" class="btn btn-primary"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Home</a>


