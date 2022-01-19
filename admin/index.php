<?php

//index.php

include('header.php');

?>

<link rel="stylesheet" href="../css/adminlte.css">

<div class="container" style="margin-top:30px">
  <div class="card">
  	<div class="card-header">
      <div class="row">
        <div class="col-md-9">Overall System Status</div>
        <div class="col-md-3" align="right">
          
        </div>
      </div>
    </div>
  	<div class="card-body">

    <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-th-list"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Course</span>
                <h3 class="info-box-number">
                  <?php echo get_total_records($connect, 'tbl_course'); ?>
                </h3>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-school"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Class</span>
                <h3 class="info-box-number"> <?php echo get_total_records($connect, 'tbl_class'); ?></h3>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chalkboard-teacher"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Teacher</span>
                <h3 class="info-box-number"><?php echo get_total_records($connect, 'tbl_teacher'); ?></h3>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Student</span>
                <h3 class="info-box-number"><?php echo get_total_records($connect, 'tbl_student'); ?></h3>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
  		


  	</div>
  </div>
</div>

</body>
</html>

<!-- <script type="text/javascript" src="../js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="../css/datepicker.css" /> -->

<style>

</style>



<script>
$(document).ready(function(){
	 


});
</script>