<?php

// $test = "test, test, test,";
// echo $test;
// echo "\n";
// echo substr($test, 0, -2);
require_once('Model/Autoloader.php');
Autoloader::register();

Database::createConnexion();

// function indexPathOrder($user) {

//     $sql = "SELECT * FROM `path_order` WHERE user = :user";
//     $req = Database::$_connection->prepare($sql);
//     $req->bindValue(":user", $user);
//     $req->execute();
//     $path = $req->fetchAll(PDO::FETCH_ASSOC);

//     return $path;
// }

// function indexMapPropriety($user) {
//     $sql = "SELECT * FROM `map_propriety` WHERE user = :user";
//     $req = Database::$_connection->prepare($sql);
//     $req->bindValue(":user", $user);
//     $req->execute();
//     $map_p = $req->fetch(PDO::FETCH_ASSOC);
//     return $map_p;
// }

// // print_r(indexPath('rootff'));

// print_r(indexPathOrder('rootff'));


$map = new Map('genbon', 10, 10);

// var_dump($map);
echo serialize($map);

