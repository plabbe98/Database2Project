<html>
  <head>
    <title>Instructor Record</title>
  </head>
    <body>
      <h1>University Of Massachusetts Lowell<h1>
      <h2>Instructor Record</h2>
      <p>
          <?php 
                session_start();
                $email = $_SESSION['email'];
                $myconnection = mysqli_connect('localhost', 'root', '', 'db2') or die('Could not connect: ' . mysqli_connect_error());

                if (mysqli_begin_transaction($myconnection)) {
                    try {
                        $query1 = 'SELECT course_name, section.course_id, section_id, section.year, semester
                                    FROM section, course, instructor
                                    WHERE ? = email  AND
                                        instructor.instructor_id = section.instructor_id AND 
                                        section.course_id = course.course_id
                                    ORDER BY course_name ASC';

                        $stmt1 = mysqli_prepare($myconnection, $query1);
                        mysqli_stmt_bind_param($stmt1, "s", $email);
                        mysqli_stmt_execute($stmt1);
                        mysqli_stmt_bind_result($stmt1, $course_name, $course_id, $section_id, $year, $semester);
                        echo 'Course list <hr>';
                        echo '<table>';
                        echo '<tr>';
                        echo '<th>Course</th>';
                        echo '<th>Course ID</th>';
                        echo '<th>Section ID</th>';
                        echo '<th>Year</th>';
                        echo '<th>Semester</th>';
                        echo '</tr>';
                        while (mysqli_stmt_fetch($stmt1)) {
                          echo '<tr>';
                          echo '<td>' . $course_name . '</td>';
                          echo '<td>' . $course_id . '</td>';
                          echo '<td>' . $section_id . '</td>';
                          echo '<td>' . $year . '</td>';
                          echo '<td>' . $semester . '</td>';
                          echo '</tr>';
                        }
                        echo '</table>';
                        echo '<hr>';
                        echo "<br> <br> <br>";
                        mysqli_stmt_close($stmt1);

                        $query2 =   "SELECT DISTINCT student.name, take.grade, course_name, section.course_id, section.section_id, section.year, section.semester
                                     FROM take, student, section, course, instructor
                                     WHERE
                                          ? = instructor.email AND 
                                          instructor.instructor_id = section.instructor_id AND
                                          section.course_id = course.course_id AND
                                          take.student_id = student.student_id AND 
                                          take.section_id = section.section_id AND 
                                          take.course_id =  section.course_id  AND 
                                          take.semester = section.semester AND 
                                          take.year = section.year 
                                          ORDER BY student.name ASC";
                        $stmt2 = mysqli_prepare($myconnection, $query2);
                        mysqli_stmt_bind_param($stmt2, "s", $email);
                        mysqli_stmt_execute($stmt2);
                        mysqli_stmt_bind_result($stmt2, $student_name, $grade, $course_name, $course_id, $section_id, $year, $semester);

                        echo 'Student list <hr>';
                        echo '<table>';
                        echo '<tr>';
                        echo '<th>Student</th>';
                        echo '<th>Grade</th>';
                        echo '<th>Course</th>';
                        echo '<th>Course ID</th>';
                        echo '<th>Section ID</th>';
                        echo '<th>Year</th>';
                        echo '<th>Semester</th>';
                        echo '</tr>';
                        while (mysqli_stmt_fetch($stmt2)) {
                          echo '<tr>';
                          echo '<td>' . $student_name . '</td>';
                          echo '<td>' . $grade . '</td>';
                          echo '<td>' . $course_name . '</td>';
                          echo '<td>' . $course_id . '</td>';
                          echo '<td>' . $section_id . '</td>';
                          echo '<td>' . $year . '</td>';
                          echo '<td>' . $semester . '</td>';
                          echo '</tr>';
                        }
                        echo '</table>';
                        mysqli_stmt_close($stmt2);
                        mysqli_commit($myconnection) or die(mysqli_error($myconnection));
                    } catch (Exception $e) {
                        mysqli_rollback($myconnection);
                        echo 'Error: ' . $e->getMessage();
                    }
                } else {
                    echo 'Failed to start the transaction.';
                }
                mysqli_close($myconnection);
          ?>
          <hr />
      </p>
    </body>
</html>
