<?php

//class_action.php
include('../admin/database_connection.php');
include('../assets/phpqrcode/qrlib.php');

session_start();

$dow = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");

if(isset($_POST["action"])) {
    if($_POST["action"] == "fetch") {
        $query ="
		SELECT tbl_class.class_id, 
		concat(tbl_course.course_id,' - ',tbl_course.course_name) as course, 
        tbl_teacher.teacher_name,
		date_from, date_to, time_from, time_to, dow, location,
		concat(tbl_class.class_id,' - ',tbl_course.course_name) as description
		FROM tbl_schedule
        INNER JOIN tbl_class_student ON tbl_schedule.class_id = tbl_class_student.class_id
		INNER JOIN tbl_class ON tbl_schedule.class_id = tbl_class.class_id
		INNER JOIN tbl_course ON tbl_class.course_id = tbl_course.course_id 
		INNER JOIN tbl_teacher ON tbl_class.teacher_id = tbl_teacher.teacher_id 
		WHERE tbl_class_student.student_id  = '".$_SESSION["student_id"]."'
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
			$sub_array["teacher"] = $row["teacher_name"];
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

	if($_POST["action"] == "single_fetch") {
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
						<a data-toggle="collapse" href="#collapse1">&#8250 Student list</a>
					</h5>
					</div>
					<div id="collapse1" class="panel-collapse collapse">
					
						<div class="table-responsive">
							<table class="table table-bordered" id="student_table">
								<thead>
									<tr>
										<th>ID</th>
										<th>Name</th>
										<th>Dob</th>
									</tr>
								</thead>
								<tbody>';
			$query2 ="
			SELECT tbl_student.student_id, student_name, student_dob 
			FROM tbl_student
			INNER JOIN tbl_class_student ON tbl_student.student_id = tbl_class_student.student_id
			WHERE class_id = '".$_POST["class_id"]."'
			ORDER BY tbl_student.student_id ASC
			";
			$statement2 = $connect->prepare($query2);
			if($statement2->execute()) {
				$result2 = $statement2->fetchAll();
				foreach($result2 as $row) {
					$output .= '
					<tr>
					<td>'.$row["student_id"].'</td>
					<td>'.$row["student_name"].'</td>
					<td>'.date("d/m/Y",strtotime($row["student_dob"])).'</td>
					</tr>
					';
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

	if($_POST["action"] == "qr_scan") {
		//Check QR Valid
		$query ="
		SELECT * FROM tbl_qr
		WHERE qr_id = '".$_POST["qr_id"]."'
		";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetch(PDO::FETCH_ASSOC);
		//QR da dc tao
		if($result)
		{
			$class_id = $result["class_id"];
			$query3 = "
			SELECT * FROM tbl_class_student
			WHERE student_id = '".$_SESSION["student_id"]."' AND class_id = '$class_id'
			";
			$statement3 = $connect->prepare($query3);
			$statement3->execute();
			$result3 = $statement3->fetch(PDO::FETCH_ASSOC);
			if(!$result3) {
				$output = array(
					'error'					=>	true,
					'status'		=>	'You are not in this class'
				);
			} else {
				$scan_time=strtotime($_POST["scan_time"]);
				$created_time=strtotime($result["date_created"]);
				// echo($scan_time-$created_time);

				//Timeout
				if (($scan_time-$created_time) > (1*60)) { 
					$output = array(
						'error'					=>	true,
						'status'		=>	'Attendance time is over'
					);
				}
				else {
					$data = array(
						':student_id'			=>	$_SESSION["student_id"],
						':qr_id'				=>	$_POST["qr_id"]
					);
		
					//Check Student-Class
					$query2 ="
					INSERT INTO tbl_attendance_qr 
					(student_id, qr_id) 
					SELECT * FROM (SELECT :student_id, :qr_id) as temp 
					WHERE NOT EXISTS (
						SELECT student_id, qr_id FROM tbl_attendance_qr WHERE student_id = :student_id AND qr_id = :qr_id
					) LIMIT 1
					";
					$statement2 = $connect->prepare($query2);
					if($statement2->execute($data)) {
						if($statement2->rowCount() > 0)
						{
							$output = array(
								'success'		=>	'Data Added Successfully',
							);
						}
						else
						{
							$output = array(
								'error'					=>	true,
								'status'		=>	'Student Already Recorded'
							);
						}
					}
				}
			}	
		}
		else //QR chua tao
		{
			$output = array(
				'error'					=>	true,
				'status'	=>	'Invalid QRCode'
			);
		}
		echo json_encode($output);
		
	}
}