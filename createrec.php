<?php
//start session to use session variables
session_start();
//assign credentials
$host = $_SESSION["host"];
$user = $_SESSION["user"];
$pass = $_SESSION["passw"];

//remember, for our purposes the DB is the same as the username ...
$dbName = $user;
//create connection
$conn = new mysqli($host, $user, $pass, $dbName);
//check connection status
if ($conn->connect_error)
        die("Could not connect:".mysqli_connect_error());
else
        echo " connected to database!<br>";

//add ingredient to the table
$queryString = "create procedure if not exists AddIngredient(recipename varchar(100), ingredientname varchar(100), quantity integer)".
               "begin".
		"    INSERT INTO Recipes VALUES(recipename, ingredientname, quantity);".
		"end";
//check the procedure status
if (! $conn->query($queryString))
   die("Error creating procedure: " . $conn->error() );

//prepare the statemnt
$stmt = $conn->prepare("call AddIngredient(?, ?, ?)");

// for the prepared statement, give the tye (s=string) and variable name 
//   of parameter.
//  Other types include "i" 
//   one type for each ?, and one variable listed for each ?. 
$stmt->bind_param( "ssi", $name, $ingredient, $quantity);

//get the variables from the form
$name = $_POST["recipename"];
$ingredient = $_POST["ingname"];
$quantity = $_POST["quantity"];

echo "<a href=mainmenu.html>Main Menu</a>, Go back to main menu";
// execute the prepared statement. 
$stmt->execute();

// close the connection (to be safe)
$conn->close();


?>
