<?php
  $DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
  $student_id = $_POST['student_id'];
?>


<head>
  <title>UMass Lowell - Student Registration</title>
</head>
<body>
<h1>UMass Lowell</h1>
<h2>Course Registration</h2>

<form action="checkPreReq.php" method="post">
<table border="0">
<tr>
  <td>Spring 2024</td>
  <td>
    <?php
      $semester = 'Spring';
      $year = 2024;

      $myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysqli_error());
      $mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');

      $query = 'SELECT course_id, section_id FROM section WHERE semester = ? AND year = ?';
      $stmt = $myconnection->prepare($query);
      $stmt->bind_param("si", $semester, $year);
      $result = $stmt->execute();
      $stmt->bind_result($course_id, $section_id); // Bind result variables
      
      if ($result) {
        while ($stmt->fetch()) {

          $value = "$course_id|$section_id";
          echo "<input type='radio' name='course' value='$value'>";
          echo "<label for='course'>$course_id - $section_id</label><br>";
        }
        $stmt->close();
      } else {
        echo "Error: " . $myconnection->error;
      }
    ?>

  </td>
</tr>
<tr>
 <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
  <td colspan="2" align="center"><input type="submit" value="Register"/></td>
</tr>

</table>
</form>
  <td colspan="2" align="left"><a href="studentmenu.php"><button>Go Back</button></a></td>

</body>
</html>