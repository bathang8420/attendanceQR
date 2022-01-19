<?php

//class_action.php
include('database_connection.php');

session_start();

$dow = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");

if(isset($_POST["action"])) {
    if($_POST["action"] == "single_fetch") {
        $var1 = $_POST["doc_from"];
        $date1 = str_replace('/', '-', $var1);
        $doc_from = date('Y-m-d', strtotime($date1));

        $var2 = $_POST["doc_to"];
        $date2 = str_replace('/', '-', $var2);
        $doc_to = date('Y-m-d', strtotime($date2));

        $total_lession = 0;


        $query = "
        SELECT t1.class_id, teacher_name,
        concat(t3.course_id,' - ',t3.course_name) as course,
        time_from, time_to, dow, location,
        COUNT(*) as total_lession
        FROM tbl_qr t1
        INNER JOIN tbl_class t2 ON t1.class_id = t2.class_id
        INNER JOIN tbl_teacher t ON t.teacher_id = t2.teacher_id
        INNER JOIN tbl_course t3 ON t2.course_id = t3.course_id
        INNER JOIN tbl_schedule t4 ON t1.class_id = t4.class_id
        WHERE t1.class_id = '".$_POST["class_id"]."' AND Date(t1.date_created) BETWEEN '$doc_from' AND '$doc_to'
        ";

        $statement = $connect->prepare($query);
		if($statement->execute()) {
            if($statement->rowCount() > 0) {

                $result = $statement->fetchAll();
                
                $output = '';
                foreach($result as $row) {
                    $total_lession  = $row["total_lession"];
                    if($total_lession == 0) {
                        $output = '<center><b><h4><i>No Result</i></h4></b></center>';   
                        echo $output;
                        return;
                    }
                    $output .= '
                        <table width="100%">
                        <tr>
                            <td width="50%">
                                <p><b>Class ID:</b> '.$row["class_id"].'</p>
                                <p><b>Course:</b> '.$row["course"].'</p>
                                <p><b>Course:</b> '.$row["teacher_name"].'</p>
                                <p><b>Total lession:</b> '.$row["total_lession"].'</p>
        
                            </td>
                            <td width="50%">
                                <p><b>Period time:</b> From '.date("d/m/Y",strtotime($_POST["doc_from"])).' to '.date("d/m/Y",strtotime($_POST["doc_to"])).'</p>
                                <p><b>Schedule:</b> Every '.$dow[$row["dow"]].'  '.date("H:i",strtotime($row["time_from"])).' - '.date("H:i",strtotime($row["time_to"])).'</p>
                                <p><b>Location:</b> '.$row["location"].'</p>
                            </td>
                        </tr>
                        </table>
                    ';
                }

                // $var = $_POST["doc"];
			    // $date = str_replace('/', '-', $var);
			    // $eventDate = date('Y-m-d', strtotime($date));

                $query2 = "
                SELECT t1.student_id, t2.student_name, present
                FROM tbl_class_student t1
                INNER JOIN tbl_student t2 ON t1.student_id = t2.student_id
                LEFT JOIN (
                    SELECT t3.student_id, COUNT(*) as present
                    FROM tbl_attendance_qr t3
                    INNER JOIN tbl_qr t4 ON t3.qr_id = t4.qr_id
                    WHERE t4.class_id = '".$_POST["class_id"]."' AND DATE(t3.scan_time) BETWEEN '$doc_from' AND '$doc_to'
                    GROUP BY t3.student_id
                ) as tmp ON t1.student_id = tmp.student_id
                WHERE t1.class_id = '".$_POST["class_id"]."'
                ORDER BY t1.student_id ASC
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
                                    <th>Present</th>
                                    <th>Absent</th>
                                </tr>
                            </thead>
                            <tbody>';
                    foreach($result2 as $row) {
                        $absent=0;
                        $present=0;
                        if($row["present"] != NULL) {
                            $present = $row["present"];
                            
                        } else {
                            $present = 0;
                            
                        }
                        $absent = $total_lession - $present;
                        $output .= '
                        <tr>
                        <td>'.$row["student_id"].'</td>
                        <td>'.$row["student_name"].'</td>
                        <td>'.$present.'</td>
                        <td>'.$absent.'</td>'
                        ;                      
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