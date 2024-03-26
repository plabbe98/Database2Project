<?php
  $DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
  session_start();
  $email = $_SESSION['email'];
  $password = $_SESSION['password'];
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


 $query = 'SELECT student_id, name, dept_name FROM student WHERE email = ? AND password = ?';
 $stmt = $myconnection->prepare($query);
 $stmt->bind_param("ss", $email, $password);
 $stmt->execute();
 $stmt->bind_result($result_student_id, $result_name, $result_dept_name); // Bind result variables
 $stmt->fetch(); // Fetch the result
 $stmt->close();
 mysqli_close($myconnection);
?>

<form action="editinfo.php" method="post">
<table border="0">
<tr>
  <td>Student ID</td>
  <td align="left"><input type="text" name="student_id" size="8" maxlength="8" value="<?php echo $result_student_id; ?>"readonly/></td>
</tr>
<tr>
  <td>Name</td>
  <td align="left"><input type="text" name="name" size="20" maxlength="20" value="<?php echo $result_name; ?>"/></td>
</tr>
<tr>
  <td>Email</td>
  <td align="left"><input type="text" name="email" size="20" maxlength="20" value="<?php echo $email; ?>" readonly/></td>
</tr>
<tr>
  <td>Password</td>
  <td align="left"><input type="text" name="password" size="20" maxlength="20" value="<?php echo $password; ?>"/></td>
</tr>
<tr>
  <td>Department Name</td>
  <td><select name="dept_name">
    <option value="<?php echo $result_dept_name; ?>"><?php echo $result_dept_name; ?></option>
    <option value="Miner School of Computer & Information Sciences">Miner School of Computer & Information Sciences</option>
  </select>
  <inputtype="submit" value="Submit"></td>
</tr>
<tr>
  <td colspan="2" align="center"><input type="submit" value="Save"/></td>
</tr>
</table>
</form>

</body>
</html>


<form action="coursereg.php" method="post">
<table border="0">
 <input type="hidden" name="student_id" value="<?php echo $result_student_id; ?>">
<tr>
  <td colspan="2" align="center"><input type="submit" value="Register for Courses"/></td>
</tr>
</table>
</form>

<form action="report.php" method="post">
<table border="0">
 <input type="hidden" name="student_id" value="<?php echo $result_student_id; ?>">
<tr>
  <td colspan="2" align="center"><input type="submit" value="Academic Advisement Report"/></td>
</tr>
</table>
</form>


<form action="schedule.php" method="post">
<table border="0">
 <input type="hidden" name="student_id" value="<?php echo $result_student_id; ?>">
<tr>
  <td colspan="2" align="center"><input type="submit" value="Schedule Meeting with Advisor"/></td>
</tr>
</table>
</form>

<form action="report.php" method="post">
<table border="0">
 <input type="hidden" name="student_id" value="<?php echo $result_student_id; ?>">
<tr>
  <td colspan="2" align="center"><input type="submit" value="Academic Advisement Report"/></td>
</tr>
</table>
</form>

</body>
</html>