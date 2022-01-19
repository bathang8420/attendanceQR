<?php

//course_action.php

include('database_connection.php');

session_start();

// $output = '';

if(isset($_POST["action"]))
{
	if($_POST["action"] == "fetch")
	{
		$query = "SELECT * FROM tbl_course ";
		if(isset($_POST["search"]["value"]))
		{
			$query .= 'WHERE course_name LIKE "%'.$_POST["search"]["value"].'%"
			OR course_id LIKE "%'.$_POST["search"]["value"].'%" 
			';
			
		}
		if(isset($_POST["order"]))
		{
			$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$query .= 'ORDER BY course_id DESC ';
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
			$sub_array[] = $row["course_id"];
			$sub_array[] = $row["course_name"];
			$sub_array[] = '
			<button type="button" name="edit_course" class="btn btn-primary btn-sm edit_course" id="'.$row["course_id"].'"><i class="fas fa-edit"></i></button>
			<button type="button" name="delete_course" class="btn btn-danger btn-sm delete_course" id="'.$row["course_id"].'"><i class="fas fa-trash"></i></button>
			';
			// $sub_array[] = '<button type="button" name="delete_course" class="btn btn-danger btn-sm delete_course" id="'.$row["course_id"].'">Delete</button>';
			$data[] = $sub_array;
		}

		$output = array(
			"draw"			=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_course'),
			"data"				=>	$data
		);

		echo json_encode($output);

		
	}
	if($_POST["action"] == 'Add' || $_POST["action"] == "Edit")
	{
		$course_id = '';
		$course_name = '';
		$error_course_id = '';
		$error_course_name = '';
		$error = 0;
		
		if(empty($_POST["course_id"]))
		{
			$error_course_id = 'Course ID is required';
			$error++;
		}
		else
		{
			$course_id = $_POST["course_id"];
		}
		if(empty($_POST["course_name"]))
		{
			$error_course_name = 'Course Name is required';
			$error++;
		}
		else
		{
			$course_name = $_POST["course_name"];
		}
		if($error > 0)
		{
			$output = array(
				'error'							=>	true,
				'error_course_id'				=>	$error_course_id,
				'error_course_name'				=>	$error_course_name
			);
		}
		else
		{
			if($_POST["action"] == "Add")
			{
				$data = array(
					':course_id'				=>	$course_id,
					':course_name'				=>	$course_name
				);
				$query = "
				INSERT INTO tbl_course 
				(course_id, course_name) 
				SELECT * FROM (SELECT :course_id, :course_name) as temp 
				WHERE NOT EXISTS (
					SELECT course_id FROM tbl_course WHERE course_id = :course_id
				) LIMIT 1
				";
				$statement = $connect->prepare($query);
				if($statement->execute($data))
				{
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
							'error_course_id'		=>	'Course ID Already Exists'
						);
					}
				}
			}
			if($_POST["action"] == "Edit")
			{
				$data = array(
					':course_name'			=>	$course_name,
					':course_id'				=>	$_POST["course_id"]
				);

				$query = "
				UPDATE tbl_course 
				SET course_name = :course_name 
				WHERE course_id = :course_id
				";
				$statement = $connect->prepare($query);
				if($statement->execute($data))
				{
					$output = array(
						'success'		=>	'Data Updated Successfully',
					);
				}
			}
		}
		echo json_encode($output);
	}

	if($_POST["action"] == "edit_fetch")
	{
		
		$query = "
		SELECT * FROM tbl_course WHERE course_id = '".$_POST["course_id"]."'
		";
		$statement = $connect->prepare($query);
		if($statement->execute())
		{
			$result = $statement->fetchAll();
			
			foreach($result as $row)
			{
				$output["course_name"] = $row["course_name"];
				$output["course_id"] = $row["course_id"];
			}
			echo json_encode($output);
		}
	}

	if($_POST["action"] == "delete")
	{
		$query = "
		DELETE FROM tbl_course 
		WHERE course_id = '".$_POST["course_id"]."'
		";
		$statement = $connect->prepare($query);
		if($statement->execute())
		{
			echo 'Data Deleted Successfully';
		}
	}

	
}

?>