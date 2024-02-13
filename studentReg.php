<?php
  // create short variable names
  $student_id = $_POST['student_id'];
  $name = $_POST['name'];
  $email = $_POST['email'];
  $dept_name = $_POST['dept_name'];
  $DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
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


 $query = 'INSERT INTO student (student_id, name, email, dept_name) VALUES (?, ?, ?, ?)';
 $stmt = $myconnection->prepare($query);
 $stmt->bind_param("isss", $student_id, $name, $email, $dept_name);
 $stmt->execute();
 $stmt->close();
 mysqli_close($myconnection);
 echo "Student successfully registered!","<br />";
?>
</body>
</html>
