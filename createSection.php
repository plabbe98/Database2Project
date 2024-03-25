<!DOCTYPE html>
<html>
  <head>
    <title>Create Section</title>
  </head>
  <h1>University of Massachusetts Lowell</h1>
  <h2>Create Section</h2>
  <body>
    <form action = "createSectionAction.php" method = "POST">
      <?php
        $myconnection = mysqli_connect('localhost', 'root', '', 'db2') or die('Could not connect: ' . mysqli_connect_error());
        if (mysqli_begin_transaction($myconnection)) {
          try {   
              $query0 = "SELECT DISTINCT instructor_id
                        FROM Instructor";

                                    
              $stmt0 = mysqli_prepare($myconnection, $query0);
              mysqli_stmt_execute($stmt0);
              mysqli_stmt_bind_result($stmt0, $instructor_id);

              echo "<label for = 'instructor_id'>instructor_id</label>";
              echo "<select name = 'instructor_id' required>";
              while (mysqli_stmt_fetch($stmt0)) {
              echo "<option value = $instructor_id>$instructor_id</option>";
              }
              echo "</select> <br>";
              mysqli_stmt_close($stmt0);

              $queryClassroom = "SELECT DISTINCT classroom_id
                                 FROM classroom";
              $stmtClassroom = mysqli_prepare($myconnection, $queryClassroom);
              mysqli_stmt_execute($stmtClassroom);
              mysqli_stmt_bind_result($stmtClassroom, $classroom_id);

              echo "<label for = 'classroom_id'>classroom_id</label>";
              echo "<select name = 'classroom_id' required>";
              while (mysqli_stmt_fetch($stmtClassroom)) {
              echo "<option value = $classroom_id>$classroom_id</option>";
              }
              echo "</select> <br>";
              mysqli_stmt_close($stmtClassroom);


              $query1 = "SELECT course_id
                         FROM course";

              $stmt1 = mysqli_prepare($myconnection, $query1);
              mysqli_stmt_execute($stmt1);
              mysqli_stmt_bind_result($stmt1, $course_id);

              echo "<label for = 'course_id'>course_id</label>";
              echo "<select name = 'course_id' required>";
              while (mysqli_stmt_fetch($stmt1)) {
              echo "<option value = $course_id>$course_id</option>";
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
      
      <label for="section">Section:</label>
      <input type ="text" id="section" name="section" required> <br>

      <label for="semester">Semester:</label>
      <select id="semester" name="semester" required>
        <option value = Fall>Fall</option>
        <option value = Winter>Winter</option>
        <option value = Spring>Spring</option>
        <option value = Summer>Summer</option>
      </select> <br>



      <label for="year">Year:</label>
      <input type="number" id="year" name="year" min="1990" max="2100" required/> <br>

      <label for="time_start">Time Start:</label>
      <input type="time" id="time_start" name="time_start" min="00:00" max="23:59" required/> <br>
      
      <label for="time_end">Time End:</label>
      <input type="time" id="time_end" name="time_end" min="00:00" max="23:59" required/> <br>
      
      <label for="monday">Monday</label> 
      <input type="checkbox" id="monday" name="monday" value="Mo"> <br>

      <label for="tuesday">Tuesday</label>
      <input type="checkbox" id="tuesday" name="tuesday" value="Tu"> <br>
      
      <label for="wednesday">Wednesday</label>
      <input type="checkbox" id="wednesday" name="wednesday" value="We"> <br> 
      
      <label for="thursday">Thursday</label>
      <input type="checkbox" id="thursday" name="thursday" value="Th"> <br> 
      
      <label for="friday">Friday</label>
      <input type="checkbox" id="friday" name="friday" value="Fr"> <br> 

      <label for="saturday">Saturday</label>
      <input type="checkbox" id="saturday" name="saturday" value="Sa"> <br>
      
      <label for="sunday">Sunday</label>
      <input type="checkbox" id="sunday" name="sunday" value="Su"> <br> 

      <input type = "submit">
    </form>
  </body>
</html>
