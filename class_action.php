<?php

//class_action.php
include('admin/database_connection.php');

session_start();

$dow = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");

if(isset($_POST["action"])) {
    if($_POST["action"] == "fetch") {
        $query = "
        SELECT tbl_class.class_id, 
        concat(tbl_course.course_id,' - ',tbl_course.course_name) as course, 
        COUNT(tbl_class_student.class_id) AS total_student 
        FROM tbl_class_student 
        RIGHT JOIN tbl_class ON tbl_class_student.class_id = tbl_class.class_id 
        LEFT JOIN tbl_course ON tbl_class.course_id = tbl_course.course_id 
        LEFT JOIN tbl_teacher ON tbl_class.teacher_id = tbl_teacher.teacher_id 
		WHERE tbl_teacher.teacher_id = '".$_SESSION["teacher_id"]."' AND (
		";
		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			tbl_class.class_id LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_course.course_id LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_course.course_name LIKE "%'.$_POST["search"]["value"].'%")
			';
		}
        $query .= 'GROUP BY tbl_class.class_id';
		if(isset($_POST["order"]))
		{
			$query .= '
			ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].'
			';
		}
		else
		{
			$query .= '
			ORDER BY tbl_class.class_id ASC 
			';
		}
		if($_POST["length"] != -1)
		{
			$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$data = array();
		$filtered_rows = $statement->rowCount();
        foreach($result as $row)
		{
			$sub_array = array();
			
			$sub_array[] = $row["class_id"];
			$sub_array[] = $row["course"];
			// $sub_array[] = $row["teacher_name"];
            $sub_array[] = $row["total_student"];
			$sub_array[] = '
            <button type="button" name="view_class" class="btn btn-info btn-sm view_class" id="'.$row["class_id"].'">Detail <i class="fas fa-info-circle"></i></button>
            ';
			
			$data[] = $sub_array;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_class'),
			"data"				=>	$data
		);
        echo json_encode($output);
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
							<th>Period</th>
							<td>From '.date("d/m/Y",strtotime($row["date_from"])).' to '.date("d/m/Y",strtotime($row["date_to"])).'</td>
						</tr>
						<tr>
							<th>Schedule</th>
							<td>Every '.$dow[$row["dow"]].'  '.date("H:i",strtotime($row["time_from"])).' - '.date("H:i",strtotime($row["time_to"])).'</td>
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
										<th>Student ID</th>
										<th>Student Name</th>
										<th>Date of Birth</th>
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
}

?>