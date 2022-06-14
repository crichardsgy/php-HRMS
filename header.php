<?php
  session_start();
?>

<!DOCTYPE html>

<head>
  <title>HRMS</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</head>

<!-- (Bootstrap, n.d) -->
<!-- https://getbootstrap.com/docs/4.0/components/navbar/ -->

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="collapse navbar-collapse" id="navbarSupportedContent">

    <a class="navbar-brand" href="index.php">HRMS</a>

    <ul class="navbar-nav mr-auto">
      <!-- navbar items for everyone (even logged out individuals-->
      <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="index.php") { ?>  class="active"   <?php   }  ?>><a class="nav-link" href="index.php">Home</a></li>
    
      <!-- navbar items for hr -->
      <?php if(isset($_SESSION["employeeRole"]) && $_SESSION["employeeRole"] == "hr"): ?>
          <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="myleave.php") { ?>  class="active"   <?php   }  ?>><a class="nav-link" href="myleave.php">My Leave</a></li>
          <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="myattendance.php") { ?>  class="active"   <?php   }  ?>><a class="nav-link" href="myattendance.php">My Attendance</a></li>
          <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="managedepartment.php") { ?>  class="active"   <?php   }  ?>><a class="nav-link" href="managedepartment.php">Manage Department</a></li>
          <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="manageunits.php") { ?>  class="active"   <?php   }  ?>><a class="nav-link" href="manageunits.php">Manage Units</a></li>
          <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="manageemployees.php") { ?>  class="active"   <?php   }  ?>><a class="nav-link" href="manageemployees.php">Manage Employees</a></li>
          <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="manageleaverequests.php") { ?>  class="active"   <?php   }  ?>><a class="nav-link" href="manageleaverequests.php">Manage Leave Requests</a></li>
          <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="manageattendance.php") { ?>  class="active"   <?php   }  ?>><a class="nav-link" href="manageattendance.php">Manage Attendance</a></li>
      
      <!-- navbar items for hod -->
      <?php elseif(isset($_SESSION["employeeRole"]) && $_SESSION["employeeRole"] == "hod"): ?>
          <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="myleave.php") { ?>  class="active"   <?php   }  ?>><a class="nav-link" href="myleave.php">My Leave</a></li>
          <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="myattendance.php") { ?>  class="active"   <?php   }  ?>><a class="nav-link" href="myattendance.php">My Attendance</a></li>
          <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="manageemployees.php") { ?>  class="active"   <?php   }  ?>><a class="nav-link" href="manageemployees.php">View Employees</a></li>
          <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="manageleaverequests.php") { ?>  class="active"   <?php   }  ?>><a class="nav-link" href="manageleaverequests.php">Manage Leave Requests</a></li>
          <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="manageattendance.php") { ?>  class="active"   <?php   }  ?>><a class="nav-link" href="manageattendance.php">Manage Attendance</a></li>
      
      <!-- navbar items for normal staff -->
      <?php elseif(isset($_SESSION["employeeRole"]) && $_SESSION["employeeRole"] == "normal"): ?>
          <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="myleave.php") { ?>  class="active"   <?php   }  ?>><a class="nav-link" href="myleave.php">My Leave</a></li>
          <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="myattendance.php") { ?>  class="active"   <?php   }  ?>><a class="nav-link" href="myattendance.php">My Attendance</a></li>
      <?php endif;?>

    </ul>

    <!-- log out button-->
    <ul class="nav navbar-nav navbar-right">
      <?php
        if (isset($_SESSION["employeeId"]))
        {
          if ($_SESSION['employeeRole'] == "hr")
          {
            echo '<li><a class="nav-link" href="managehrms.php">Settings</a></li>'; 
          }
          echo '<li><a class="nav-link" href="./controllers/Employees.ctrl.php?state=logout">Log Out</a></li>';
        }
      ?>
    </ul>
  </div>
</nav>
  
<br/><br/>

<div class="container p-3 my-3">