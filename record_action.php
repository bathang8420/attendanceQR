<?php

//class_action.php
include('admin/database_connection.php');

session_start();

$dow = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");

if(isset($_POST["action"])) {
    if($_POST["action"] == "single_fetch") {
        $query = "
        SELECT tbl_class.class_id, 
		concat(tbl_course.course_id,' - ',tbl_course.course_name) as course, 
		COUNT(tbl_class_student.class_id) AS total_student,
		date_created, time_from, time_to, dow, location
		FROM tbl_class_student 
		RIGHT JOIN tbl_class ON tbl_class_student.class_id = tbl_class.class_id 
		LEFT JOIN tbl_course ON tbl_class.course_id = tbl_course.course_id 
		LEFT JOIN tbl_teacher ON tbl_class.teacher_id = tbl_teacher.teacher_id 
		INNER JOIN tbl_schedule ON tbl_class.class_id = tbl_schedule.class_id
        INNER JOIN tbl_qr ON tbl_class.class_id = tbl_qr.class_id
		WHERE tbl_class.class_id = '".$_POST["class_id"]."'  AND DATE(date_created) = '".$_POST["doc"]."' 
		GROUP BY tbl_class.class_id
        ";

        $statement = $connect->prepare($query);
		if($statement->execute()) {
            if($statement->rowCount() > 0) {

                $result = $statement->fetchAll();
                $output = '';
                foreach($result as $row) {
                    $output .= '
                        <table width="100%">
                        <tr>
                            <td width="50%">
                                <p><b>Class ID:</b> '.$row["class_id"].'</p>
                                <p><b>Course:</b> '.$row["course"].'</p>
                                <p><b>Total student:</b> '.$row["total_student"].'</p>
        
                            </td>
                            <td width="50%">
                                <p><b>QR created at:</b> '.date("d/m/Y H:i:s",strtotime($row["date_created"])).'</p>
                                <p><b>Time:</b> '.$dow[$row["dow"]].'  '.date("H:i",strtotime($row["time_from"])).' - '.date("H:i",strtotime($row["time_to"])).'</p>
                                <p><b>Location:</b> '.$row["location"].'</p>
                            </td>
                        </tr>
                        </table>
                    ';
                }

                $var = $_POST["doc"];
			    $date = str_replace('/', '-', $var);
			    $eventDate = date('Y-m-d', strtotime($date));

                $query2 = "
                SELECT tbl_student.student_id, student_name, student_dob, scan_time 
                FROM tbl_student
                INNER JOIN tbl_class_student ON tbl_student.student_id = tbl_class_student.student_id
                LEFT JOIN (
                    SELECT tbl_attendance_qr.qr_id, tbl_attendance_qr.student_id, scan_time
                    FROM tbl_attendance_qr 
                    INNER JOIN tbl_qr ON tbl_qr.qr_id = tbl_attendance_qr.qr_id
                    WHERE DATE(scan_time) = '$eventDate' AND tbl_qr.class_id = 	'".$_POST["class_id"]."'
                    ) as tmp ON tmp.student_id = tbl_student.student_id
                WHERE class_id = '".$_POST["class_id"]."'
                ORDER BY tbl_student.student_id ASC
                ";
                $statement2 = $connect->prepare($query2);
                if($statement2->execute()) {
                    $result2 = $statement2->fetchAll();
                    $output .= '
                    <div class="table-responsive">
                        <table class="table table-bordered" id="student_table">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Date of Birth</th>
                                    <th>Attendance status</th>
                                </tr>
                            </thead>
                            <tbody>';
                    foreach($result2 as $row) {
                        // echo($row["scan_time"]);
                        $output .= '
                        <tr>
                        <td>'.$row["student_id"].'</td>
                        <td>'.$row["student_name"].'</td>
                        <td>'.date("d/m/Y",strtotime($row["student_dob"])).'</td>';
                        

                        if($row["scan_time"] != NULL) {
                            $output.='<td>
                            <label class="badge badge-success">Present</label>
                            <label class="badge badge-info">'.date("d/m/Y H:i:s",strtotime($row["scan_time"])).'</label>
                            </td></tr>';
                        } else {
                            $output.='<td><label class="badge badge-danger">Absent</label></td></tr>';
                        }
                        
                    }
                    $output .= '
								</tbody>
							</table>
						</div>';
                    
                }
            } else {
                $output = '<center><b><h4><i>No Result</i></h4></b></center>';   
            }
        }
        echo $output;
    }
}