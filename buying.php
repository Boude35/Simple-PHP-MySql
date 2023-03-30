<?php
//start the session to use session variables
session_start();
//assign the credentials
$host = $_SESSION["host"];
$user = $_SESSION["user"];
$pass = $_SESSION["passw"];

//for our purposes the DB is the same as the username ...
$dbName = $user;
//create the connection to the database
$conn = new mysqli($host, $user, $pass, $dbName);
//check status of connection
if ($conn->connect_error)
        die("Could not connect:".mysqli_connect_error());
else
        echo " connected to database!<br>";

//create query to buy all the ingredients in a recipe from inventory using transaction
$queryString = "create procedure if not exists buying(recipename varchar(100))".
               "begin".
		"    START TRANSACTION;
			SELECT * FROM Recipes, Inventory WHERE RecipeName = recipename
			AND Recipes.Ingredient = Inventory.Ingredient FOR UPDATE;
			
			update Inventory, Recipes
			Set Inventory.Quantity = Inventory.Quantity - Recipes.Quantity
			Where Inventory.Ingredient = Recipes.Ingredient 
			AND Recipes.RecipeName = recipename;
			SELECT ROW_COUNT() INTO @count1;

			IF (SELECT Count(*) FROM Recipes WHERE Recipes.RecipeName = recipename) = @count1
			AND (SELECT COUNT(*) FROM Inventory WHERE Inventory.Quantity < 0) <= 0 THEN
				COMMIT;   				
			ELSE	
				ROLLBACK;
			END IF;".
		"end";

//check the procedure
if (! $conn->query($queryString))
   die("Error creating procedure: " . $conn->error() );

// build prepared statement, with a single parameter.
$stmt = $conn->prepare("call buying(?)");


//one type for each ?, and one variable listed for each ?. 
$stmt->bind_param( "s", $name);

//get the variable from the form
$name = $_POST["recipename"];


// execute the prepared statement. 
$stmt->execute();
echo "<a href=mainmenu.html>Main Menu</a>, Go back to main menu";

// close the connection (to be safe)
$conn->close();

?>
