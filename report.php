<?php
include('header.php');
// session_start();
$teacher_id = $_SESSION["teacher_id"];
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
        <div class="col-md-9">Attendance Report</div>

      </div>
    </div>
  	<div class="card-body">
  		
    	<form action="" id="filter-frm">
			<div class="row mx-auto justify-content-center align-items-center">
			
				<label for="" class="">Class</label>
				<div class="col-md-4">
					<select name="class_id" id="class_id" class="form-control select2">
						<option disabled selected>-- Select Class --</option>
						<?php
						echo load_class_list_by_teacher($connect,$teacher_id);
						?>
					</select>
				</div>
				<label for="" class="">From date</label>
				<div class="col-md-3">
					<input type="date" name="doc_from" id="doc_from" class="form-control">
				</div>
				<label for="" class="">to date</label>
				<div class="col-md-3">
					<input type="date" name="doc_to" id="doc_to" class="form-control">
				</div>
				<div class="mt-3">
					<button class="btn  btn-primary" type="button" id="filter"><i class="fas fa-filter"></i> Filter</button>
				</div>
			</div>

			<hr/>
			
			<div class="row mt-3">
				<div class="col-md-12" id='att-list'>
					<center><b><h4><i>Please Select Class & Period Time First.</i></h4></b></center>
				</div>
				
			</div>
		</form>


		<div id="class_details" style="">
			
		</div>

		<div class="row mt-3">
			<div class="col-md-12" style="display: none" id="submit-btn-field">
				<center>
					<button class="btn btn-success btn-sm col-sm-1" type="button" id="print_att"><i class="fa fa-print"></i> Print</button>
				</center>
			</div>
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
		url:"report_action.php",
		method:"POST",
		data:{action:'single_fetch', class_id:$('#class_id').val(), doc_from:$('#doc_from').val(), doc_to:$('#doc_to').val()},
		success:function(data)
		{
		// $('#viewModal').modal('show');
		$('#att-list').html('')
		

			$('#class_details').html(data);
			$('#submit-btn-field').show();

		}

	});
	
})

$('#print_att').click(function(){
	// console.log("print")
	var _c = $('#class_details').html();
	var ns = $('noscript').clone();
	var nw = window.open('','_blank','width=900,height=600')
	nw.document.write(_c)
	nw.document.write(ns.html())
	nw.document.close()
	nw.print()
	setTimeout(() => {
		nw.close()
	}, 500);
})



</script>