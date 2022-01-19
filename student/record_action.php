<?php

//class_action.php
include('../admin/database_connection.php');

session_start();

$dow = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");

if(isset($_POST["action"])) {
    if($_POST["action"] == "single_fetch") {
        $total_lession = 0;


        $query = "
        SELECT t1.class_id,
        concat(t3.course_id,' - ',t3.course_name) as course,
        date_from, date_to, time_from, time_to, dow, location,
        COUNT(*) as total_lession
        FROM tbl_qr t1
        INNER JOIN tbl_class t2 ON t1.class_id = t2.class_id
        INNER JOIN tbl_course t3 ON t2.course_id = t3.course_id
        INNER JOIN tbl_schedule t4 ON t1.class_id = t4.class_id
        WHERE t1.class_id = '".$_POST["class_id"]."'
        ";

        $statement = $connect->prepare($query);
		if($statement->execute()) {
            if($statement->rowCount() > 0) {

                $result = $statement->fetchAll();
                $output = '';
                $total_lession=0;
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
                                <p><b>Total lession:</b> '.$row["total_lession"].'</p>
        
                            </td>
                            <td width="50%">
                                <p><b>Period:</b> From '.date("d/m/Y",strtotime($row["date_from"])).' to '.date("d/m/Y",strtotime($row["date_to"])).'</p>
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
                SELECT DATE(date_created) as lesson, scan_time
                FROM tbl_attendance_qr t1
                INNER JOIN tbl_qr t2 ON t1.qr_id = t2.qr_id
                WHERE t1.student_id = '".$_SESSION["student_id"]."' AND t2.class_id = '".$_POST["class_id"]."'
                ORDER BY lesson DESC
                ";
                $statement2 = $connect->prepare($query2);
                if($statement2->execute()) {
                    $i=1;
                    $result2 = $statement2->fetchAll();
                    $output .= '
                    <div class="table-responsive">
                        <table class="table table-bordered" id="student_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date Lession</th>
                                    <th>Attendance Time</th>
                                </tr>
                            </thead>
                            <tbody>';
                    foreach($result2 as $row) {
               
                        $output .= '
                        <tr>
                        <td>'.$i++.'</td>
                        <td>'.date("d/m/Y",strtotime($row["lesson"])).'</td>
                        <td>'.date("d/m/Y H:i:s",strtotime($row["scan_time"])).'</td></tr>';
                        

                
                        
                    }
                }
                
            } else {
                $output = '<center><b><h4><i>No Result</i></h4></b></center>';   
            }
        }
        echo $output;
    }
}