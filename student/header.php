<?php

//header.php

include('../admin/database_connection.php');
session_start();

if(!isset($_SESSION["student_id"]))
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
  <link rel="stylesheet" href="../css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../assets/fontawesome-free/css/all.min.css">

  <link href="../assets/fullcalendar/lib/main.css" rel="stylesheet">

  <script src="../js/jquery.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/jquery.dataTables.min.js"></script>
  <script src="../js/dataTables.bootstrap4.min.js"></script>
  <script type="text/javascript" src="../assets/fullcalendar/lib/main.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- <script src='https://unpkg.com/popper.js/dist/umd/popper.min.js'></script> -->
  <!-- <script src='https://unpkg.com/tooltip.js/dist/umd/tooltip.min.js'></script> -->
  <style>
  .header {
    height: 120px;
    background-image: url(../assets/header.png);
  }

  .logo {
    height: 84px;
  }

</style>

</head>
<body>

<div class="header jumbotron-small text-center">
  <div class="container d-flex align-items-center h-100">
    <img src="../assets/Logo_Hust.png" alt="logo-hust" class="logo px-2">
    <h4>Student Attendance System</h4>

  </div>
  
</div>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <a class="navbar-brand" href="index.php">Home</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link" href="class.php">Class</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="attendance.php">Attendance</a>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link" href="profile.php">Profile</a>
      </li> -->
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Logout</a>
      </li>  
    </ul>
  </div>  
</nav>