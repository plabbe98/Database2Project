<!DOCTYPE html>
<html>
  <head>
    <title>Appoint Page</title>
  </head>
  <body>
    <h1>University of Massachusetts Lowell</h1>
    <h2>Appoint Page</h2>
    <form action = "appointAction.php" method = "POST">
      <?php
        $myconnection = mysqli_connect('localhost', 'root', '', 'db2') or die('Could not connect: ' . mysqli_connect_error());

                
        if (mysqli_begin_transaction($myconnection)) {
          try {   
              $queryStudent = "SELECT DISTINCT student_id
              FROM PhD";

              $queryInstructor = "SELECT DISTINCT instructor_id
                                  FROM  instructor";


              $stmt0 = mysqli_prepare($myconnection, $queryStudent);
              mysqli_stmt_execute($stmt0);
              mysqli_stmt_bind_result($stmt0, $studentId);

              echo "<label for = 'student_id'>Student_id:</label>";
              echo "<select name = 'student_id' required>";
              while (mysqli_stmt_fetch($stmt0)) {
              echo "<option value = $studentId>$studentId</option>";
              }
              echo "</select> <br>";
              mysqli_stmt_close($stmt0);

              $stmt1 = mysqli_prepare($myconnection, $queryInstructor);
              mysqli_stmt_execute($stmt1);
              mysqli_stmt_bind_result($stmt1, $instructorId);

              echo "<label for= 'instructor_id'>Instructor_id:</label>";
              echo "<select name = 'instructor_id' required>";
              while (mysqli_stmt_fetch($stmt1)) {
                echo "<option value = $instructorId>$instructorId</option>";
              }
              echo "</select> <br>";
              mysqli_stmt_close($stmt1);
              mysqli_commit($myconnection) or die(mysqli_error($myconnection));
            } catch (Exception $e) {
              mysqli_rollback($myconnection);
              echo "Error: " . $e->getMessage();
            }
        } else {
          echo "Failed to start the transaction: " . mysqli_error($myconnection) . "<br>";
        }
        mysqli_close($myconnection);
      ?>
      Start date: <input type = "date" name = "start_date" required> <br>
      End date: <input type = "date" name = "end_date"> <br>
      <input type = "submit">
    </form>
  </body>
</html>