<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<?php
if(isset($_GET['error'])) {
    if($_GET['error'] == 1) {
        echo "Invalid information";
    }
}
?>
<form method="post" action="../scripts/controllers/form_handlers/createManagerFormHandler.php">
    Name: <input type="text" name="name"><br>
    Facility Id: <input type="text" name="facility_id"><br>
    Email: <input type="text" name="email"><br>
    Password: <input type="text" name="password"><br>
    <input type="hidden" name="role" value="manager">
    <input type="submit" name="submit" value="Register">
</form>
<h3><a href="../public/index.php">Back to home</a></h3>
</body>
</html>
