<?php
  $DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
  $student_id = $_POST['student_id'];
?>

<head>
  <title>UMass Lowell - Student AAR</title>
</head>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses Taken by Student</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Courses Taken by Student</h2>

<table>
    <thead>
        <tr>
            <th>Course Name</th>
            <th>Credits</th>
            <th>Semester</th>
            <th>Year</th>
            <th>Grade</th>
        </tr>
    </thead>
    <tbody>

  <?php

    $total_credits = 0;    

    $myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysql_error());
    $mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');

        $query = 'SELECT c.course_name, c.credits, t.semester, t.year, t.grade
                  FROM take t
                  INNER JOIN course c ON t.course_id = c.course_id
                  WHERE t.student_id = ?';
        $stmt = $myconnection->prepare($query);
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $stmt->bind_result($course_name, $credits, $semester, $year, $grade);
        
        $total_grade_points = 0;

        while ($stmt->fetch()) {

            if($year < 2024){
            	$total_credits += $credits;
            	$grade_points = getGradePoints($grade);            
            	$total_grade_points += ($grade_points * $credits);
	    }
            echo "<tr>";
            echo "<td>$course_name</td>";
            echo "<td>$credits</td>";
            echo "<td>$semester</td>";
            echo "<td>$year</td>";
            echo "<td>$grade</td>";
            echo "</tr>";
        }

        $stmt->close();

        $gpa = ($total_grade_points / $total_credits);

        echo "<tr>";
        echo "<td><strong>Total Credits:</strong></td>";
        echo "<td>$total_credits</td>";
        echo "<td colspan='3'></td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td><strong>GPA:</strong></td>";
        echo "<td>$gpa</td>";
        echo "<td colspan='3'></td>";
        echo "</tr>";

        function getGradePoints($grade) {
            switch ($grade) {
                case 'A+':
                    return 4.0;
                case 'A':
                    return 4.0;
                case 'A-':
                    return 3.7;
                case 'B+':
                    return 3.3;
                case 'B':
                    return 3.0;
                case 'B-':
                    return 2.7;
                case 'C+':
                    return 2.3;
                case 'C':
                    return 2.0;
                case 'C-':
                    return 1.7;
                case 'D+':
                    return 1.3;
                case 'D':
                    return 1.0;
                default:
                    return 0.0;
            }
        }

        ?>
    </tbody>
</table>

</body>
</html>
