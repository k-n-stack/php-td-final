<?php

$main = 
'<form action="?page=next-turn" method="post">
    <input type="submit" name="submit" value="next_turn">
</form>';

$tower = 
'<form action="?page=next-turn" method="post">
<input type="submit" name="submit" value="next_turn">
</form>
<form action="?page=build-tower" method="post">
<button type="submit" name="type" value="arrow">Build_arrow_tower</button><br>
<button type="submit" name="type" value="fire">Build_fire_tower</button><br>
<button type="submit" name="type" value="lazer">Build_lazer_tower</button><br>
</form>';

$sell = 
'<form action="?page=next-turn" method="post">
<input type="submit" name="submit" value="next_turn">
</form>

<form action="?page=sell-tower" method="post">
<input type="submit" name="sell" value="sell_tower">
</form>';

if(isset($_POST['box'])) {
    echo $tower;
} elseif(isset($_POST['tower'])) {
    echo $sell;
} else {
    echo $main;
}