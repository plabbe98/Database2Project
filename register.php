<?php
  // create short variable names
  $student_id = $_POST['student_id'];
  $name = $_POST['name'];
  $email = $_POST['email'];
  $dept_name = $_POST['dept_name'];
  $password = $_POST['password'];
  $DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
  $type = "student";
?>

<head>
  <title>UMass Lowell - Student Registration</title>
</head>
<body>
<h1>UMass Lowell</h1>
<h2>Student Registration</h2>
<?php
  $myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysql_error());
  $mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');


 $query = 'INSERT INTO student (student_id, name, email, dept_name, password) VALUES (?, ?, ?, ?, ?)';
 $stmt = $myconnection->prepare($query);
 $stmt->bind_param("issss", $student_id, $name, $email, $dept_name, $password);
 $stmt->execute();
 $stmt->close();

 $query = 'INSERT INTO account (email, password, type) VALUES (?, ?, ?)';
 $stmt = $myconnection->prepare($query);
 $stmt->bind_param("sss", $email, $password, $type);
 $stmt->execute();
 $stmt->close();

 $query = 'INSERT INTO undergraduate (student_id) VALUES (?)';
 $stmt = $myconnection->prepare($query);
 $stmt->bind_param("i", $student_id);
 $stmt->execute();
 $stmt->close();

 mysqli_close($myconnection);
 echo '<script>alert("Student successfully registered!")</script>';
  header("Refresh: 0; URL=home.html");
?>
</body>
</html>