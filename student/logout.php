<?php

//logout.php

session_start();

session_destroy();

setcookie("siteAuth", '', time() - 3600);

header('location:login.php');

?>