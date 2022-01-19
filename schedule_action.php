<?php

//class_action.php
include('admin/database_connection.php');
include('assets/phpqrcode/qrlib.php');

session_start();

$dow = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");

if(isset($_POST["action"])) {
    if($_POST["action"] == "fetch") {
        $query ="
        SELECT tbl_class.class_id, 
		concat(tbl_course.course_id,' - ',tbl_course.course_name) as course, 
		date_from, date_to, time_from, time_to, dow, location,
		concat(tbl_class.class_id,' - ',tbl_course.course_name) as description
		FROM tbl_schedule
		INNER JOIN tbl_class ON tbl_schedule.class_id = tbl_class.class_id
		INNER JOIN tbl_course ON tbl_class.course_id = tbl_course.course_id 
		INNER JOIN tbl_teacher ON tbl_class.teacher_id = tbl_teacher.teacher_id 
		WHERE tbl_teacher.teacher_id  = '".$_SESSION["teacher_id"]."'
        ";

        $statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$data = array();
		$filtered_rows = $statement->rowCount();
        foreach($result as $row){
            $sub_array = array();
			
			$sub_array["class_id"] = $row["class_id"];
			$sub_array["course"] = $row["course"];
			$sub_array["date_from"] = $row["date_from"];
			$sub_array["date_to"] = $row["date_to"];
			$sub_array["time_from"] = $row["time_from"];
			$sub_array["time_to"] = $row["time_to"];
			$sub_array["dow"] = $row["dow"];
			$sub_array["location"] = $row["location"];
			$sub_array["description"] = $row["description"];
            $data[] = $sub_array;
        }
        // $output = array(
		// 	// "draw"				=>	intval($_POST["draw"]),
		// 	"data"				=>	$data
		// );
        echo json_encode($data);
    }

	if($_POST["action"] == "single_fetch")
	{
		$query = "
		SELECT tbl_class.class_id, 
		concat(tbl_course.course_id,' - ',tbl_course.course_name) as course, 
		tbl_teacher.teacher_name, COUNT(tbl_class_student.class_id) AS total_student,
		date_from, date_to, time_from, time_to, dow, location
		FROM tbl_class_student 
		RIGHT JOIN tbl_class ON tbl_class_student.class_id = tbl_class.class_id 
		LEFT JOIN tbl_course ON tbl_class.course_id = tbl_course.course_id 
		LEFT JOIN tbl_teacher ON tbl_class.teacher_id = tbl_teacher.teacher_id 
		INNER JOIN tbl_schedule ON tbl_class.class_id = tbl_schedule.class_id
		WHERE tbl_class.class_id = '".$_POST["class_id"]."' 
		GROUP BY tbl_class.class_id
		";
		$statement = $connect->prepare($query);
		if($statement->execute())
		{
			$result = $statement->fetchAll();
			$output = '
			<div class="row">
			';
			foreach($result as $row)
			{
				$output .= '
				<div class="col-md-6">
					<table class="table">
						<tr>
							<th>Class ID </th>
							<td>'.$row["class_id"].'</td>
						</tr>
						<tr>
							<th>Course </th>
							<td>'.$row["course"].'</td>
						</tr>
						<tr>
							<th>Teacher </th>
							<td>'.$row["teacher_name"].'</td>
						</tr>
						<tr>
							<th>Total Student </th>
							<td>'.$row["total_student"].'</td>
						</tr>
					</table>
				</div>
				<div class="col-md-6">
					<table class="table">
						<tr>
							<th>Date</th>
							<td>'.$_POST["eventDate"].'</td>
						</tr>
						<tr>
							<th>Time</th>
							<td>'.$dow[$row["dow"]].'  '.date("H:i",strtotime($row["time_from"])).' - '.date("H:i",strtotime($row["time_to"])).'</td>
						</tr>
						<tr>
							<th>Location</th>
							<td>'.$row["location"].'</td>
						</tr>
					</table>
				</div>
				';
			}
			$output .= '</div>';
			$output .= '
			<div class="panel-group">
				<div class="panel panel-default">
					<div class="panel-heading">
					<h5 class="panel-title">
						<a data-toggle="collapse" href="#collapse1">&#8250 Attendance list</a>
					</h5>
					</div>
					<div id="collapse1" class="panel-collapse collapse">
					
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
			
			$var = $_POST["eventDate"];
			$date = str_replace('/', '-', $var);
			$eventDate = date('Y-m-d', strtotime($date));
			// echo($eventDate);

			$query2 ="
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
				foreach($result2 as $row) {
					// echo($row["scan_time"]);
					$output .= '
					<tr>
					<td>'.$row["student_id"].'</td>
					<td>'.$row["student_name"].'</td>
					<td>'.date("d/m/Y",strtotime($row["student_dob"])).'</td>';

					if($row["scan_time"] != NULL) {
						$output.='<td><label class="badge badge-success">Present</label></td></tr>';
					} else {
						$output.='<td><label class="badge badge-danger">Absent</label></td></tr>';
					}

					
				}
				
			}
			$output .= '
								</tbody>
							</table>
						</div>
					
					</div>
				</div>
			</div>
			';
			echo $output;
		}
	}

	if($_POST["action"] == "qr_fetch") {
		$qr_id = md5($_POST["class_id"].''.$_POST["class_date"]);
		$class_id = $_POST["class_id"];

		$tempDir = 'temp/'.uniqid().'.png'; 
		
		$data = array(
			':qr_id'		=>	$qr_id,
			':class_id'	=>	$class_id
		);
		$query = "
		INSERT INTO tbl_qr
		(qr_id, class_id) 
		SELECT * FROM (SELECT :qr_id, :class_id) as temp 
		WHERE NOT EXISTS (
			SELECT qr_id FROM tbl_qr WHERE qr_id = :qr_id
		) LIMIT 1
		";
		$statement = $connect->prepare($query);
		if($statement->execute($data))
		{
			if($statement->rowCount() > 0)
			{
				
				QRcode::png($qr_id, $tempDir, QR_ECLEVEL_L, 15, 1);
				$output = '
				<div class="form-group text-center">
					<div class="form-group d-flex justify-content-center">
						<img src="'.$tempDir.'" alt="" id="cimg" class="img-fluid img-thumbnail">

						
					</div>

					<div d-flex justify-content-center><h3>Closes in <span id="time">01:00</span> minutes!</h3></div>
					
				</div>
				
				';
			}
			else
			{
				$output = '<h4 class="text-center">QR Already Exists</h4>';
				
			}
		}
		echo $output;



	}
}