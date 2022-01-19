<?php

//login.php

include('database_connection.php');

session_start();

if(isset($_SESSION["admin_id"]))
{
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
    <h2>Student Attendance System</h2>
  </div>
  
</div>


<div class="container">
  <div class="row">
    <div class="col-md-4">

    </div>
    <div class="col-md-4" style="margin-top:20px;">
      <div class="card">
        <div class="card-header">Admin Login</div>
        <div class="card-body">
          <form method="post" id="admin_login_form">
            <div class="form-group">
              <label>Enter Username</label>
              <input type="text" name="admin_user_name" id="admin_user_name" class="form-control" />
              <span id="error_admin_user_name" class="text-danger"></span>
            </div>
            <div class="form-group">
              <label>Enter Password</label>
              <input type="password" name="admin_password" id="admin_password" class="form-control" />
              <span id="error_admin_password" class="text-danger"></span>
            </div>
            <div class="form-group text-center">
              <input type="submit" name="admin_login" id="admin_login" class="btn btn-info" value="Login" />
              <div class="pt-2">
                <a href="../index.php">Login as Teacher</a>
              </div>
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
  $('#admin_login_form').on('submit', function(event){
    event.preventDefault();
    $.ajax({
      url:"check_admin_login.php",
      method:"POST",
      data:$(this).serialize(),
      dataType:"json",
      beforeSend:function(){
        $('#admin_login').val('Validate...');
        $('#admin_login').attr('disabled', 'disabled');
      },
      success:function(data)
      {
        if(data.success)
        {
          location.href = "<?php echo $base_url; ?>admin";
        }
        if(data.error)
        {
          $('#admin_login').val('Login');
          $('#admin_login').attr('disabled', false);
          if(data.error_admin_user_name != '')
          {
            $('#error_admin_user_name').text(data.error_admin_user_name);
          }
          else
          {
            $('#error_admin_user_name').text('');
          }
          if(data.error_admin_password != '')
          {
            $('#error_admin_password').text(data.error_admin_password);
          }
          else
          {
            $('#error_admin_password').text('');
          }
        }
      }
    });
  });
});
</script>