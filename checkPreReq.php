<?php
  $DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
  if(isset($_POST['course'])){
    $course_info = explode("|", $_POST['course']);
    $course_id = $course_info[0]; // Extracting course_id
    $section_id_reg = $course_info[1]; // Extracting section_id
  }
  $student_id = $_POST['student_id'];
?>

<?php

  $semester = 'Spring';
  $year = 2024;

  $myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysql_error());
  $mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');

  $query = 'SELECT numEnrolled FROM section WHERE section_id = ? AND semester = ? AND year = ?';
  $stmt = $myconnection->prepare($query);
  $stmt->bind_param("ssi", $section_id_reg, $semester, $year);
  $stmt->execute();
  $stmt->bind_result($numEnrolled); // Bind result variables
  $stmt->fetch(); // Fetch the result
  $stmt->close();

  if($numEnrolled < 15){

    $addStudent = $numEnrolled + 1;

    $query = 'UPDATE section SET numEnrolled = ? WHERE section_id = ? AND course_id = ? AND year = ? AND semester = ?';
    $stmt = $myconnection->prepare($query);
    $stmt->bind_param("issss", $addStudent, $section_id_reg, $course_id, $year, $semester);
    $stmt->execute();
    $stmt->close();

    $query = 'SELECT prereq_id FROM prereq WHERE course_id = ?';
    $stmt = $myconnection->prepare($query);
    $stmt->bind_param("s", $course_id);
    $stmt->execute();
    $stmt->bind_result($prereq_id); // Bind result variables
    $stmt->fetch(); // Fetch the result
    $stmt->close();

    if($prereq_id){
      $query = 'SELECT course_id, semester, year, section_id FROM take WHERE course_id = ? AND student_id = ?';
      $stmt = $myconnection->prepare($query);
      $stmt->bind_param("si", $prereq_id, $student_id);
      $stmt->execute();
      $stmt->bind_result($result_course_id, $semester, $year, $section_id); // Bind result variables
      $stmt->fetch(); // Fetch the result
      $stmt->close();

      if($result_course_id){
        $semester = 'Spring';
        $year = 2024;

        $query = 'INSERT INTO take (student_id, course_id, section_id, semester, year) VALUES (?, ?, ?, ?, ?)';
        $stmt = $myconnection->prepare($query);
        $stmt->bind_param("isssi", $student_id, $course_id, $section_id_reg, $semester, $year);
        $stmt->execute();
        $stmt->close();
      }
      else{
        echo '<script>alert("Student does not meet prerequisite requirement!")</script>';
        header("Refresh: 0; URL=studentmenu.php");
      }
  }
  else{
     $semester = 'Spring';
     $year = 2024;
     $query = 'INSERT INTO take (student_id, course_id, section_id, semester, year) VALUES (?, ?, ?, ?, ?)';
     $stmt = $myconnection->prepare($query);
     $stmt->bind_param("isssi", $student_id, $course_id, $section_id_reg, $semester, $year);
     $stmt->execute();
     $stmt->close();
  }
 }
 else{
    echo '<script>alert("This section is at capacity!")</script>';
    header("Refresh: 0; URL=studentmenu.php");
   }



 mysqli_close($myconnection);
?>