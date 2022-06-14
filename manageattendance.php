<?php
    include_once 'header.php';
    require_once "models/Attendance.php";

    $attendanceModel = new Attendance;
    $signinentries = $attendanceModel->findAllEmployeeAttendance();
    $missingemployees = $attendanceModel->findAllAwolEmployees();
    unset($attendanceModel);
?>
<h3>Manage Attendance</h3>

<?php if(isset($_SESSION["employeeRole"]) && $_SESSION["employeeRole"] == "hr"): ?>
  <section class="missingemployees">
      <br/>
      <h4>Employees Absent Without Leave Today</h4>
      <table class="table table-bordered">
          <thead>
          <tr>
              <th>Name</th>
              <th>Department</th>
              <th>Unit</th>
              <th>Address</th>
              <th>Phone</th>
              <th>Sick Leave Available (Days)</th>
              <th>Standard Leave Available (Days)</th>
          </tr>
          </thead>
          <?php if(!empty($missingemployees)):?>
          <tbody>
              <?php
                  foreach($missingemployees as $employee) 
                  {
                      echo "<tr>";
                          $employeeFullNameAndUid = $employee->employeeFName . " " . $employee->employeeLName . " (" . $employee->employeeUid . ")";
                          echo "<td>$employeeFullNameAndUid</td>";
                          echo "<td>$employee->departmentName</td>";
                          echo "<td>$employee->unitName</td>";
                          echo "<td>$employee->employeeAddress</td>";
                          echo "<td>$employee->employeePhone</td>";
                          echo "<td>$employee->sickleaveAvailable</td>";
                          echo "<td>$employee->standardleaveAvailable</td>";
                          echo "<td>
                          <form action='controllers/Attendances.ctrl.php' method='post'>
                          <input type='hidden' name='formtype' value='markassickleave'>
                          <button class='btn btn-warning' type='submit' name='employeeId' value='$employee->employeeId'>Mark As Sick Leave</button>
                          </form>
                          </td>";
                          echo "<td>
                          <form action='controllers/Attendances.ctrl.php' method='post'>
                          <input type='hidden' name='formtype' value='markasstandardleave'>
                          <button class='btn btn-warning' type='submit' name='employeeId' value='$employee->employeeId'>Mark As Standard Leave</button>
                          </form>
                          </td>";
                      echo "</tr>";
                  }  
              ?>
          </tbody>
          <?php endif;?>
      </table>
  </section>

  <section class="presentemployees">
      <br/>
      <?php 
        if ($_SESSION['attendanceviewperiod'] == "today")
        {
          echo "<h4>Employee Sign In Times And Status For Today</h4>";
        }
        elseif ($_SESSION['attendanceviewperiod'] == "week")
        {
          echo "<h4>Employee Sign In Times And Status For The Past 7 Days</h4>";
        }
        elseif ($_SESSION['attendanceviewperiod'] == "month")
        {
          echo "<h4>Employee Sign In Times And Status For The Past 30 Days</h4>";
        }
        elseif ($_SESSION['attendanceviewperiod'] == "all")
        {
          echo "<h4>Employee Sign In Times And Status For All Time</h4>";
        }
      ?>

      <br/>
      <div>
          <input type="text" id="searchbar" onkeyup="searchFilter()" placeholder="Search Via..">
          <input type="radio" name="searchoption" value="1"> Date
          <input type="radio" name="searchoption" value="2"> Arrival Time
          <input type="radio" name="searchoption" value="3"> Name
          <input type="radio" name="searchoption" value="4"> Department
          <input type="radio" name="searchoption" value="5"> Unit
      </div>
      <br/>

      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          View Attendance For:
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <form action='controllers/Attendances.ctrl.php' method='post'>
            <input type='hidden' name='formtype' value='attendancetoday'>
            <button class="dropdown-item" type='submit'>Today</button>
          </form>
          <form action='controllers/Attendances.ctrl.php' method='post'>
            <input type='hidden' name='formtype' value='attendanceweek'>
            <button class="dropdown-item" type='submit'>Past 7 Days</button>
          </form>
          <form action='controllers/Attendances.ctrl.php' method='post'>
            <input type='hidden' name='formtype' value='attendancemonth'>
            <button class="dropdown-item" type='submit'>Past 30 Days</button>
          </form>
          <form action='controllers/Attendances.ctrl.php' method='post'>
            <input type='hidden' name='formtype' value='attendanceall'>
            <button class="dropdown-item" type='submit'>All</button>
          </form>
        </div>
      </div>

      <br/>

      <button class='btn btn-dark' onclick="showStatus('',6)">Show All Staff</button>
      <button class='btn btn-dark' onclick="showStatus('late',6)">Show Late Staff</button>
      <button class='btn btn-dark' onclick="showStatus('ontime',6)">Show On-Time Staff</button>
      <button class='btn btn-dark' onclick="showStatus('sick',6)">Show Staff On Sick Leave</button>
      <button class='btn btn-dark' onclick="showStatus('standard leave',6)">Show Staff On Standard Leave</button>

      <table class="table table-bordered" id="presenttable">
          <thead>
          <tr>
              <th>Day</th>
              <th>Date</th>
              <th>Arrival Time</th>
              <th>Name</th>
              <th>Department</th>
              <th>Unit</th>
              <th>Status</th>
          </tr>
          </thead>
          <?php if(!empty($signinentries)):?>
          <tbody>
              <?php
                  foreach($signinentries as $entry) 
                  {
                      echo "<tr>";
                          $employeeFullNameAndUid = $entry->employeeFName . " " . $entry->employeeLName . " (" . $entry->employeeUid . ")";
                          $day = date('l', strtotime($entry->signInTime));
                          $datetime = new DateTime($entry->signInTime);
                          $date = $datetime->format('Y-m-d');
                          $time = $datetime->format('H:i:s');
                          echo "<td>$day</td>";
                          echo "<td>$date</td>";
                          echo "<td>$time</td>";
                          echo "<td>$employeeFullNameAndUid</td>";
                          echo "<td>$entry->departmentName</td>";
                          echo "<td>$entry->unitName</td>";
                          $attendanceStatus = strtoupper($entry->attendanceStatus);
                          if($entry->attendanceStatus == "standard")
                          {
                              echo "<td>STANDARD LEAVE</td>";
                          }
                          else
                          {
                              echo "<td>$attendanceStatus</td>";
                          }

                      echo "</tr>";
                  }  
              ?>
          </tbody>
          <?php endif;?>
      </table>
  </section>

