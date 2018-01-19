<?php
session_start();

require_once "pdo.php";

//when button 'cancel' is clicked
if ( isset($_POST['cancel'] ) ) {
  // Redirect the browser to index.php
  header("Location: index.php");
  return;
}

//our password
$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123


// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) {
  unset($_SESSION["name"]); //lougout current user
  //if input is empty send message thatemail and password must be included
  if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
      $_SESSION['error'] = "User name and password are required";
      header("Location: login.php");
      return; 
   } 
  //if user typed something, validate it
  else {
    //using test_input function to prepare the data
    $email = test_input($_POST["email"]);
    //validating for email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $_SESSION['error'] = "Email must have an at-sign (@)";
      header("Location: login.php");
      return; 
    }else {
      $check = hash('md5', $salt.$_POST['pass']);
      if ( $check == $stored_hash ) {
          // Redirect the browser to index.php
          $_SESSION['name'] = $_POST['email'];
          header("Location: index.php");
          error_log("Login success ".$_POST['email']);
          return;
      } else {
          $_SESSION['error'] = "Incorrect password";
          header("Location: login.php");
          error_log("Login fail ".$_POST['email']." $check");
          return; 
      }
    }
  }
}

//check the input data
function test_input($data) {
  //The trim() function removes whitespace and other predefined characters from both sides of a string.
  $data = trim($data);
  //The stripslashes() function removes backslashes
  $data = stripslashes($data);
  //The htmlspecialchars() function converts some predefined characters to HTML entities.
  $data = htmlspecialchars($data);
  return $data;
}
?>

<!DOCTYPE html>
<html>
  <head>
  <title>Iuliia Zemliana - Autos Database CRUD</title>

  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
</head>

<body>

<div class="container">
<h1>Please Log In</h1>
<?php
//printing the flash message
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
  <p>User Name: <input type="text" name="email"></p>
  <p>Password: <input type="text" name="pass"></p>
  <input type="submit" value="Log In">
  <input type="submit" name="cancel" value="Cancel">
</form>
<p>
For a password, view source and find a password
in the HTML comments.
<!-- The password is php (all lower case) followed by 123. -->
</p>
</div>
</body>


