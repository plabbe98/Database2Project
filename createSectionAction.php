<html>
  <head>
    <title>Section Creation</title>
  </head>
  <body>
    <h1>University of Massachusetts Lowell</h1>
    <h2>Section Creation</h2>
    <?php
      $monday = isset($_POST['monday']) ? $_POST['monday'] : '';
      $tuesday = isset($_POST['tuesday']) ? $_POST['tuesday'] : '';
      $wednesday = isset($_POST['wednesday']) ? $_POST['wednesday'] : '';
      $thursday = isset($_POST['thursday']) ? $_POST['thursday'] : '';
      $friday = isset($_POST['friday']) ? $_POST['friday'] : '';
      $saturday = isset($_POST['saturday']) ? $_POST['saturday'] : '';
      $sunday = isset($_POST['sunday']) ? $_POST['sunday'] : '';

      $dayOfWeek = $monday . $tuesday . 
                   $wednesday . $thursday . $friday . $saturday . $sunday;

      $instructorId = $_POST["instructor_id"];
      $startTime = $_POST["time_start"];
      $endTime = $_POST["time_end"];
      $year = $_POST["year"];
      $section = $_POST["section"];
      $course = $_POST["course_id"];
      $semester = $_POST["semester"];
      $classroomId = $_POST["classroom_id"];

      if ($startTime > $endTime) {
        echo "The starting time can not be greater than the ending time.";
        exit(1);
      }

      $myconnection = mysqli_connect('localhost', 'root', '', 'db2') or die('Could not connect: ' . mysqli_connect_error());
      if (mysqli_begin_transaction($myconnection)) {
          try {              

            //$year, $semester, $course, $section
            $queryAlreadyExists = "SELECT COUNT(*)
                                  FROM section
                                  WHERE ? = year AND 
                                        ? = semester AND 
                                        ? = course_id AND
                                        ? = section_id";

            $checkAlreadyExists = mysqli_prepare($myconnection, $queryAlreadyExists);
            mysqli_stmt_bind_param($checkAlreadyExists, "ssss", $year, $semester, $course, $section);
            mysqli_stmt_execute($checkAlreadyExists);
            mysqli_stmt_bind_result($checkAlreadyExists, $alreadyExists);
            mysqli_stmt_fetch($checkAlreadyExists);
            mysqli_stmt_close($checkAlreadyExists);

            if ($alreadyExists) {
              throw new Exception("The course and section for this year and semester already exists.");
            }

            //$dayOfWeek, $dayOfWeek, $dayOfWeek, $dayOfWeek, $dayOfWeek, $dayOfWeek, $dayOfWeek
            $checkDays = "(((? LIKE '%Mo%') = (day LIKE '%Mo%')) AND
                        ((? LIKE '%Tu%') = (day LIKE '%Tu%')) AND
                        ((? LIKE '%We%') = (day LIKE '%We%')) AND
                        ((? LIKE '%Th%') = (day LIKE '%Th%')) AND
                        ((? LIKE '%Fr%') = (day LIKE '%Fr%')) AND
                        ((? LIKE '%Sa%') = (day LIKE '%Sa%')) AND
                        ((? LIKE '%Su%') = (day LIKE '%Su%')))";

            //$dayOfWeek, $dayOfWeek, $dayOfWeek, $dayOfWeek, $dayOfWeek, $dayOfWeek, $dayOfWeek
            $checkAnyDays = "(((? LIKE '%Mo%') = (day LIKE '%Mo%')) OR
                        ((? LIKE '%Tu%') = (day LIKE '%Tu%')) OR
                        ((? LIKE '%We%') = (day LIKE '%We%')) OR
                        ((? LIKE '%Th%') = (day LIKE '%Th%')) OR
                        ((? LIKE '%Fr%') = (day LIKE '%Fr%')) OR
                        ((? LIKE '%Sa%') = (day LIKE '%Sa%')) OR
                        ((? LIKE '%Su%') = (day LIKE '%Su%')))";

          //$startTime, $startTime, $endTime, $endTime, $startTime, $endTime, $startTime, $endTime
            $overlapCondition = "((? >= start_time AND ? <= end_time) OR
                              (? >= start_time AND ? <= end_time) OR 
                              (start_time >= ? AND start_time <= ?) OR
                              (end_time >= ? AND end_time <= ?))";

            $queryClassroomOccupied = "SELECT COUNT(*)
                                     FROM section, time_slot
                                     WHERE ? = section.classroom_id AND
                                           section.time_slot_id = time_slot.time_slot_id AND 
                                           ? = year AND 
                                           ? = semester AND
                                           $checkAnyDays AND 
                                           $overlapCondition AND 
                                           ";
            $stmtClassroomOccupied = mysqli_prepare($myconnection, $queryClassroomOccupied);
            mysqli_stmt_bind_param($stmtClassroomOccupied, "sissssssssssssssss", $classroomId, $year, $semester,
            $dayOfWeek, $dayOfWeek, $dayOfWeek, $dayOfWeek, $dayOfWeek, $dayOfWeek, $dayOfWeek,
            $startTime, $startTime, $endTime, $endTime, $startTime, $endTime, $startTime, $endTime);
            mysqli_stmt_execute($stmtClassroomOccupied);
            mysqli_stmt_bind_result($stmtClassroomOccupied, $isOccupied);
            mysqli_stmt_fetch($stmtClassroomOccupied);
            mysqli_stmt_close($stmtClassroomOccupied);


            if ($isOccupied) {
              throw new Exception("The classroom is occupied at this time.");
            }

            $queryInstructorCount = "SELECT COUNT(*)
                                     FROM section
                                     WHERE ? = instructor_id AND
                                           ? = year AND
                                           ? = semester";

            $checkInstructorCount = mysqli_prepare($myconnection, $queryInstructorCount);
            mysqli_stmt_bind_param($checkInstructorCount, "sis", $instructorId, $year, $semester);
            mysqli_stmt_execute($checkInstructorCount);
            mysqli_stmt_bind_result($checkInstructorCount, $instructorCount);
            mysqli_stmt_fetch($checkInstructorCount);
            mysqli_stmt_close($checkInstructorCount);

            if ($instructorCount == 1) {
              $queryIsConsecutive = "SELECT COUNT(*)
                                     FROM section, time_slot
                                     WHERE 
                                           section.time_slot IS NOT NULL AND 
                                           ? = instructor_id AND
                                           ? = year AND
                                           ? = semester AND 
                                           section.time_slot_id = time_slot.time_slot_id AND 
                                           $checkDays AND 
                                           $overlapCondition = FALSE";

              $CheckIsConsecutive = mysqli_prepare($myconnection, $queryIsConsecutive);
              mysqli_stmt_bind_param($CheckIsConsecutive, "sissssssssssssssss",
              $instructorId, $year, $semester,
              $dayOfWeek, $dayOfWeek, $dayOfWeek, $dayOfWeek, $dayOfWeek, $dayOfWeek, $dayOfWeek,
              $startTime, $startTime, $endTime, $endTime, $startTime, $endTime, $startTime, $endTime);
              mysqli_stmt_execute($CheckIsConsecutive);
              mysqli_stmt_bind_result($CheckIsConsecutive, $isConsecutive);
              mysqli_stmt_fetch($CheckIsConsecutive);
              mysqli_stmt_close($CheckIsConsecutive);

              if (!$isConsecutive) {
                throw new Exception("The new section is not considered consecutive with 
                  the other section they are teaching for this year and semester.");
              }
              
            } else if ($instructorCount >= 2) {
              throw new Exception("The instructor can not be assigned any more courses for this year and semester.");
            }

            $queryValidTimeSlot = "SELECT COUNT(*) 
                                   FROM section
                                   WHERE ? = time_slot_id";

            $stmtValidTimeSlot = mysqli_prepare($myconnection, $queryValidTimeSlot);
            mysqli_stmt_bind_param($stmtValidTimeSlot, "s", $timeId);
            mysqli_stmt_execute($stmtValidTimeSlot);
            mysqli_stmt_bind_result($stmtValidTimeSlot, $timeSlotCount);
            mysqli_stmt_fetch($stmtValidTimeSlot);
            mysqli_stmt_close($stmtValidTimeSlot);

            if ($timeSlotCount >= 2) {
              throw new Exception("Can not add any more course sections to this start time and end time as it is required that there is a limit to how many time slots
              a section can have at any time which is two.");
            }

            $queryFindDuplicate = "SELECT time_slot_id
                                   FROM time_slot 
                                   WHERE $checkDays AND 
                                         $overlapCondition";
            $stmtFindDuplicate = mysqli_prepare($myconnection, $queryFindDuplicate);
            mysqli_stmt_bind_param($stmtFindDuplicate, "sssssssssssssss", $dayOfWeek, $dayOfWeek, $dayOfWeek, $dayOfWeek, $dayOfWeek, $dayOfWeek, $dayOfWeek,
            $startTime, $startTime, $endTime, $endTime, $startTime, $endTime, $startTime, $endTime);
            mysqli_stmt_execute($stmtFindDuplicate);
            mysqli_stmt_bind_result($stmtFindDuplicate, $timeId);
            mysqli_stmt_fetch($stmtFindDuplicate);
            mysqli_stmt_close($stmtFindDuplicate);

            if ($timeId == null) {
              $queryMaxTSValue = "SELECT MAX(SUBSTRING(time_slot_id, 3)) AS max_value 
                                  FROM time_slot WHERE time_slot_id LIKE 'TS%'";
              $stmtMaxTSValue = mysqli_prepare($myconnection, $queryMaxTSValue);
              mysqli_stmt_execute($stmtMaxTSValue);
              mysqli_stmt_bind_result($stmtMaxTSValue, $maxValue);
              mysqli_stmt_fetch($stmtMaxTSValue);
              mysqli_stmt_close($stmtMaxTSValue);
              $timeId = "TS" . ((int)$maxValue + 1);


              
              $queryInsertTime = "INSERT INTO time_slot(time_slot_id, day, start_time, end_time)
                                  VALUES (?, ?, ?, ?)";
              $stmtInsertTime = mysqli_prepare($myconnection, $queryInsertTime);
              mysqli_stmt_bind_param($stmtInsertTime, "ssss", $timeId, $dayOfWeek, $startTime, $endTime);
              mysqli_stmt_execute($stmtInsertTime);
              mysqli_stmt_close($stmtInsertTime);
            }

            $queryInsertSection = "INSERT INTO section(course_id, section_id, semester, 
                                   year, instructor_id, classroom_id, time_slot_id)
                                   VALUES(?, ?, ?, ?, ?, ?, ?)";
            $stmtInsertSection = mysqli_prepare($myconnection, $queryInsertSection);
            mysqli_stmt_bind_param($stmtInsertSection, "sssisss", $course, $section, $semester,
            $year, $instructorId, $classroomId, $timeId);
            if (mysqli_stmt_execute($stmtInsertSection)) {
              echo "The insert was successful";
            } else {
              echo "The insert was not successful";
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