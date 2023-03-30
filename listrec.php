<?php
//start session to use session variables
session_start();
//assign credentials
$host = $_SESSION["host"];
$user = $_SESSION["user"];
$pass = $_SESSION["passw"];

//remember, for our purposes the DB is the same as the username ...
$dbName = $user;
//create conn
$conn = new mysqli($host, $user, $pass, $dbName);
//check conn
if ($conn->connect_error)
        die("Could not connect:".mysqli_connect_error());

//prepare statemn to list all the ingredients
$stmt = $conn->prepare("SELECT * FROM Recipes WHERE RecipeName=?;");
$stmt->bind_param( "s", $name);

//get the value from the form thats is going to be binded to the prepare statement
$name = $_POST["recipename"];

// execute the prepared statement. 
$stmt->execute();
//get the result from the query
$stmt->bind_result($rname, $iname, $quant); 

// print result
$stmt->fetch();
echo "<h3> Recipe: ".$rname."</h3>";
echo "<table border=1>";
echo "<tr> <th>Ingredient</th> <th>Quantity</th> </tr>";
echo "<tr> <td>".$iname."</td>"."<td>".$quant."</td> </tr>";
// as long as there are more rows in result, pull next row into 
//    bound variables (see bind_result() above)
while($stmt->fetch() ) 
    {
        echo "<tr> <td>".$iname."</td>". 
                  "<td>".$quant."</td> </tr>";
    }

echo "</table>";
echo "<br>";
echo ("All done!"); 
echo "<br>";
echo "<a href=mainmenu.html>Main Menu</a>, Go back to main menu";

// close the connection (to be safe)
$conn->close();


?>
