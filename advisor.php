<html>
  <head>
    <title>Advisor Page</title>
  </head>
  <body>
    <h1>University of Massachusetts Lowell</h1>
    <h2>Advisor Page</h2>
    <a href = "updateAdvisee.php">Update Advisee</a> <br><br><br>
    <?php       
                session_start();
                $email = $_SESSION['email'];
                $myconnection = mysqli_connect('localhost', 'root', '', 'db2') or die('Could not connect: ' . mysqli_connect_error());
                $query =   "SELECT DISTINCT student.name
                            FROM student, instructor, advise
                            WHERE ? = instructor.email AND 
                                  instructor.instructor_id = advise.instructor_id AND 
                                  student.student_id = advise.student_id
                            ORDER BY student.name ASC";
                $stmt1 = mysqli_prepare($myconnection, $query);
                mysqli_stmt_bind_param($stmt1, "s", $email);
                mysqli_stmt_execute($stmt1);
                mysqli_stmt_bind_result($stmt1, $student_name);
                
                
                echo 'Advisee list <hr>';
                echo '<table>';
                echo '<tr>';
                echo '<th>Advisee name</th>';
                echo '</tr>';
                while (mysqli_stmt_fetch($stmt1)) {
                  echo '<tr>';
                  echo '<td>' . $student_name . '</td>';
                  echo '</tr>';
                }
                echo '</table>';

                
                mysqli_stmt_close($stmt1);
                mysqli_close($myconnection);
    ?>
  </body>
</html>