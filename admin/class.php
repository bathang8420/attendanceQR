<?php
include('header.php');
?>


<div class="container" style="margin-top:30px">
  <div class="card">
  	<div class="card-header">
      <div class="row">
        <div class="col-md-9">Class List</div>
        <div class="col-md-3" align="right">
        	<!-- <button type="button" id="import_button" class="btn btn-info btn-sm"><i class="fas fa-file-upload"></i> Student list</button> -->
          <button type="button" id="add_stu_class_button" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> Add Student</button>

          <button type="button" id="add_button" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> Add Class</button>
        </div>
      </div>
    </div>
  	<div class="card-body">
  		<div class="table-responsive">
        	<span id="message_operation"></span>
        	<table class="table table-striped table-bordered" id="class_table">
  				<thead>
  					<tr>
  						<th>Class ID</th>
  						<th>Class Name</th>
  						<th>Teacher</th>
  						<!-- <th>Schedule</th> -->
  						<th>Total Students</th>
  						<th>Action</th>
  					</tr>
  				</thead>
  				<tbody>

  				</tbody>
  			</table>
  			
  		</div>
  	</div>
  </div>
</div>

</body>
</html>




<link rel="stylesheet" href="../css/datepicker.css" />

<style>
    .datepicker {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
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

<form method="post" id="class_form">
<div class="modal" id="formModal">
<div class="modal-dialog modal-lg modal-dialog-centered" >
  	
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title" id="modal_title"></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="text-right">Class ID <span class="text-danger">*</span></label>
                    <div class="">
                      <input type="text" name="class_id" id="class_id" class="form-control" />
                      <span id="error_class_id" class="text-danger"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="text-right">Course <span class="text-danger">*</span></label>
                    <!-- <div class=""> -->
                      <select name="course_id" id="course_id" class="form-control select2">
                      <option disabled selected>-- Select Course --</option>
                      <?php
                			echo load_course_list($connect);
                			?>
                      </select>
                      <span id="error_course_id" class="text-danger"></span>
                    <!-- </div>  -->
                </div>

                <div class="form-group">
                    <label class="text-right">Teacher <span class="text-danger">*</span></label>
                    <div class="">
                      <select name="teacher_id" id="teacher_id" class="form-control select2">
                      <option disabled selected>-- Select Teacher --</option>
                      <?php
                			echo load_teacher_list($connect);
                			?>
                      </select>
                      <span id="error_teacher_id" class="text-danger"></span>
                    </div> 
                </div>

                

                <div class="form-group">
                    <label class="text-right">Location <span class="text-danger">*</span></label>
                    <div class="">
                      <input type="text" name="location" id="location" class="form-control" />
                      <span id="error_location" class="text-danger"></span>
                    </div>
                </div>
                
                <!-- <div class="form-group">
                    <label>Student List</label> 
                    <input type="file" name="select_excel" id="select_excel" accept=".xls,.xlsx">
                    <button type="submit" id="submit" name="import" class="btn-submit">Import</button>
                    <span id="error_file" class="text-danger"></span>
                  
                </div> -->

            </div>

            <div class="col-md-6">
              <div class="form-group for-repeating">
                
                <label class="text-right ">Days of week <span class="text-danger">*</span></label>
                <select name="dow" id="dow" class="form-control">
                  <option disabled selected>-- Select Days of week --</option>
                  <?php 
                  $dow = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
                  for($i = 0; $i < 7;$i++):
                  ?>
                  <option value="<?php echo $i ?>"><?php echo $dow[$i] ?></option>
                  <?php endfor; ?>
						    </select>
                <span id="error_dow" class="text-danger"></span>
              </div>

              <div class="form-group for-repeating">
                <label class="text-right">Date From <span class="text-danger">*</span></label>
                <div class="">
                  <input type="date" name="date_from" id="date_from" class="form-control" />
                  <span id="error_date_from" class="text-danger"></span>
                </div>
              </div>

              <div class="form-group for-repeating">
                <label class="text-right">Date To <span class="text-danger">*</span></label>
                <div class="">
                  <input type="date" name="date_to" id="date_to" class="form-control" />
                  <span id="error_date_to" class="text-danger"></span>
                </div>
              </div>

              <!-- <div class="form-group for-nonrepeating" style="display: none">
                <label class="text-right">Date Schedule <span class="text-danger">*</span></label>
                <div class="">
                  <input type="date" name="date_schedule" id="date_schedule" class="form-control" />
                  <span id="error_date_schedule" class="text-danger"></span>
                </div>
              </div> -->

              <div class="form-group">
                <label class="text-right">Time From <span class="text-danger">*</span></label>
                <div class="">
                  <input type="time" name="time_from" id="time_from" class="form-control" />
                  <span id="error_time_from" class="text-danger"></span>
                </div>
              </div>

              <div class="form-group">
                <label class="text-right">Time To <span class="text-danger">*</span></label>
                <div class="">
                  <input type="time" name="time_to" id="time_to" class="form-control" />
                  <span id="error_time_to" class="text-danger"></span>
                </div>
              </div>
  
            </div>

          </div>

        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
        	<!-- <input type="hidden" name="student_id" id="student_id" /> -->
        	<input type="hidden" name="action" id="action" value="Add" />
        	<input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="Add" />
          	<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
        </div>

      </div>
  
  </div>
</div>
</form>

<div class="modal" id="viewModal">
  <div class="modal-dialog modal-lg modal-dialog-centered">

    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Class Details</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="class_details">

      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>

    </div>

  </div>
</div>

<!-- <div class="modal" id="importFileModal">
  <div class="modal-dialog modal-dialog-centered">
    <form method="post" id="import_excel_form" enctype="multipart/form-data">
      <div class="modal-content">

        
        <div class="modal-header">
          <h4 class="modal-title">Import Student List</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

       
        <div class="modal-body" id="import_details">

          <div class="form-group">

             
              <input type="file" name="import_excel" id="import_excel" accept=".xls,.xlsx">
              
              <span id="error_file" class="text-danger"></span>
          </div>            

        </div>

        
        <div class="modal-footer">
        <input type="hidden" name="hidden_import_file" id="hidden_import_file" value="" />
          <input type="submit" name="button_import" id="button_import" class="btn btn-success btn-sm" value="Import" />
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
        </div>

      </div>
    </form>
  </div>
  
</div> -->
<form method="post" id="add_student_form">
<div class="modal" id="addStudentModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Student to Class</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="form-group">  
            <div class="row">
              <label class="col-md-4 text-right">Class<span class="text-danger">*</span></label>
              <div class="col-md-8">
                <select name="add_class_id" id="add_class_id" class="form-control select2">
                    <option disabled selected>-- Select Class --</option>
                    <?php
                    echo load_class_list($connect);
                    ?>
                </select>
                <span id="error_add_class_id" class="text-danger"></span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Student<span class="text-danger">*</span></label>
              <div class="col-md-8">
                <select name="add_stu_id" id="add_stu_id" class="form-control select2">
                      <option disabled selected>-- Select Student --</option>
                      <?php
                      echo load_student_list($connect);
                      ?>
                </select>
                <span id="error_add_stu_id" class="text-danger"></span>
              </div>
            </div>
          </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="Add" />
        <!-- <button type="button" name="ok_button" id="ok_button" class="btn btn-primary btn-sm">OK</button> -->
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
</form>

<div class="modal" id="deleteModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Delete Confirmation</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <h4 align="center">Are you sure you want to remove this?</h4>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" name="ok_button" id="ok_button" class="btn btn-primary btn-sm">OK</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<script>

$(document).ready(function(){

  
	
	var dataTable = $('#class_table').DataTable({
		"processing":true,
		"serverSide":true,
		"order":[],
		"ajax":{
			url:"class_action.php",
			method:"POST",
			data:{action:'fetch'},
		},
    "columnDefs":[
			{
				"targets":[4],
				"orderable":false,
			},
		],
	});

	// $('#date_from').datepicker({
	// 	format:"yyyy-mm-dd",
	// 	autoclose: true,
  //       container: '#formModal modal-body'
	// });

  // if($('#is_repeating').prop('checked') == true){
	// 		$('.for-repeating').show()
	// 		$('.for-nonrepeating').hide()
	// 	}else{
	// 		$('.for-repeating').hide()
	// 		$('.for-nonrepeating').show()
	// }  

  // $('#is_repeating').change(function(){
	// 	if($(this).prop('checked') == true){
	// 		$('.for-repeating').show()
	// 		$('.for-nonrepeating').hide()
	// 	}else{
	// 		$('.for-repeating').hide()
	// 		$('.for-nonrepeating').show()
	// 	}
	// })

	function clear_field()
	{
		$('#class_form')[0].reset();
		$('#add_student_form')[0].reset();

		$('#error_class_id').text('');
		$('#error_course_id').text('');
		$('#error_teacher_id').text('');
    $('#error_location').text('');
    $('#error_dow').text('');
    $('#error_date_from').text('');
    $('#error_date_to').text('');
    $('#error_time_from').text('');
    $('#error_time_to').text('');
    $('error_add_class_id').text('');
    $('error_add_stu_id').text('');

	}

	$('#add_button').click(function(){
    $('.select2').select2({
      dropdownParent: $('#formModal'),
      width:'100%'
    })

		$('#modal_title').text('Add Class');
    $('#class_id').prop("readonly", false); 
		$('#button_action').val('Add');
		$('#action').val('Add');
		clear_field();
		$('#formModal').modal('show');
    
	});

  $('#add_stu_class_button').click(function(){
    $('.select2').select2({
      dropdownParent: $('#addStudentModal'),
      width:'100%'
    })

		$('#modal_title').text('Add Student to Class');
    // $('#class_id').prop("readonly", false); 
		$('#button_action').val('Add');
		$('#action').val('Add');
		clear_field();
		$('#addStudentModal').modal('show');
    
	});

  $('#add_student_form').on('submit', function(event) {
    event.preventDefault();
    $.ajax({
      url:"class_action.php",
			method:"POST",
			data:{action:'add_student',class_id:$('#add_class_id').val(),student_id:$('#add_stu_id').val()},
			dataType:"json",
			beforeSend:function(){
				$('#button_action').val('Validate...');
				$('#button_action').attr('disabled', 'disabled');
			},
      success:function(data) {
        $('#button_action').attr('disabled', false);
				$('#button_action').val($('#action').val());
        if(data.success) {
          $('#message_operation').html('<div class="alert alert-success">'+data.success+'</div>');
					clear_field();
					$('#addStudentModal').modal('hide');
					dataTable.ajax.reload();
        }
        if(data.error) {
          if(data.error_class_id != '') {
						$('#error_add_class_id').text(data.error_class_id);
					} else {
						$('#error_add_class_id').text('');
					}
          if(data.error_student_id != '') {
						$('#error_add_stu_id').text(data.error_student_id);
					} else {
						$('#error_add_stu_id').text('');
					}

        }
      }

    })

  });

	$('#class_form').on('submit', function(event){
		event.preventDefault();
		$.ajax({
			url:"class_action.php",
			method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			beforeSend:function(){
				$('#button_action').val('Validate...');
				$('#button_action').attr('disabled', 'disabled');
			},
			success:function(data)
			{
				$('#button_action').attr('disabled', false);
				$('#button_action').val($('#action').val());
				if(data.success)
				{
					$('#message_operation').html('<div class="alert alert-success">'+data.success+'</div>');
					clear_field();
					$('#formModal').modal('hide');
					dataTable.ajax.reload();
				}
				if(data.error)
				{
					if(data.error_class_id != '') {
						$('#error_class_id').text(data.error_class_id);
					} else {
						$('#error_class_id').text('');
					}
					if(data.error_course_id != '')
					{
						$('#error_course_id').text(data.error_course_id);
					}
					else
					{
						$('#error_course_id').text('');
					}
					if(data.error_teacher_id != '')
					{
						$('#error_teacher_id').text(data.error_teacher_id);
					}
					else
					{
						$('#error_teacher_id').text('');
					}
          if(data.error_location != '') {
						$('#error_location').text(data.error_location);
					} else {
						$('#error_location').text('');
					}

          if(data.error_dow != '') {
						$('#error_dow').text(data.error_dow);
					} else {
						$('#error_dow').text('');
					}

          if(data.error_date_from != '') {
						$('#error_date_from').text(data.error_date_from);
					} else {
						$('#error_date_from').text('');
					}

          if(data.error_date_to != '') {
						$('#error_date_to').text(data.error_date_to);
					} else {
						$('#error_date_to').text('');
					}

          if(data.error_time_from != '') {
						$('#error_time_from').text(data.error_time_from);
					} else {
						$('#error_time_from').text('');
					}
          if(data.error_time_to != '') {
						$('#error_time_to').text(data.error_time_to);
					} else {
						$('#error_time_to').text('');
					}
				
				}
			}
		})
	});

  var class_id = '';
  // $('#import_excel_form').on('submit', function(event){
  //   class_id = $(this).attr('id');
  //   event.preventDefault();
  //   $.ajax({
  //     url:"class_action.php",
  //     method:"POST",
  //     data:new FormData(this),
  //     contentType:false,
  //     cache:false,
  //     processData:false,
  //     beforeSend:function(){
  //       $('#button_import').val('Importing...');
  //       $('#button_import').attr('disabled', 'disabled');
  //     },
  //     success:function(data)
  //     {
  //       $('#button_import').attr('disabled', false);
	// 			$('#button_import').val('Import');
  //       if(data.success) {
  //         $('#message_operation').html('<div class="alert alert-success">'+data.success+'</div>');
	// 				$('#import_excel_form')[0].reset();
  //         $('#error_file').text('');
	// 				$('#importFileModal').modal('hide');
	// 				dataTable.ajax.reload();
  //       }
  //       if(data.error) {

  //         if(data.error_file != '') {
	// 					$('#error_file').text(data.error_file);
	// 				} else {
	// 					$('#error_file').text('');
	// 				}

  //       }
  //     }
  //   })
  // });

  $(document).on('click', '.view_class', function(){
    class_id = $(this).attr('id');
    $.ajax({
      url:"class_action.php",
      method:"POST",
      data:{action:'single_fetch', class_id:class_id},
      success:function(data)
      {
        $('#viewModal').modal('show');
        $('#class_details').html(data);
      }
    });
  });

  $(document).on('click', '.edit_class', function(){
    class_id = $(this).attr('id');
    clear_field();
    $.ajax({
      url:"class_action.php",
      method:"POST",
      data:{action:'edit_fetch', class_id:class_id},
      dataType:"json",
      success:function(data)
      {
        
        $('#class_id').val(data.class_id);
        $('#class_id').prop("readonly", true); 
        $('#course_id').val(data.course_id);
        $('#teacher_id').val(data.teacher_id);
        $('#location').val(data.location);
        $('#dow').val(data.dow);
        $('#date_from').val(data.date_from);
        $('#date_to').val(data.date_to);
        $('#time_from').val(data.time_from);
        $('#time_to').val(data.time_to);

        $('#modal_title').text('Edit Class');
        $('#button_action').val('Edit');
        $('#action').val('Edit');
        $('.select2').select2({
          dropdownParent: $('#formModal'),
          width:'100%'
        })
        $('#formModal').modal('show');
      }
    })
  });

  $(document).on('click', '.delete_class', function(){
    class_id = $(this).attr('id');
    $('#deleteModal').modal('show');
  });

  // $(document).on('click', '.import_class', function(){
  //   $('#import_excel_form')[0].reset();
  //   $('#error_file').text('');
  //   $('#importFileModal').modal('show');
  // });






  $('#ok_button').click(function(){
    $.ajax({
      url:"class_action.php",
      method:"POST",
      data:{class_id:class_id, action:"delete"},
      success:function(data)
      {
        $('#message_operation').html('<div class="alert alert-success">'+data+'</div>');
        $('#deleteModal').modal('hide');
        dataTable.ajax.reload();
      }
    })
  });

  

});
</script>