<?php
//get session variables
session_start();
//assign the to the credentials
$host = $_SESSION["host"];
$user = $_SESSION["user"];
$pass = $_SESSION["passw"];

//for our purposes the DB is the same as the username ...
$dbName = $user;
//create the connection
$conn = new mysqli($host, $user, $pass, $dbName);
//check status of connection
if ($conn->connect_error)
        die("Could not connect:".mysqli_connect_error());
else
        echo " connected to database!<br>";

//create the query to insert in the inventory table
$stmt = $conn->prepare("INSERT INTO Inventory VALUES(?, ?) ON DUPLICATE KEY UPDATE Quantity = Quantity + ?;");
// for the prepared statement, give the tye (s=string) and variable name 
//   of parameter.
//  Other types include "i" 
//   one type for each ?, and one variable listed for each ?. 
$stmt->bind_param( "sii", $ingredient, $quantity, $quantity);

//get the result from the form in adding.html
$ingredient = $_POST["ingname"];
$quantity = $_POST["quantity"];

// execute the prepared statement. 
$stmt->execute();

//print a message to go back to main menu
echo "<a href=mainmenu.html>Main Menu</a>, Go back to main menu";

// close the connection (to be safe)
$conn->close();


?>
