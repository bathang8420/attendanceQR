<?php

//class_action.php
include '../vendor/autoload.php';
include('database_connection.php');

session_start();

$dow = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");

if(isset($_POST["action"])) {
	if($_POST["action"] == "add_student") {
		$class_id = '';
		$student_id = '';
		$error_class_id = '';
		$error_student_id = '';
		$error = 0;
		if(empty($_POST["class_id"])) {
			$error_class_id = 'Choose Class';
			$error++;
		} else {
			$class_id = $_POST["class_id"];
		}
		if(empty($_POST["student_id"])) {
			$error_student_id = 'Choose Student';
			$error++;
		} else {
			$student_id = $_POST["student_id"];
		}
		if($error > 0) {
			$output = array(
				'error'							=>	true,
				'error_class_id'			=>	$error_class_id,
				'error_student_id'			=>	$error_student_id
			);
		} else {
			$data = array(
				':class_id'			=>	$class_id,
				':student_id'		=>	$student_id
			);
			$query = "
			INSERT INTO tbl_class_student
			(class_id, student_id) 
			SELECT * FROM (SELECT :class_id, :student_id) as temp 
			WHERE NOT EXISTS (
				SELECT class_id,student_id FROM tbl_class_student  WHERE class_id = :class_id AND student_id = :student_id
			) LIMIT 1
			";
			$statement = $connect->prepare($query);
			if($statement->execute($data)) {
				if($statement->rowCount() > 0)
				{
					$output = array(
						'success'		=>	'Data Added Successfully',
					);
				}
				else
				{
					$output = array(
						'error'					=>	true,
						'error_student_id'	=>	'Student Already Exists'
					);
				}
			}
		}
		echo json_encode($output);
	}

    if($_POST["action"] == "fetch") {
        $query = "
        SELECT tbl_class.class_id, 
        concat(tbl_course.course_id,' - ',tbl_course.course_name) as course, 
        tbl_teacher.teacher_name, COUNT(tbl_class_student.class_id) AS total_student 
        FROM tbl_class_student 
        RIGHT JOIN tbl_class ON tbl_class_student.class_id = tbl_class.class_id 
        LEFT JOIN tbl_course ON tbl_class.course_id = tbl_course.course_id 
        LEFT JOIN tbl_teacher ON tbl_class.teacher_id = tbl_teacher.teacher_id 
		";
		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			WHERE tbl_class.class_id LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_course.course_id LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_course.course_name LIKE "%'.$_POST["search"]["value"].'%" 
            OR tbl_teacher.teacher_name LIKE "%'.$_POST["search"]["value"].'%" 
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
			$sub_array[] = $row["teacher_name"];
            $sub_array[] = $row["total_student"];
			$sub_array[] = '
            <button type="button" name="view_class" class="btn btn-info btn-sm view_class" id="'.$row["class_id"].'"><i class="fas fa-eye"></i></button>
            <button type="button" name="edit_class" class="btn btn-primary btn-sm edit_class" id="'.$row["class_id"].'"><i class="fas fa-edit"></i></button>
            <button type="button" name="delete_class" class="btn btn-danger btn-sm delete_class" id="'.$row["class_id"].'"><i class="fas fa-trash"></i></button>
            
            ';
			// <button type="button" name="import_class" class="btn btn-primary btn-sm import_class" id="'.$row["class_id"].'">Import</button>
			// $sub_array[] = '<button type="button" name="edit_class" class="btn btn-primary btn-sm edit_class" id="'.$row["class_id"].'">Edit</button>';
			// $sub_array[] = '<button type="button" name="delete_class" class="btn btn-danger btn-sm delete_class" id="'.$row["class_id"].'">Delete</button>';
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

    if($_POST["action"] == 'Add' || $_POST["action"] == "Edit") {
        $class_id = '';
		$course_id = ''; 
		$teacher_id = '';
		$location = '';
		
		$dow = '';
		$date_from = '';
		$date_to = '';
		$time_from = '';
		$time_to = '';

		$error_class_id = '';
		$error_course_id = ''; 
		$error_teacher_id = '';
		$error_location = '';
		$error_dow = '';
		$error_date_from = '';
		$error_date_to = '';
		$error_time_from = '';
		$error_time_to = '';

		$error = 0;

		if(empty($_POST["class_id"])) {
			$error_class_id = 'Class ID is required';
			$error++;
		} else {
			$class_id = $_POST["class_id"];
		}

		if(empty($_POST["course_id"])) {
			$error_course_id = 'Select Course';
			$error++;
		} else {
			$course_id = $_POST["course_id"];
			
		}

		if(empty($_POST["teacher_id"])) {
			$error_teacher_id = 'Select Teacher';
			$error++;
		} else {
			$teacher_id = $_POST["teacher_id"];
			
		}

		if(empty($_POST["location"])) {
			$error_location = 'location is required';
			$error++;
		} else {
			$location = $_POST["location"];
		}

		if(empty($_POST["dow"])) {
			$error_dow = 'Day of week is required';
			$error++;
		} else {
			$dow = $_POST["dow"];
		}

		if(empty($_POST["date_from"])) {
			$error_date_from = 'date_from is required';
			$error++;
		} else {
			$date_from = $_POST["date_from"];
		}

		if(empty($_POST["date_to"])) {
			$error_date_to = 'date_to is required';
			$error++;
		} else {
			$date_to = $_POST["date_to"];
		}

		if(empty($_POST["time_from"])) {
			$error_time_from = 'time_from is required';
			$error++;
		} else {
			$time_from = $_POST["time_from"];
		}

		if(empty($_POST["time_to"])) {
			$error_time_to = 'time_to is required';
			$error++;
		} else {
			$time_to = $_POST["time_to"];
		}

		if($error > 0) {
			$output = array(
				'error'							=>	true,
				'error_class_id'			=>	$error_class_id,
				'error_course_id'			=>	$error_course_id,
				'error_teacher_id'			=>	$error_teacher_id,
				'error_location'		=>	$error_location,
				'error_dow'				=>	$error_dow,
				'error_date_from'			=>	$error_date_from,
				'error_date_to'			=>	$error_date_to,
				'error_time_from'			=>	$error_time_from,
				'error_time_to'			=>	$error_time_to,
				// 'error_file'			=> $error_file

			);

		} else {
			if($_POST["action"] == 'Add') {
				$data = array(
					':class_id'			=>	$class_id,
					':course_id'		=>	$course_id,
					':teacher_id'		=>	$teacher_id
				);

				$data2 = array(
					':class_id'			=>	$class_id,
					':location'		=>	$location,
					':dow'		=>	$dow,
					':date_from'		=>	$date_from,
					':date_to'		=>	$date_to,
					':time_from'		=>	$time_from,
					':time_to'		=> $time_to
				);

				$query = "
				INSERT INTO tbl_class 
				(class_id, course_id, teacher_id) 
				SELECT * FROM (SELECT :class_id, :course_id, :teacher_id) as temp 
				WHERE NOT EXISTS (
					SELECT class_id FROM tbl_class  WHERE class_id = :class_id
				) LIMIT 1
				";
				$statement = $connect->prepare($query);
				if($statement->execute($data))
				{
					if($statement->rowCount() > 0)
					{
						$query2 = "
						INSERT INTO tbl_schedule 
						(class_id, date_from, date_to, dow, time_from, time_to, location) 
						VALUES (:class_id, :date_from, :date_to, :dow, :time_from, :time_to, :location) 
						";
						$statement2 = $connect->prepare($query2);
						if($statement2->execute($data2)) {
							$output = array(
								'success'		=>	'Data Added Successfully',
							);
						}
					}
					else
					{
						$output = array(
							'error'					=>	true,
							'error_class_id'	=>	'Class Already Exists'
						);
					}
				}
			}

			if($_POST["action"] == "Edit") {
				$data = array(
					':class_id'			=>	$_POST["class_id"],
					':course_id'		=>	$course_id,
					':teacher_id'		=>	$teacher_id
				);

				$data2 = array(
					':class_id'			=>	$_POST["class_id"],
					':location'		=>	$location,
					':dow'		=>	$dow,
					':date_from'		=>	$date_from,
					':date_to'		=>	$date_to,
					':time_from'		=>	$time_from,
					':time_to'		=> $time_to
				);
				$query = "
				UPDATE tbl_class
				SET course_id = :course_id, 
				teacher_id = :teacher_id
				WHERE class_id = :class_id
				";
				$statement = $connect->prepare($query);
				if($statement->execute($data))
				{
					$query2 = "
					UPDATE tbl_schedule
					SET location = :location, 
					dow = :dow, 
					date_from = :date_from,
					date_to = :date_to,
					time_from = :time_from,
					time_to = :time_to
					WHERE class_id = :class_id
					";
					$statement2 = $connect->prepare($query2);
					if($statement2->execute($data2)) {
						$output = array(
							'success'		=>	'Data Edited Successfully',
						);
					}
					
				}

			}
		}
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

	if($_POST["action"] == "edit_fetch")
	{
		$query = "
		SELECT tbl_class.class_id, 
		tbl_course.course_id,
		tbl_teacher.teacher_id, COUNT(tbl_class_student.class_id) AS total_student,
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
			foreach($result as $row)
			{
				$output["class_id"] = $row["class_id"];
				$output["course_id"] = $row["course_id"];

				$output["teacher_id"] = $row["teacher_id"];
				$output["location"] = $row["location"];

				$output["dow"] = $row["dow"];
				$output["date_from"] = $row["date_from"];
				$output["date_to"] = $row["date_to"];
				$output["time_from"] = $row["time_from"];
				$output["time_to"] = $row["time_to"];
			}
			echo json_encode($output);
		}
	}

    if($_POST["action"] == "delete")
	{
		$query = "
		DELETE tbl_class,tbl_class_student,tbl_schedule
        FROM tbl_class
        LEFT JOIN tbl_class_student ON tbl_class.class_id = tbl_class_student.class_id
        INNER JOIN tbl_schedule ON tbl_class.class_id = tbl_schedule.class_id
        WHERE tbl_class.class_id =  '".$_POST["class_id"]."'
		";
		$statement = $connect->prepare($query);
		if($statement->execute())
		{
			echo 'Data Deleted Successfully';
		}
	}

	// if($_POST["action"] == "Import") {
	// 	$error_file = '';

	// 	$error = 0;

	// 	$import_file = $_POST["hidden_import_file"];


	// 	if($_FILES["import_excel"]["name"] != '') {
	// 		$allowed_extension = array('xls', 'xlsx');
	// 		$file_array = explode(".", $_FILES['import_excel']['name']);
	// 		$file_extension = end($file_array);
	// 		if(in_array($file_extension, $allowed_extension)) {
	// 			$file_name = time() . '.' . $file_extension;
  	// 			move_uploaded_file($_FILES['import_excel']['tmp_name'], $file_name);
	// 			$file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_name);
	// 			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);
	// 			$spreadsheet = $reader->load($file_name);

	// 			unlink($file_name);
			  
	// 			$data = $spreadsheet->getActiveSheet()->toArray();
	// 			foreach($data as $row) {
	// 				$insert_data = array(
	// 					':stu_id'		=> $row[0],
	// 					':stu_name'		=> $row[1],
	// 					':stu_dob'		=> $row[2]
	// 			);
				
	// 		}

	// 		} else {
				
	// 			$error_file = 'Only .xls or .xlsx file allowed';
	// 			$error++;
	// 		}
	// 	} else {
	// 		if($import_file = '') {
	// 			$error_file = 'Please select file';
	// 			$error++;
	// 		}
			
	// 	}

	// 	if($error > 0) {
	// 		$output = array(
	// 			'error'							=>	true,
	// 			'error_file'			=> $error_file
	// 		);

	// 	} else {
	// 		$query = "
	// 		INSERT INTO tbl_student 
	// 		(student_id, student_name, student_dob) 
	// 		VALUES (:stu_id, :stu_name, :stu_dob)
	// 		";

	// 		$statement = $connect->prepare($query);
			
	// 		if($statement->execute($insert_data)) {
	// 			$output = array(
	// 				'success'		=>	'Data Added Successfully'
	// 			);
	// 		}
	// 	}
	// 	echo json_encode($output);

	// } 
}

?>