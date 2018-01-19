<?php
	require_once "pdo.php";
	session_start();
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Iuliia Zemliana - Autos Database CRUD</title>

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	</head>

	<body>
		<div class="container">
		<h1>Welcome to the Automobiles Database</h1>
			<?php
			if ( isset($_SESSION['success']) ) {
				echo('<p style="color:green">'.$_SESSION["success"]."</p>\n");
				unset($_SESSION["success"]);
			}

			//check if we are logged in!
			if( ! isset($_SESSION["name"]) ) { ?>
			<p><a href="login.php">Please log in</a></p>
			<p>Attempt to <a href="add.php">add data</a> without logging in</p>
			<?php } else { ?>
			<?php
				// get data from DB
				// if there is at least one row
				// display data
				// else show message

				$nRows = $pdo->query('select count(*) from autos')->fetchColumn(); 
				if ($nRows == 0) {
					echo "<p>No rows found</p>";
				} else {
				//sneaking in some code, but its not a good idea 
				//displaying the table data
				echo('<table border="1">'."\n");
				echo "<tr><th>Make</th>";
				echo "<th>Model</th>";
				echo "<th>Year</th>";
				echo "<th>Mileage</th>";
				echo "<th>Action</th></tr>";
				//getting data from database
				$stmt = $pdo->query("SELECT make, model, year, mileage, auto_id FROM autos");
				while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
				    echo "<tr><td>";
					echo(htmlentities($row['make']));
					echo "</td><td>";
					echo(htmlentities($row['model']));
					echo "</td><td>";
					echo(htmlentities($row['year']));
					echo "</td><td>";
					echo(htmlentities($row['mileage']));
					echo "</td><td>";
					//last column - action column
					//anchor text, we passing whichever column we are working with
					//get parameter we get from url
					echo('<a href="edit.php?auto_id='.$row['auto_id'].'">Edit</a> / ');
					echo('<a href="delete.php?auto_id='.$row['auto_id'].'">Delete</a> ');
					echo("\n</form>\n");
					echo "</td></tr>\n";
				}
			}
			
			?>
			</table>
			<p style="margin-top:1em"><a href="add.php">Add New Entry</a></p>
			<p><a href="logout.php">Log Out</a></p>
			<?php } ?>
		</div>
	</body>
</html>

