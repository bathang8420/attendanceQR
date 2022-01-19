<?php
include('header.php');
// session_start();
$student_id = $_SESSION["student_id"];
?>

<style>
.select2-selection__rendered {
    line-height: 31px !important;
}
.select2-container .select2-selection--single {
    height: 35px !important;
}
.select2-selection__arrow {
    height: 34px !important;
}

</style>

<div class="container" style="margin-top:30px">
  <div class="card card-outline card-primary">
  	<div class="card-header">
      <div class="row">
        <div class="col-md-9">Attendance Record</div>
        <!-- <div class="col-md-3" align="right">
        	<button type="button" id="add_button" class="btn btn-info btn-sm">Add</button>
        </div> -->
      </div>
    </div>
  	<div class="card-body">
  		
    	<form action="" id="filter-frm">
			<div class="row mx-auto justify-content-center align-items-center">
			
				<label for="" class="">Class</label>
				<div class="col-md-8">
					<select name="class_id" id="class_id" class="form-control select2">
						<option disabled selected>-- Select Class --</option>
						<?php
						echo load_class_list_by_student($connect,$student_id);
						?>
					</select>
				</div>
				<!-- <label for="" class="">Date</label>
				<div class="col-md-3">
					<input type="date" name="doc" id="doc" class="form-control">
				</div> -->
				<div class="col-md-4">
					<button class="btn  btn-primary" type="button" id="filter"><i class="fas fa-filter"></i> Filter</button>
				</div>
			</div>

			<hr/>
			
			<div class="row mt-3">
				<div class="col-md-12" id='att-list'>
					<center><b><h4><i>Please Select Class First.</i></h4></b></center>
				</div>
			</div>
		</form>


		<div id="class_details" style="">
			
		</div>

  	</div>
  </div>
</div>



</body>
</html>


<script>
$(document).ready(function() {
	$('.select2').select2({
      width:'100%'
    })
});

$('#filter').click(function() {
	// start_load()
	$.ajax({
		url:"record_action.php",
		method:"POST",
		data:{action:'single_fetch', class_id:$('#class_id').val()},
		success:function(data)
		{
		// $('#viewModal').modal('show');
		$('#att-list').html('')
		
		$('#class_details').html(data);
		}

	});
});

</script>