<?php

//login.php

include('../admin/database_connection.php');

session_start();

// if(isset($_SESSION["student_id"]))
// {
//   header('location:index.php');
// }
if(empty($_SESSION['student_id'])){
 
  if(isset($cookie_name)){

      if(isset($_COOKIE[$cookie_name])){

          // echo "Welcome " . $_COOKIE[$cookie_name];

          parse_str($_COOKIE[$cookie_name]);

          $query ="select * from user where username='$usr' and password='$hash'";

          $statement = $connect->prepare($query);

          $statement->execute();

          $total_row = $statement->rowCount();
          if($total_row > 0) {

            $result = $statement->fetchAll();

          }

          if($result){

              header('location:index.php');

              exit;

          }

      }

  }

} else {
  header('location:index.php');
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Student Attendance System</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <style>
  .header {
    height: 120px;
    background-image: url(../assets/header.png);
    justify-content: center;
  }

  .logo {
    height: 84px;
  }
</style>
</head>
<body>

<div class="header jumbotron-small text-center">
  <div class="container d-flex align-items-center justify-content-center h-100">
    <img src="../assets/Logo_Hust.png" alt="logo-hust" class="logo px-2">
    <h4>Student Attendance System</h4>
  </div>
  
</div>


<div class="container">
  <div class="row">
    <div class="col-md-4">

    </div>
    <div class="col-md-4" style="margin-top:20px;">
      <div class="card">
        <div class="card-header">Student Login</div>
        <div class="card-body">
          <form method="post" id="student_login_form">
            <div class="form-group">
              <label>Enter Username</label>
              <input type="text" name="student_emailid" id="student_emailid" class="form-control" />
              <span id="error_student_emailid" class="text-danger"></span>
            </div>
            <div class="form-group">
              <label>Enter Password</label>
              <input type="password" name="student_password" id="student_password" class="form-control" />
              <span id="error_student_password" class="text-danger"></span>
            </div>
            <div class="form-group text-center">
              <input type="submit" name="student_login" id="student_login" class="btn btn-info" value="Login" />
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-4">

    </div>
  </div>
</div>

</body>
</html>

<script>
$(document).ready(function(){
  $('#student_login_form').on('submit', function(event){
    event.preventDefault();
    $.ajax({
      url:"check_student_login.php",
      method:"POST",
      data:$(this).serialize(),
      dataType:"json",
      beforeSend:function(){
        $('#student_login').val('Validate...');
        $('#student_login').attr('disabled','disabled');
      },
      success:function(data)
      {
        if(data.success)
        {
          location.href="index.php";
        }
        if(data.error)
        {
          $('#student_login').val('Login');
          $('#student_login').attr('disabled', false);
          if(data.error_student_emailid != '')
          {
            $('#error_student_emailid').text(data.error_student_emailid);
          }
          else
          {
            $('#error_student_emailid').text('');
          }
          if(data.error_student_password != '')
          {
            $('#error_student_password').text(data.error_student_password);
          }
          else
          {
            $('#error_student_password').text('');
          }
        }
      }
    })
  });
});
</script>