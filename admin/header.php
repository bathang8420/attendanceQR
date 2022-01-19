<?php

//header.php

include('database_connection.php');

session_start();

if(!isset($_SESSION["admin_id"]))
{
  header('location:login.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Student Attendance System</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../css/dataTables.bootstrap4.min.css">



  <!-- <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/dataTables.bootstrap4.min.css"> -->
  <!-- <link rel="stylesheet" href="../css/bootstrap-select.min.css"> -->
  
  <!-- <link rel="stylesheet" href="../css/select2.min.css" > -->
  <script src="../js/jquery.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/jquery.dataTables.min.js"></script>
  <script src="../js/dataTables.bootstrap4.min.js"></script>
  <!-- <script type="text/javascript" src="../js/bootstrap-select.min.js"></script> -->
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />




  <script type="text/javascript" src="../js/bootstrap-datepicker.js"></script>
  <!-- <script type="text/javascript" src="../js/select2.min.js"></script> -->

  <style>
  .header {
    height: 120px;
    background-image: url(../assets/header.png);
  }

  .logo {
    height: 84px;
  }

  .nav_link {
    display: inline-block;
    min-width: 84px;
    height: 46px;
    text-decoration: none;
    color: white;
    text-align: center;
    line-height: 46px;
    text-decoration: none;
  }

  .nav_link:hover {
    color: red;
    text-decoration: none;
    background-color: #444;
  }

  .nav_link--active {
    color: red;
    font-weight: 500;
    text-decoration: none;
    background-color: #444;
  }
</style>

</head>
<body>

<div class="header jumbotron-small text-center">
  <div class="container d-flex align-items-center h-100">
    <img src="../assets/Logo_Hust.png" alt="logo-hust" class="logo px-2">
    <h2>Student Attendance System</h2>

  </div>
  
</div>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark py-0">
  <div class="container">

    <a class="nav_link" href="index.php">HOME</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse d-flex" id="collapsibleNavbar">
      <ul class="navbar-nav">
      <li class="nav-item">
          <a class="nav_link" href="course.php">COURSE</a>
        </li>
        <li class="nav-item">
          <a class="nav_link" href="class.php">CLASS</a>
        </li>
        <li class="nav-item">
          <a class="nav_link" href="teacher.php">TEACHER</a>
        </li>
        <li class="nav-item">
          <a class="nav_link" href="student.php">STUDENT</a>
        </li>
        <li class="nav-item">
          <a class="nav_link" href="report.php">REPORT</a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav_link" href="logout.php">Logout</a>
        </li>   -->
      </ul>
      <ul class="navbar-nav ml-auto">
        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i></a>
    </ul>
    </div>  
  </div>
</nav>

<!-- <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <a class="navbar-brand" href="index.php">Home</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse d-flex" id="collapsibleNavbar">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="course.php">Course</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="class.php">Class</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="teacher.php">Teacher</a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link" href="student.php">Student</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="attendance.php">Attendance</a>
      </li>
        
    </ul>
    <ul class="navbar-nav ml-auto">
        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i></a>
    </ul>
  </div>  
</nav> -->

