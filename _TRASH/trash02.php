<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="trash02.php" method="post">
    <input type="text" name="test1">
    <input type="text" name="test2">
    <input type="text" name="poulet">
    <input type="submit">
    </form>
</body>
</html>



<?php 

if(isset($_POST) and !empty($_POST)) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = htmlentities($value);
    }
}

echo $_POST['test1'];
echo $_POST['test2'];
echo $_POST['poulet'];