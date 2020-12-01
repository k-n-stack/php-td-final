<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="main.style.css">
    <link rel="stylesheet" type="text/css" href="form.style.css">
    <link rel="stylesheet" type="text/css" href="map.style.css">
    <title>Document</title>
</head>
<body>

    <div class="map-form">

        <form action="?page=process-map" method="POST">

            <div>
                <label for="x_length">choose vertical size (between 10 and 20)</label>
                <input type="number" name="x_length" id="x_length" min="10" max="20" value="<?php if(isset($_POST['x_length'])); echo $_POST['x_length'];?>">
            </div>
            
            <div>    
                <label for="y_length">choose horizontal size (between 10 and 20)</label>
                <input type="number" name="y_length" id="y_length" min="10" max="20" value="<?php if(isset($_POST['y_length'])); echo $_POST['y_length'];?>">
            </div>
            
            <div>
                <button type="submit">Generate new map</button>
            </div>
            
        </form>

        <form action="?page=start-game" method="POST"><button type="submit">Start Game</button></form>
    </div>

    <div class="map">
        <?php if(!empty($_POST)) {
            include_once('map.structure.view.php');
        }?>
    </div>
    
</body>
</html>