<?php elseif(isset($_SESSION["employeeRole"]) && $_SESSION["employeeRole"] == "hod"): ?>
  <section class="missingemployees">
      <br/>
      <h4>Employees Absent Without Leave Today</h4>
      <table class="table table-bordered">
          <thead>
          <tr>
              <th>Name</th>
              <th>Department</th>
              <th>Unit</th>
              <th>Address</th>
              <th>Phone</th>
              <th>Sick Leave Available (Days)</th>
              <th>Standard Leave Available (Days)</th>
          </tr>
          </thead>
          <?php if(!empty($missingemployees)):?>
          <tbody>
              <?php
                  foreach($missingemployees as $employee) 
                  {
                      echo "<tr>";
                          $employeeFullNameAndUid = $employee->employeeFName . " " . $employee->employeeLName . " (" . $employee->employeeUid . ")";
                          echo "<td>$employeeFullNameAndUid</td>";
                          echo "<td>$employee->departmentName</td>";
                          echo "<td>$employee->unitName</td>";
                          echo "<td>$employee->employeeAddress</td>";
                          echo "<td>$employee->employeePhone</td>";
                          echo "<td>$employee->sickleaveAvailable</td>";
                          echo "<td>$employee->standardleaveAvailable</td>";
                          echo "<td>
                          <form action='controllers/Attendances.ctrl.php' method='post'>
                          <input type='hidden' name='formtype' value='markassickleave'>
                          <button class='btn btn-warning' type='submit' name='employeeId' value='$employee->employeeId'>Mark As Sick Leave</button>
                          </form>
                          </td>";
                          echo "<td>
                          <form action='controllers/Attendances.ctrl.php' method='post'>
                          <input type='hidden' name='formtype' value='markasstandardleave'>
                          <button class='btn btn-warning' type='submit' name='employeeId' value='$employee->employeeId'>Mark As Standard Leave</button>
                          </form>
                          </td>";
                      echo "</tr>";
                  }  
              ?>
          </tbody>
          <?php endif;?>
      </table>
  </section>

  <section class="presentemployees">
      <br/>
      <?php 
        if ($_SESSION['attendanceviewperiod'] == "today")
        {
          echo "<h4>Employee Sign In Times And Status For Today</h4>";
        }
        elseif ($_SESSION['attendanceviewperiod'] == "week")
        {
          echo "<h4>Employee Sign In Times And Status For The Past 7 Days</h4>";
        }
        elseif ($_SESSION['attendanceviewperiod'] == "month")
        {
          echo "<h4>Employee Sign In Times And Status For The Past 30 Days</h4>";
        }
        elseif ($_SESSION['attendanceviewperiod'] == "all")
        {
          echo "<h4>Employee Sign In Times And Status For All Time</h4>";
        }
      ?>

      <br/>
      <div>
          <input type="text" id="searchbar" onkeyup="searchFilter()" placeholder="Search Via..">
          <input type="radio" name="searchoption" value="1"> Date
          <input type="radio" name="searchoption" value="2"> Arrival Time
          <input type="radio" name="searchoption" value="3"> Name
          <input type="radio" name="searchoption" value="4"> Department
          <input type="radio" name="searchoption" value="5"> Unit
      </div>
      <br/>

      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          View Attendance For:
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <form action='controllers/Attendances.ctrl.php' method='post'>
            <input type='hidden' name='formtype' value='attendancetoday'>
            <button class="dropdown-item" type='submit'>Today</button>
          </form>
          <form action='controllers/Attendances.ctrl.php' method='post'>
            <input type='hidden' name='formtype' value='attendanceweek'>
            <button class="dropdown-item" type='submit'>Past 7 Days</button>
          </form>
          <form action='controllers/Attendances.ctrl.php' method='post'>
            <input type='hidden' name='formtype' value='attendancemonth'>
            <button class="dropdown-item" type='submit'>Past 30 Days</button>
          </form>
          <form action='controllers/Attendances.ctrl.php' method='post'>
            <input type='hidden' name='formtype' value='attendanceall'>
            <button class="dropdown-item" type='submit'>All</button>
          </form>
        </div>
      </div>

      <br/>

      <button class='btn btn-dark' onclick="showStatus('',6)">Show All Staff</button>
      <button class='btn btn-dark' onclick="showStatus('late',6)">Show Late Staff</button>
      <button class='btn btn-dark' onclick="showStatus('ontime',6)">Show On-Time Staff</button>
      <button class='btn btn-dark' onclick="showStatus('sick',6)">Show Staff On Sick Leave</button>
      <button class='btn btn-dark' onclick="showStatus('standard leave',6)">Show Staff On Standard Leave</button>

      <table class="table table-bordered" id="presenttable">
          <thead>
          <tr>
              <th>Day</th>
              <th>Date</th>
              <th>Arrival Time</th>
              <th>Name</th>
              <th>Department</th>
              <th>Unit</th>
              <th>Status</th>
          </tr>
          </thead>
          <?php if(!empty($signinentries)):?>
          <tbody>
              <?php
                  foreach($signinentries as $entry) 
                  {
                      echo "<tr>";
                          $employeeFullNameAndUid = $entry->employeeFName . " " . $entry->employeeLName . " (" . $entry->employeeUid . ")";
                          $day = date('l', strtotime($entry->signInTime));
                          $datetime = new DateTime($entry->signInTime);
                          $date = $datetime->format('Y-m-d');
                          $time = $datetime->format('H:i:s');
                          echo "<td>$day</td>";
                          echo "<td>$date</td>";
                          echo "<td>$time</td>";
                          echo "<td>$employeeFullNameAndUid</td>";
                          echo "<td>$entry->departmentName</td>";
                          echo "<td>$entry->unitName</td>";
                          $attendanceStatus = strtoupper($entry->attendanceStatus);
                          if($entry->attendanceStatus == "standard")
                          {
                              echo "<td>STANDARD LEAVE</td>";
                          }
                          else
                          {
                              echo "<td>$attendanceStatus</td>";
                          }

                      echo "</tr>";
                  }  
              ?>
          </tbody>
          <?php endif;?>
      </table>
  </section>
<?php endif;?>

<script>
//https://www.w3schools.com/howto/howto_js_filter_table.asp
function showStatus(status,col) {
  var filter, col, table, tr, td, i, txtValue;
  filter = status.toUpperCase();
  table = document.getElementById("presenttable");
  tr = table.getElementsByTagName("tr");

  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[col];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}

function searchFilter() {
  var input, filter, table, col, tr, td, i, txtValue;
  col = document.querySelector('input[name="searchoption"]:checked').value;
  input = document.getElementById("searchbar");
  filter = input.value.toUpperCase();
  table = document.getElementById("presenttable");
  tr = table.getElementsByTagName("tr");

  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[col];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
</script>

<?php
    include_once 'footer.php';
?>