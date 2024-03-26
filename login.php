<?php
  $email = $_POST['email'];
  $password = $_POST['password'];
  $DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
  session_start();
  $_SESSION['email'] = $email;
  $_SESSION['password'] = $password;
?>

<head>
  <title>UMass Lowell - Login </title>
</head>
<body>
<h1>UMass Lowell</h1>
<h2>User Login</h2>
<?php
  $myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysql_error());
  $mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');


 $query = 'SELECT type FROM account WHERE email = ? AND password = ?';
 $stmt = $myconnection->prepare($query);
 $stmt->bind_param("ss", $email, $password);
 $stmt->execute();
 $stmt->bind_result($type); // Bind result variables
 $stmt->fetch(); // Fetch the result
 $stmt->close();
 mysqli_close($myconnection);
  if($type == 'student'){
    echo '<script>alert("User logged in!")</script>';
    header("Refresh: 0; URL=studentmenu.php");
  }
  elseif($type == 'admin'){
    echo '<script>alert("User logged in!")</script>';
    header("Refresh: 0; URL=adminmenu.php");
  }
  elseif($type == 'instructor'){
    echo '<script>alert("User logged in!")</script>';
    header("Refresh: 0; URL=instructormenu.php");
  }
  else{
     echo '<script>alert("Email/password are not valid credentials!")</script>';
     header("Refresh: 0; URL=home.html");
  }
?>
</body>
</html>