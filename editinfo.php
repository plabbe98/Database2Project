<?php
  $email = $_POST['email'];
  $password = $_POST['password'];
  $name = $_POST['name'];
  $dept_name = $_POST['dept_name'];
  $student_id = $_POST['student_id'];
  $DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
?>

<head>
  <title>UMass Lowell - Student Info</title>
</head>
<body>
<h1>UMass Lowell</h1>
<h2>Student Information</h2>

</body>
</html>

<?php
  $myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysql_error());
  $mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');


 $query = 'UPDATE student SET name = ?, dept_name = ?, password = ? WHERE student_id = ?';
 $stmt = $myconnection->prepare($query);

 $stmt->bind_param("sssi", $name, $dept_name, $password, $student_id);
 $stmt->execute();
 $stmt->close();

 $query = 'UPDATE account SET password = ? WHERE email = ?';
 $stmt = $myconnection->prepare($query);

 $stmt->bind_param("ss", $password, $email);
 $stmt->execute();
 $stmt->close();

 mysqli_close($myconnection);
 echo '<script>alert("Information saved!")</script>';
  header("Refresh: 0; URL=home.html");
?>

