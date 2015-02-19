<?php require_once(dirname(__FILE__).'/../cookieManager.php');?>

<h1>Welcome to Checkin Children</h1>
<div id="signed-in"><h3>Currently signed in as a <?php echo $_COOKIE[cookieManager::$userRole]?></h3></div>

<?php
    if ($_COOKIE[cookieManager::$userRole]=='manager') {
        ?>
        <a id="display_employee" href="displayEmployees.php">View My Employees</a>
        <br><br>
        <a href="createParent.php">Create a Parent</a> <?php
    }

    else if ($_COOKIE[cookieManager::$userRole]=='employee') {
        ?>
        <a href="createParent.php">Create a Parent</a> <?php
    }

    else if ($_COOKIE[cookieManager::$userRole]=='company') {
        ?> <a href="displayFacilities.php">View My Facilities</a> <?php
    }

?>
<form method="post" action="../scripts/controllers/logoutController.php">
    <br><br>
    <input type="submit" name="submit" value="Logout">
</form>