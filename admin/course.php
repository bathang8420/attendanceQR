<?php

//course.php

include('header.php');

?>

<div class="container" style="margin-top:30px">
  <div class="card">
  	<div class="card-header">
      <div class="row">
        <div class="col-md-9">Course List</div>
        <div class="col-md-3" align="right">
          <button type="button" id="add_button" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> Add</button>
        </div>
      </div>
    </div>
  	<div class="card-body">
  		<div class="table-responsive">
        <span id="message_operation"></span>
        <table class="table table-striped table-bordered" id="course_table">
          <thead>
            <tr>
              <th>Course ID</th>
              <th>Course Name</th>
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

<!-- Add course modal -->
<div class="modal" id="formModal">
  <div class="modal-dialog">
    <form method="post" id="course_form">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title" id="modal_title"></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Course ID <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="course_id" id="course_id" class="form-control" />
                <span id="error_course_id" class="text-danger"></span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Course Name <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="course_name" id="course_name" class="form-control" />
                <span id="error_course_name" class="text-danger"></span>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <!-- <input type="hidden" name="course_id" id="course_id" /> -->
          <input type="hidden" name="action" id="action" value="Add" />

          <input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="Add" />
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>


<!-- DELETE MODAL -->
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
$(document).ready(function() {
	
  var dataTable = $('#course_table').DataTable({
    "processing":true,
    "serverSide":true,
    "order":[],
    "ajax":{
      url:"course_action.php",
      type:"POST",
      data:{action:'fetch'},
    },
    "columnDefs":[
      {
        "targets":[2],
        "orderable":false,
      },
    ],
  });

  function clear_field()
  {
    $('#course_form')[0].reset();
    $('#error_course_id').text('');
    $('#error_course_name').text('');
  }

  $('#add_button').click(function(){
    $('#modal_title').text('Add course');
    $('#course_id').prop("readonly", false); 
    $('#button_action').val('Add');
    $('#action').val('Add');
    $('#formModal').modal('show');
    clear_field();
  });


  $('#course_form').on('submit', function(event) {
    event.preventDefault();
    $.ajax({
      url:"course_action.php",
      method:"POST",
      data:$(this).serialize(),
      dataType:"json",
      beforeSend:function()
      {
        $('#button_action').attr('disabled', 'disabled');
        $('#button_action').val('Validate...');
      },
      success:function(data)
      {
        $('#button_action').attr('disabled', false);
        $('#button_action').val($('#action').val());
        if(data.success)
        {
          $('#message_operation').html('<div class="alert alert-success">'+data.success+'</div>');
          clear_field();
          dataTable.ajax.reload();
          $('#formModal').modal('hide');
        }
        if(data.error)
        {
          if(data.error_course_id != '')
          {
            $('#error_course_id').text(data.error_course_id);
          }
          else
          {
            $('#error_course_id').text('');
          }
          if(data.error_course_name != '')
          {
            $('#error_course_name').text(data.error_course_name);
          }
          else
          {
            $('#error_course_name').text('');
          }
        }
      }
    })
  });

  var course_id = '';

  // EDIT BUTTON
  $(document).on('click', '.edit_course', function() {
    
    course_id = $(this).attr('id');
    // console.log(course_id);
    clear_field();
    
    $.ajax({
      url:"course_action.php",
      method:"POST",
      data:{action:'edit_fetch', course_id:course_id},
      dataType:"json",
      success:function(data)
      {
        $('#course_name').val(data.course_name);
        $('#course_id').val(data.course_id);
        $('#course_id').prop("readonly", true); 
        $('#modal_title').text('Edit Course');
        $('#button_action').val('Edit');
        $('#action').val('Edit');
        $('#formModal').modal('show');
        // console.log(data.course_id);
      }
    })
  });


  // DELETE BUTTON
  $(document).on('click', '.delete_course', function(){
    course_id = $(this).attr('id');
    $('#deleteModal').modal('show');
  });

  $('#ok_button').click(function(){
    $.ajax({
      url:"course_action.php",
      method:"POST",
      data:{course_id:course_id, action:'delete'},
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