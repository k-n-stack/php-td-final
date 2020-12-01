<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.style.css">
    <title>Home</title>
</head>
<body>
    <div class="welcome">
        <h1>Welcome <?php echo $_SESSION['user']->name;?></h1>
        <a href="?page=process-map"><h2>Proceed to Map generation...</h2></a>
        <a href="?page=process-logout"><h2>or logout.</h2></a>
    </div>
</body>
</html>