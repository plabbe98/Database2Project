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

 echo 'Title &nbsp; &nbsp; &nbsp; Year<br>';
 while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC)) { 
 echo $row["title"];
 echo "&nbsp;&nbsp;&nbsp;";
 echo $row["year"];
 echo '<br>';

 $query = 'INSERT INTO student (student_id, name, email, dept_name) VALUES ()';
 $result = mysqli_query($myconnection, $query) or die ('Query failed: ' . mysql_error());
 }
 mysqli_free_result($result);
 mysqli_close($myconnection);
?>
</body>
</html>
