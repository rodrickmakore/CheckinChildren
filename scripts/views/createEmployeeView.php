<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<form method="post" action="../scripts/controllers/form_handlers/createEmployeeFormHandler.php">
    Name: <input type="text" name="name" > <br>
    Employee Email: <input type="text" name="email"> <br>
    Employee Password: <input type="text" name="password"><br>
    <input type="hidden" name="role" value="employee">
    <input type="submit" name="submit" value="Submit">
</form>
</body>
</html>