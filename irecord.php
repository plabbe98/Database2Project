<html>
    <head>
        <title>
            irecord
        </title>
    </head>
    <body>
        <p>
            Assigned to <hr />
            <?php
                $email = $_SESSION['email'];
                $myconnection = mysqli_connect('localhost', 'root', '')
                    or die ('Could not connect: ' . mysql_error());
                $mydb = mysqli_select_db ($myconnection, 'movie')
                    or die ('Could not select database');
                $query = 'SELECT course_name, section.course_id, section_id, section.year, semester
                            FROM section, course, instructor
                            WHERE $email = email  AND
                                instructor.instructor_id = section.instructor_id AND 
                                section.course_id = course.course_id
                            ORDER BY course_name ASC';
                $result = mysqli_query($myconnection, $query)
                    or die ('Query failed: ' . mysql_error());
                echo '<table>';
                echo '<tr>';
                echo '<td>course</td>';
                echo '<td>course_id</td>';
                echo '<td>section_id</td>';
                echo '<td>year</td>';
                echo '<td>semester</td>';
                echo '</tr>';
                echo 'Title &nbsp; &nbsp; &nbsp; Year<br>';
                while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC)) {
                    echo '<tr>';
                    echo '<td>',$row["course_name"],'</td>';
                    echo '<td>',$row["section.course_id"],'</td>';
                    echo '<td>',$row["section_id"],'</td>';
                    echo '<td>',$row["section.year"],'</td>';
                    echo '<td>',$row["semester"],'</td>';
                    echo '</tr>';
                }
                echo '</table>';
                mysqli_free_result($result);
                mysqli_close($myconnection);
            ?>
            <hr />
        </p>
        <p>
            Student list <br />
            <?php
                $email = $_SESSION['email'];
                $myconnection = mysqli_connect('localhost', 'root', '')
                    or die ('Could not connect: ' . mysql_error());
                $mydb = mysqli_select_db ($myconnection, 'movie')
                    or die ('Could not select database');
                $query = 'SELECT student.name, course_name, section.course_id, section.section_id, section.year, section.semster
                            FROM take, student, section, course
                            WHERE $email = instructor.email  AND
                                instructor.instructor_id = section.instructor_id AND 
                                section.course_id = course.course_id AND
                                take.student_id = student.student_id AND
                                take.section_id = section.section_id AND 
                                take.course_id =  section.course_id  AND 
                                take.semester = section.semester AND 
                                take.year = section.year 
                            ORDER BY student.name ASC;';
                $result = mysqli_query($myconnection, $query)
                    or die ('Query failed: ' . mysql_error());
                echo '<table>';
                echo '<tr>';
                echo '<td>student</td>';
                echo '<td>course</td>';
                echo '<td>course_id</td>';
                echo '<td>section_id</td>';
                echo '<td>year</td>';
                echo '<td>semester</td>';
                echo '</tr>';
                echo 'Title &nbsp; &nbsp; &nbsp; Year<br>';
                while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC)) {
                    echo '<tr>';
                    echo '<td>',$row["student.name"],'</td>';
                    echo '<td>',$row["course_name"],'</td>';
                    echo '<td>',$row["section.course_id"],'</td>';
                    echo '<td>',$row["section.section_id"],'</td>';
                    echo '<td>',$row["section.year"],'</td>';
                    echo '<td>',$row["section.semster"],'</td>';
                    echo '</tr>';
                }
                echo '</table>';
                mysqli_free_result($result);
                mysqli_close($myconnection);
            ?>
            <hr />
        </p>
    </body>
</html>
