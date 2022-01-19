<?php

//database_connection.php

$cookie_name = 'siteAuth';
 
$cookie_time = (3600 * 24 * 30 * 6); // 30 days


$connect = new PDO("mysql:host=localhost;dbname=attendance","root","");

$base_url = "http://localhost/project3/";

function get_total_records($connect, $table_name)
{
	$query = "SELECT * FROM $table_name";
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount();
}

function load_course_list($connect) {
	$query = "
	SELECT course_id,
	concat(course_id,' - ',course_name) as course 
	FROM tbl_course ORDER BY course_id ASC
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$output = '';
	foreach($result as $row)
	{
		$output .= '<option value="'.$row["course_id"].'">'.$row["course"].'</option>';
	}
	return $output;
}

function load_teacher_list($connect) {
	$query = "
	SELECT teacher_id, teacher_name
	FROM tbl_teacher ORDER BY teacher_id ASC
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$output = '';
	foreach($result as $row)
	{
		$output .= '<option value="'.$row["teacher_id"].'">'.$row["teacher_name"].'</option>';
	}
	return $output;
}

function load_class_list($connect) {
	$query = "
	SELECT class_id, concat(class_id,' - ',course_name) as class_name
	FROM tbl_class
	INNER JOIN tbl_course ON tbl_class.course_id = tbl_course.course_id
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$output = '';
	foreach($result as $row)
	{
		$output .= '<option value="'.$row["class_id"].'">'.$row["class_name"].'</option>';
	}
	return $output;

}

function load_student_list($connect) {
	$query = "
	SELECT student_id, concat(student_id,' - ',student_name) as student
	FROM tbl_student
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$output = '';
	foreach($result as $row)
	{
		$output .= '<option value="'.$row["student_id"].'">'.$row["student"].'</option>';
	}
	return $output;

}

function load_class_list_by_teacher($connect,$teacher_id) {
	$query = "
	SELECT class_id, concat(class_id,' - ',course_name) as class_name
	FROM tbl_class
	INNER JOIN tbl_course ON tbl_class.course_id = tbl_course.course_id
	WHERE teacher_id = '".$teacher_id."'
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$output = '';
	foreach($result as $row)
	{
		$output .= '<option value="'.$row["class_id"].'">'.$row["class_name"].'</option>';
	}
	return $output;

}

function load_class_list_by_student($connect,$student_id) {
	$query = "
	SELECT t1.class_id, concat(t1.class_id,' - ',course_name) as class_name
	FROM tbl_class_student t1
	INNER JOIN tbl_class ON tbl_class.class_id = t1.class_id
    INNER JOIN tbl_course ON tbl_class.course_id = tbl_course.course_id
	WHERE t1.student_id = '".$student_id."'
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$output = '';
	foreach($result as $row)
	{
		$output .= '<option value="'.$row["class_id"].'">'.$row["class_name"].'</option>';
	}
	return $output;

}



// function get_attendance_percentage($connect, $student_id)
// {
// 	$query = "
// 	SELECT 
// 		ROUND((SELECT COUNT(*) FROM tbl_attendance 
// 		WHERE attendance_status = 'Present' 
// 		AND student_id = '".$student_id."') 
// 	* 100 / COUNT(*)) AS percentage FROM tbl_attendance 
// 	WHERE student_id = '".$student_id."'
// 	";

// 	$statement = $connect->prepare($query);
// 	$statement->execute();
// 	$result = $statement->fetchAll();
// 	foreach($result as $row)
// 	{
// 		if($row["percentage"] > 0)
// 		{
// 			return $row["percentage"] . '%';
// 		}
// 		else
// 		{
// 			return 'NA';
// 		}
// 	}
// }

// function Get_student_name($connect, $student_id)
// {
// 	$query = "
// 	SELECT student_name FROM tbl_student 
// 	WHERE student_id = '".$student_id."'
// 	";

// 	$statement = $connect->prepare($query);

// 	$statement->execute();

// 	$result = $statement->fetchAll();

// 	foreach($result as $row)
// 	{
// 		return $row["student_name"];
// 	}
// }

// function Get_student_grade_name($connect, $student_id)
// {
// 	$query = "
// 	SELECT tbl_grade.grade_name FROM tbl_student 
// 	INNER JOIN tbl_grade 
// 	ON tbl_grade.grade_id = tbl_student.student_grade_id 
// 	WHERE tbl_student.student_id = '".$student_id."'
// 	";
// 	$statement = $connect->prepare($query);
// 	$statement->execute();
// 	$result = $statement->fetchAll();
// 	foreach($result as $row)
// 	{
// 		return $row['grade_name'];
// 	}
// }

// function Get_student_teacher_name($connect, $student_id)
// {
// 	$query = "
// 	SELECT tbl_teacher.teacher_name 
// 	FROM tbl_student 
// 	INNER JOIN tbl_grade 
// 	ON tbl_grade.grade_id = tbl_student.student_grade_id 
// 	INNER JOIN tbl_teacher 
// 	ON tbl_teacher.teacher_grade_id = tbl_grade.grade_id 
// 	WHERE tbl_student.student_id = '".$student_id."'
// 	";
// 	$statement = $connect->prepare($query);
// 	$statement->execute();
// 	$result = $statement->fetchAll();
// 	foreach($result as $row)
// 	{
// 		return $row["teacher_name"];
// 	}
// }

// function Get_grade_name($connect, $grade_id)
// {
// 	$query = "
// 	SELECT grade_name FROM tbl_grade 
// 	WHERE grade_id = '".$grade_id."'
// 	";
// 	$statement = $connect->prepare($query);
// 	$statement->execute();
// 	$result = $statement->fetchAll();
// 	foreach($result as $row)
// 	{
// 		return $row["grade_name"];
// 	}
// }

?>