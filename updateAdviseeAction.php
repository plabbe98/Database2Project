<html>
  <head>
    <title>Update Advisee</title>
  </head>
  <body>
  <h1>University of Massachusetts Lowell</h1>
  <h2>Update Advisee</h2>  
  <?php
     session_start();
     $sid = $_POST["student_id"];
     $email = $_SESSION["email"];
     $sdate = $_POST["start_date"];
     $edate = $_POST["end_date"];
     
     //if an ending date exist check if starting date is greater than the ending date
     //else if the ending date does not exist assign it the value of null.
     if ($edate && $sdate > $edate) {
         echo "The starting date could not be ahead of the ending date <br>";
         exit(1);
     } else if (!$edate) {
        $edate = null;
     }

     $myconnection = mysqli_connect('localhost', 'root', '', 'db2') or die('Could not connect: ' . mysqli_connect_error());
     
     if (mysqli_begin_transaction($myconnection)) {
         try {
            $queryInstructorId = "SELECT instructor_id
                                  FROM instructor
                                  WHERE ? = email";
            $stmt0 = mysqli_prepare($myconnection, $queryInstructorId);
            mysqli_stmt_bind_param($stmt0, "s", $email);
            mysqli_stmt_execute($stmt0);
            mysqli_stmt_bind_result($stmt0, $iid);
            mysqli_stmt_fetch($stmt0);
            mysqli_stmt_close($stmt0);

            $overlapCondition = "((start_date >= ? AND ((start_date <= ?) OR (? IS NULL))) OR
                                    (end_date >= ? AND ((end_date <= ?) OR (? IS NULL))) OR
                                    (? >= start_date AND ((? <= end_date) OR (end_date IS NULL))) OR
                                    (? >= start_date AND ((? <= end_date) OR (end_date IS NULL))))";
     
            $queryOverlapCount = "SELECT COUNT(*)
                                   FROM advise
                                   WHERE student_id = ? AND
                                         $overlapCondition AND
                                         instructor_id <> ?";
     
             $stmt1 = mysqli_prepare($myconnection, $queryOverlapCount);
             mysqli_stmt_bind_param($stmt1, "ssssssssssss",
             $sid,
             $sdate, $edate, $edate, $sdate, $edate, $edate, $sdate, $sdate, $edate, $edate,
             $iid);
             mysqli_stmt_execute($stmt1);
             mysqli_stmt_bind_result($stmt1, $overlapCount);
             mysqli_stmt_fetch($stmt1);
             mysqli_stmt_close($stmt1);

             if ($overlapCount >= 2) {
                 throw new Exception("Cannot have more than two advisors in a given date range. <br>");
             }

     
             $queryInsert = "INSERT INTO advise (instructor_id, student_id, start_date, end_date)
                             VALUES (?, ?, ?, ?)
                             ON DUPLICATE KEY UPDATE start_date = VALUES(start_date), end_date = VALUES(end_date)";

             $stmt2 = mysqli_prepare($myconnection, $queryInsert);
             mysqli_stmt_bind_param($stmt2, "ssss", $iid, $sid, $sdate, $edate);
             mysqli_stmt_execute($stmt2);
             $affectedRows = mysqli_stmt_affected_rows($stmt2);
             mysqli_stmt_close($stmt2);
             if ($affectedRows > 0) {
                echo "The appointment was successful. <br />";
             } else {
                  echo "The appointment was not sucessful. <br />";
             }
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
  </body>
</html>

