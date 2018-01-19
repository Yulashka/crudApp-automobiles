<?php
// EDIT EXISTING 
// you coming with get request and get auto_id 
	require_once "pdo.php";
	session_start(); //start the session

	//STEP 3
	//post request
	//validating auto data
	if ( isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year']) 
     && isset($_POST['mileage']) ) {
     	if ( strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || strlen($_POST['mileage']) < 1 || strlen($_POST['year']) < 1 ) {
	      		$_SESSION['error'] = "All fields are required";
	      		header("Location: edit.php?auto_id=".$_REQUEST['auto_id']);
				return;
   		}else {
   			//mileage and year must be numeric
     		if( ! is_numeric($_POST['year']) || ! is_numeric($_POST['mileage']) ){
     			$_SESSION['error'] = "Mileage and year must be numeric";
     			header("Location: edit.php?auto_id=".$_REQUEST['auto_id']);
				return;
     		}else {
	     		//data validation
				$sql = "UPDATE autos SET make = :make, model = :model, 
					year = :year, mileage = :mileage 
					WHERE auto_id = :auto_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':make' => $_POST['make'],
					':model' => $_POST['model'],
					':year' => $_POST['year'],
					':mileage' => $_POST['mileage'],
					':auto_id' => $_POST['auto_id']));
				//displat the message
				$_SESSION['success'] = 'Record edited';
				header('Location: index.php'); //redirect to the index.php
				return;
	     	}
   		}
	}
	
	//STEP 1 
	//checking if row exists
	$stmt = $pdo->prepare("SELECT * FROM autos where auto_id = :xyz");
	$stmt->execute(array(":xyz" => $_GET['auto_id'] ));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	if ( $row === false ) {
		$_SESSION['error'] = 'Bad value for user_id';
		header('Location: index.php');
		return;
	}

    //view 
	//STEP 2
	//checking the user input
	$m = htmlentities($row['make']);
	$mo = htmlentities($row['model']);
	$y = htmlentities($row['year']);
	$mi = htmlentities($row['mileage']);
	$auto_id = $row['auto_id'];
?>

<p>Editing Automobile</p>
<?php
	if ( isset($_SESSION['error']) ) {
		echo('<p style="color:red">'.htmlentities( $_SESSION["error"] )."</p>\n");
		unset($_SESSION["error"]);
	}
	if ( isset($_SESSION['success']) ) {
		echo('<p style="color:green">'.htmlentities( $_SESSION["success"] )."</p>\n");
		unset($_SESSION["success"]);
	}
?>
<form method="post">
	<p>Make:
		<input type="text" name="make" value="<?= $m ?>"></p>
	<p>Model:
		<input type="text" name="model" value="<?= $mo ?>"></p>
	<p>Year:
		<input type="text" name="year" value="<?= $y ?>"></p>
	<p>Mileage:
		<input type="text" name="mileage" value="<?= $mi ?>"></p>
	<input type="hidden" name="auto_id" value="<?= $auto_id ?>">
	<p>
		<input type="submit" value="Save"/>
		<a href="index.php">Cancel</a>
	</p>
</form>


