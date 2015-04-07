<?php
/**
 * This is class displays the home page once a user logs in. For parents, it displays the current status of their child.
 */
require_once(dirname(__FILE__).'/../cookieManager.php');?>
<head>
    <title>CheckinChildren</title>
</head>
<h1>Welcome to Checkin Children</h1>
<!--<div id="signed-in"><h3>Currently signed in as a --><?php //echo $_COOKIE[cookieManager::$userRole]?><!--</h3></div>-->

<?php
if ($_COOKIE[cookieManager::$userRole] == "parent") {
    require_once(dirname(__FILE__) . '/../cookieManager.php');
    require_once(dirname(__FILE__) . '/../models/dao/childDAO.php');
    require_once(dirname(__FILE__) . '/../models/dao/parentDAO.php');
    require_once(dirname(__FILE__) . '/../models/childModel.php');
    require_once(dirname(__FILE__).'/../dateTimeProvider.php');

    $childDAO = new childDAO();
    $parentDAO = new parentDAO();

    $children = $childDAO->findChildrenWithParent($_COOKIE[cookieManager::$userId]); //find all of the parent's children

    $childList = "";

    foreach ($children as $child) { //THis loop appends a different colored gif to each child depending on their status
        if ($child->getStatus() == childStatus::here_due || $child->getStatus() == childStatus::not_here_late) {
            $childList = $childList . '<li class="list-group-item"><img src="../images/childStatus/flash.gif" width="8px" heigh="8px"> <a href="displayChild.php?child_id=' . $child->child_id . '">' . ($child->child_name) . "</a></li>";
        }
    }
    ?>
    <ul class="list-group">
        <?php echo $childList; ?>
    </ul>
<?php
}
?>


<?php
//    if ($_COOKIE[cookieManager::$userRole]=='manager') {
//        ?>
<!--        <a id="display_employee" href="displayEmployees.php">View My Employees</a>-->
<!--        <br><br>-->
<!--        <a id="create_parent" href="createParent.php">Create a Parent</a>-->
<!--        <br><br>-->
<!--        <a id="checkin_children" href="checkinChildren.php">View Children</a>-->
<!--        <br><br>-->
<!--        <a id="create_child" href="createChild.php">Create a Child</a> --><?php
//    }
//
//    else if ($_COOKIE[cookieManager::$userRole]=='employee') {
//        ?>
<!--        <a id="create_parent" href="createParent.php">Create a Parent</a>-->
<!--        <br><br>-->
<!--        <a id="checkin_children" href="checkinChildren.php">View Children</a>-->
<!--        <br><br>-->
<!--        <a id="create_child" href="createChild.php">Create a Child</a> --><?php
//    }
//
//    else if ($_COOKIE[cookieManager::$userRole]=='company') {
//        ?>
<!--            <a id="display_facilities" href="displayFacilities.php">View My Facilities</a><br>-->
<!--            <a id="display_managers" href="displayManagers.php">View My Managers</a>-->
<!--        --><?php
//    }
//
//    else if ($_COOKIE[cookieManager::$userRole]=='parent') {
//        ?>
<!--        <a id="view_parent_info" href="displayParentInfo.php">View My Profile</a>-->
<!--        <br><br>-->
<!--        <a id="display_children" href="displayChildren.php">My Children</a>-->
<!--    --><?php
//    }
//
//?>
<!--<form method="post" action="../scripts/controllers/logoutController.php">-->
<!--    <br><br>-->
<!--    <input type="submit" class="btn btn-primary" name="submit" value="Logout">-->
<!--</form>-->