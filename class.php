<?php
include('header.php');
?>


<div class="container" style="margin-top:30px">
  <div class="card">
  	<div class="card-header">
      <div class="row">
        <div class="col-md-9">Class List</div>
        <!-- <div class="col-md-3" align="right">
        	<button type="button" id="add_button" class="btn btn-info btn-sm">Add</button>
        </div> -->
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
  						<!-- <th>Teacher</th> -->
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
				"targets":[3],
				"orderable":false,
			},
		],
	});

  var class_id = '';
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

});
</script>