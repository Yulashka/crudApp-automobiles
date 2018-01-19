<?php
	session_start();
	require_once "pdo.php";
	
	//checking if user is logged in
	if ( ! isset($_SESSION['name']) ) {
    	die('ACCESS DENIED');
	}

	if ( isset($_POST["email"]) && isset($_POST["pass"]) ) {
		unset($_SESSION["name"]); //lougout current user
		if ($_POST["pass"] == 'php123') {
			$_SESSION["name"] = $_POST["email"];
			$_SESSION["success"] = 'Logged in';
			header('Location: add.php');
			return;
		} else {
			$_SESSION["error"] = 'Incorrect password';
			header('Location: login.php');
			return;
		}
	}

	//when button 'cancel' is clicked
	if( isset($_POST['cancel']) ) {
	  // Redirect the browser to index.php
	  header('Location: index.php');
	  return;
	}

	//validating auto data
	if ( isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year']) 
     && isset($_POST['mileage']) ) {
     	if ( strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || strlen($_POST['mileage']) < 1 || strlen($_POST['year']) < 1 ) {
      		$_SESSION['error'] = "All fields are required";
      		header("Location: add.php");
			return;
   		} 
   		else {
   			//mileage and year must be numeric
     		if( ! is_numeric($_POST['year']) || ! is_numeric($_POST['mileage']) ){
     			$_SESSION['error'] = "Mileage and year must be numeric";
     			header("Location: add.php");
				return;
     		}else {
	     		$stmt = $pdo->prepare('INSERT INTO autos
	        	(make, model, year, mileage) VALUES ( :mk, :md, :yr, :mi)');
		    	$stmt->execute(array(
		        ':mk' => $_POST['make'],
		        ':md' => $_POST['model'],
		        ':yr' => $_POST['year'],
	        	':mi' => $_POST['mileage']));
	        	$_SESSION['success'] = "Record added";
				header("Location: index.php");
				return;
	     	}
   		}
	}
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
<?php
echo('<h1>Tracking Autos for '.htmlentities( $_SESSION["name"])."</h1>\n");

if ( isset($_SESSION['error']) ) {
	echo('<p style="color:red">'.htmlentities( $_SESSION["error"] )."</p>\n");
	unset($_SESSION["error"]);
}
if ( isset($_SESSION['success']) ) {
	echo('<p style="color:green">'.htmlentities( $_SESSION["success"] )."</p>\n");
	unset($_SESSION["success"]);
}

//check if we are logged in!
if( ! isset($_SESSION["name"]) ) { 
	echo('<p>You are not logged in </p>');
}
?>
<form method="post">
<p>Make:
<input type="text" name="make" size="60"/></p>
<p>Model:
<input type="text" name="model" size="60"/></p>
<p>Year:
<input type="text" name="year"/></p>
<p>Mileage:
<input type="text" name="mileage"/></p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</form>
</div>
</body>
</html>



