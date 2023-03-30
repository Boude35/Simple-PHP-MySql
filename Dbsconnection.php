<?php
//start session to use session variables
session_start();
//inizialize session variables with the results from the form
$_SESSION["host"] = $_POST["host"];
$_SESSION["user"] = $_POST["user"];
$_SESSION["passw"] = $_POST["password"];
//assign credentials
$host = $_SESSION["host"];
$user = $_SESSION["user"];
$pass = $_SESSION["passw"];

//remember, for our purposes the DB is the same as the username ...
$dbName = $user;
//create connection
$conn = new mysqli($host, $user, $pass, $dbName);
//check conn status
if ($conn->connect_error)
        die("Could not connect:".mysqli_connect_error());
else
        echo " connected!<br>";

// try and create the Recipes table (if it does not exist) ...
$queryString = "create table if not exists Recipes".
               " (RecipeName char(200), Ingredient char(100), Quantity integer, PRIMARY KEY(RecipeName, Ingredient))";

if (! $conn->query($queryString))
   die("Error creating table: " . $conn->error() );

// try and create Inventory table (if it does not exist) ...
$queryString = "create table if not exists Inventory".
               " (Ingredient char(100), Quantity integer, PRIMARY KEY(Ingredient))";

if (! $conn->query($queryString))
   die("Error creating table: " . $conn->error() );


// close the connection (to be safe)
$conn->close();

echo "<a href=mainmenu.html>Main Menu</a>, You are Ready to start";
?>
