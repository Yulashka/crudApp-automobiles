<?php
	// DELETE AN ENTRY -->
	// model code -->
	require_once "pdo.php"; //ask for pdo.php
	session_start(); //start the session

	//STEP 4
	if( isset($_POST['delete']) && isset($_POST['auto_id']) ) {
		$sql = "DELETE FROM autos WHERE auto_id = :zip";  //stuck the data in the placeholder
		$stmt = $pdo->prepare($sql); //prepare the statement
		$stmt->execute(array(':zip' => $_POST['auto_id']));
		$_SESSION['success'] = 'Record deleted';
		header( 'Location: index.php' ); //redirect
		return;
	}

	//STEP 1
	//Make sure that user_id is present 
	if ( ! isset($_GET['auto_id']) ) {
		$_SESSION['error'] = "Missing auto_id";
		header('Location: index.php');
		return;
	}

	//STEP 2
	//checking if the value that we got from Get request is correct
	$stmt = $pdo->prepare("SELECT make, auto_id FROM autos WHERE auto_id = :xyz");
	$stmt->execute(array(":xyz" => $_GET['auto_id'] ));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	if ( $row === false ) {
		$_SESSION['error'] = 'Bad value for auto_id';
		header('Location: index.php');
		return;
	}
?>

<!-- STEP 3 -->
<!-- view -->
<p>Confirm: Deleting <?= htmlentities($row['make']) ?> </p>
<!-- hidden variable here  -->
<form method="post">
	<input type="hidden" name="auto_id" value="<?= $row ['auto_id'] ?>">
	<input type="submit" value="Delete" name="delete">
	<a href="index.php">Cancel</a>
</form>