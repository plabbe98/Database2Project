<!DOCTYPE html>
<html>
  <head>
    <title>Update Advisee</title>
  </head>
  <h1>University of Massachusetts Lowell</h1>
  <h2>Update Advisee</h2>
  <body>
    <form action = "updateAdviseeAction.php" method = "POST">
    <?php
        session_start();
        $myconnection = mysqli_connect('localhost', 'root', '', 'db2') or die('Could not connect: ' . mysqli_connect_error());
        if (mysqli_begin_transaction($myconnection)) {
          try {   
              $email = $_SESSION["email"];
              $query = "SELECT DISTINCT student_id
                               FROM Advise, Instructor
                               WHERE ? = Instructor.email AND 
                                     Instructor.instructor_id = Advise.instructor_id";

                                    
              $stmt0 = mysqli_prepare($myconnection, $query);
              mysqli_stmt_bind_param($stmt0, "s", $email);
              mysqli_stmt_execute($stmt0);
              mysqli_stmt_bind_result($stmt0, $studentId);

              echo "<label for = 'student_id'>Advisee_id:</label>";
              echo "<select name = 'student_id' required>";
              while (mysqli_stmt_fetch($stmt0)) {
              echo "<option value = $studentId>$studentId</option>";
              }
              echo "</select> <br>";
              mysqli_stmt_close($stmt0);
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
