<?php
    include_once 'header.php';
    require_once "models/Attendance.php";

    $attendanceModel = new Attendance;
    $signinentries = $attendanceModel->findAttendanceByEmployeeId($_SESSION["employeeId"]);
    unset($attendanceModel);
?>

<section class="myattendance">
    <h3>My Attendance</h3>
    <br/>

    <button class='btn btn-dark' onclick="showStatus('',3)">Show All</button>
    <button class='btn btn-dark' onclick="showStatus('LATE',3)">Show Late</button>
    <button class='btn btn-dark' onclick="showStatus('ONTIME',3)">Show On-Time</button>
    <button class='btn btn-dark' onclick="showStatus('SICK',3)">Show Sick Leave</button>
    <button class='btn btn-dark' onclick="showStatus('STANDARD LEAVE',3)">Show Standard Leave</button>

    <table id="presenttable" class="table table-bordered">
        <thead>
        <tr>
            <th>Day</th>
            <th>Date</th>
            <th>Arrival Time</th>
            <th>Status</th>
        </tr>
        </thead>
        <?php if(!empty($signinentries)):?>
        <tbody>
            <?php
                foreach($signinentries as $entry) 
                {
                    echo "<tr>";
                        $day = date('l', strtotime($entry->signInTime));
                        $datetime = new DateTime($entry->signInTime);
                        $date = $datetime->format('Y-m-d');
                        $time = $datetime->format('H:i:s');
                        $attendanceStatus = strtoupper($entry->attendanceStatus);
                        echo "<td>$day</td>";
                        echo "<td>$date</td>";
                        echo "<td>$time</td>";
                        if($attendanceStatus == "STANDARD" || $attendanceStatus == "SICK" || $attendanceStatus == "MATERNITY" || $attendanceStatus == "PATERNITY")
                        {
                            echo "<td>$attendanceStatus LEAVE</td>";
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