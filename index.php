<?php
    include_once 'header.php';
    include_once 'models/Init.php';
    require_once "models/Leave.php";
    require_once "models/Attendance.php";

    if (isset($_SESSION["employeeFullName"]))
    {
      echo "<h2>Welcome " . $_SESSION["employeeFullName"] . "</h2>";
      echo "<br/>";
      $leaveModel = new Leave;
      $attendanceModel = new Attendance;
      $leaverequestcount = $leaveModel->countPendingLeaveRequests($_SESSION['employeeId'],$_SESSION['departmentId']);
      $employeestats = $attendanceModel->findAttendanceStats($_SESSION['employeeId'],$_SESSION['departmentId']);
      $departmentstats = $attendanceModel->findAttendanceStatsByDepartments();
      $unitstats = $attendanceModel->findAttendanceStatsByUnits();
      $leaveRemaining = $leaveModel->findLeaveAvailableByEmployeeId($_SESSION["employeeId"]);
      $_SESSION["sickleaveAvailable"] = $leaveRemaining->sickleaveAvailable;
      $_SESSION["standardleaveAvailable"] = $leaveRemaining->standardleaveAvailable;

      unset($leaveModel);
      unset($attendanceModel);
    }
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<?php if(isset($_SESSION["employeeRole"]) && $_SESSION["employeeRole"] == "hr"): ?>
  <h5>Pending Leave Requests: <?php echo $leaverequestcount['hr']; ?> </h5>
  <br/>

  <h5>Overall Employee Stats For Today</h5>
  <table class="table table-bordere ">
    <thead>
    <tr>
        <th>Absent</th>
        <th>Late</th>
        <th>On Time</th>
        <th>Sick Leave</th>
        <th>Standard Leave</th>
        <th>Maternity Leave</th>
        <th>Paternity Leave</th>
    </tr>
    </thead>
    <tbody>
      <tr>
        <td><?php echo $employeestats["awolemployees"]; ?></td>
        <td><?php echo $employeestats["lateemployees"]; ?></td>
        <td><?php echo $employeestats["ontimeemployees"]; ?></td>
        <td><?php echo $employeestats["sickleaveemployees"]; ?></td>
        <td><?php echo $employeestats["standardleaveemployees"]; ?></td>
        <td><?php echo $employeestats["maternityleaveemployees"]; ?></td>
        <td><?php echo $employeestats["paternityleaveemployees"]; ?></td>
      </tr>
    </tbody>
  </table>

  <div class="container">
  <h5>Departmental Stats For Today</h5>
  <div class="row">
    <div class="col-sm">
      <div class="chart-wrapper">
          <canvas id="departmentlatestats"></canvas>
      </div>
    </div>
    <div class="col-sm">
      <div class="chart-wrapper">
          <canvas id="departmentawolstats"></canvas>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm">
      <div class="chart-wrapper">
          <canvas id="departmentsickstats"></canvas>
      </div>
    </div>
    <div class="col-sm">
      <div class="chart-wrapper">
          <canvas id="departmentstandardstats"></canvas>
      </div>
    </div>
  </div>
</div>

<br/><br/>

<div class="container">
  <h5>Unit Stats For Today</h5>
  <div class="row">
    <div class="col-sm">
      <div class="chart-wrapper">
          <canvas id="unitlatestats"></canvas>
      </div>
    </div>
    <div class="col-sm">
      <div class="chart-wrapper">
          <canvas id="unitawolstats"></canvas>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm">
      <div class="chart-wrapper">
          <canvas id="unitsickstats"></canvas>
      </div>
    </div>
    <div class="col-sm">
      <div class="chart-wrapper">
          <canvas id="unitstandardstats"></canvas>
      </div>
    </div>
  </div>
</div>

<script> 
//(w3schools, n.d.)
//https://www.w3schools.com/js/tryit.asp?filename=tryai_chartjs_bars_colors_more

//-------------------------------------------------------------------DEPARTMENTS-------------------------------------------------------------------
//---------------------------late--------------------------------------------
    var xValues = 
    [<?php 
        foreach ($departmentstats as $department)
        {
            echo '"'. $department["department"] . '", ';
        }
    ?>];
    var yValues =     
    [<?php 
        foreach ($departmentstats as $department)
        {
            echo $department["late"] . ', ';
        }
    ?>];

    new Chart("departmentlatestats", {
    type: "horizontalBar",
    data: {
        labels: xValues,
        datasets: [{
        backgroundColor: "#456bf1",
        data: yValues
        }]
    },
    options: {
        legend: {display: false},
        title: {
        display: true,
        text: "Late Employees By Department"
        }
    }
    });

</script>

<script>

    //---------------------------awol--------------------------------------------
    var xValues = 
    [<?php 
        foreach ($departmentstats as $department)
        {
            echo '"'. $department["department"] . '", ';
        }
    ?>];
    var yValues =     
    [<?php 
        foreach ($departmentstats as $department)
        {
            echo $department["awol"] . ', ';
        }
    ?>];

    new Chart("departmentawolstats", {
    type: "horizontalBar",
    data: {
        labels: xValues,
        datasets: [{
        backgroundColor: "#456bf1",
        data: yValues
        }]
    },
    options: {
        legend: {display: false},
        title: {
        display: true,
        text: "Awol Employees By Department"
        }
    }
    });

</script>

<script>
    //---------------------------sick--------------------------------------------
    var xValues = 
    [<?php 
        foreach ($departmentstats as $department)
        {
            echo '"'. $department["department"] . '", ';
        }
    ?>];
    var yValues =     
    [<?php 
        foreach ($departmentstats as $department)
        {
            echo $department["sick"] . ', ';
        }
    ?>];

    new Chart("departmentsickstats", {
    type: "horizontalBar",
    data: {
        labels: xValues,
        datasets: [{
        backgroundColor: "#456bf1",
        data: yValues
        }]
    },
    options: {
        legend: {display: false},
        title: {
        display: true,
        text: "Sick Employees By Department"
        }
    }
    });

</script>

<script>

    //---------------------------standard--------------------------------------------
    var xValues = 
    [<?php 
        foreach ($departmentstats as $department)
        {
            echo '"'. $department["department"] . '", ';
        }
    ?>];
    var yValues =     
    [<?php 
        foreach ($departmentstats as $department)
        {
            echo $department["standardleave"] . ', ';
        }
    ?>];

    new Chart("departmentstandardstats", {
    type: "horizontalBar",
    data: {
        labels: xValues,
        datasets: [{
        backgroundColor: "#456bf1",
        data: yValues
        }]
    },
    options: {
        legend: {display: false},
        title: {
        display: true,
        text: "Employees On Standard Leave By Department"
        }
    }
    });
</script>

<?php elseif(isset($_SESSION["employeeRole"]) && $_SESSION["employeeRole"] == "hod"): ?>
  <h5>Pending Leave Requests From The <?php echo $_SESSION['departmentName']?> Department: <?php echo $leaverequestcount['hod']?></h5>
  <br/>
  <h5>Overall Employee Stats For The <?php echo $_SESSION['departmentName']?> Department Today</h5>
  <table class="table table-bordere ">
    <thead>
    <tr>
        <th>Absent</th>
        <th>Late</th>
        <th>On Time</th>
        <th>Sick Leave</th>
        <th>Standard Leave</th>
        <th>Maternity Leave</th>
        <th>Paternity Leave</th>
    </tr>
    </thead>
    <tbody>
      <tr>
        <td><?php echo $employeestats["awolemployees"]; ?></td>
        <td><?php echo $employeestats["lateemployees"]; ?></td>
        <td><?php echo $employeestats["ontimeemployees"]; ?></td>
        <td><?php echo $employeestats["sickleaveemployees"]; ?></td>
        <td><?php echo $employeestats["standardleaveemployees"]; ?></td>
        <td><?php echo $employeestats["maternityleaveemployees"]; ?></td>
        <td><?php echo $employeestats["paternityleaveemployees"]; ?></td>
      </tr>
    </tbody>
  </table>
  <br/>
  <div class="container">
  <h5>Unit Stats For Today</h5>
  <div class="row">
    <div class="col-sm">
      <div class="chart-wrapper">
          <canvas id="unitlatestats"></canvas>
      </div>
    </div>
    <div class="col-sm">
      <div class="chart-wrapper">
          <canvas id="unitawolstats"></canvas>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm">
      <div class="chart-wrapper">
          <canvas id="unitsickstats"></canvas>
      </div>
    </div>
    <div class="col-sm">
      <div class="chart-wrapper">
          <canvas id="unitstandardstats"></canvas>
      </div>
    </div>
  </div>
</div>

<?php elseif(isset($_SESSION["employeeRole"]) && $_SESSION["employeeRole"] == "normal"): ?>
  <h5>Pending Leave Requests: <?php echo $leaverequestcount['normal']; ?> </h5>
  <br/>
  <?php echo "<h5>You Have ".$_SESSION["sickleaveAvailable"]." Sick Leave Days And ".$_SESSION["standardleaveAvailable"]." Standard Leave Days Remaining</h5>"?>
  <br/>
  <h5>Overall Stats For The Past 30 Days</h5>
  <table class="table table-bordere ">
    <thead>
    <tr>
        <th>Absent</th>
        <th>Late</th>
        <th>On Time</th>
        <th>Sick Leave</th>
        <th>Standard Leave</th>
        <th>Maternity Leave</th>
        <th>Paternity Leave</th>
    </tr>
    </thead>
    <tbody>
      <tr>
        <td><?php echo $employeestats["awolemployees"]; ?></td>
        <td><?php echo $employeestats["lateemployees"]; ?></td>
        <td><?php echo $employeestats["ontimeemployees"]; ?></td>
        <td><?php echo $employeestats["sickleaveemployees"]; ?></td>
        <td><?php echo $employeestats["standardleaveemployees"]; ?></td>
        <td><?php echo $employeestats["maternityleaveemployees"]; ?></td>
        <td><?php echo $employeestats["paternityleaveemployees"]; ?></td>
      </tr>
    </tbody>
  </table>

  <div class="chart-wrapper">
        <h2>Punctuality Ratio</h2>
        <canvas id="punctualityratio"></canvas>
  </div>

  <script> 
//(WebDevSHORTS, 2019)
//https://github.com/WebDevSHORTS/ChartJS-PieChart/blob/master/js/script.js
    let ctx = document.getElementById('punctualityratio').getContext('2d');
    let labels = ['On-Time', 'Late'];
    let colorHex = ['#2596BE', '#EFCA07'];

    let myChart = new Chart(ctx, {
    type: 'pie',
    data: 
    {
        datasets: 
        [{
            data: [<?php echo $employeestats["ontimeemployees"] . "," . $employeestats["lateemployees"];?>],
            backgroundColor: colorHex
        }],
        labels: labels
    },
        options: 
        {
            responsive: true,
            legend: 
            {
                position: 'bottom'
            },
            plugins: 
            {
                datalabels: 
                {
                    color: '#fff',
                    anchor: 'end',
                    align: 'start',
                    offset: -10,
                    borderWidth: 2,
                    borderColor: '#fff',
                    borderRadius: 25,
                    backgroundColor: (context) => {
                    return context.dataset.backgroundColor;
                    },
                    font: 
                    {
                        weight: 'bold',
                        size: '10'
                    },
                    formatter: (value) => {
                    return value + ' %';
                    }
                }
            }
        }
    })
</script>

<?php else: include_once 'login.php';?>
<?php endif;?>

<script>
//-------------------------------------------------------------------UNITS-------------------------------------------------------------------
//---------------------------late--------------------------------------------
    var xValues = 
    [<?php 
        foreach ($unitstats as $unit)
        {
            echo '"'. $unit["unit"] . '", ';
        }
    ?>];
    var yValues =     
    [<?php 
        foreach ($unitstats as $unit)
        {
            echo $unit["late"] . ', ';
        }
    ?>];

    new Chart("unitlatestats", {
    type: "horizontalBar",
    data: {
        labels: xValues,
        datasets: [{
        backgroundColor: "#456bf1",
        data: yValues
        }]
    },
    options: {
        legend: {display: false},
        title: {
        display: true,
        text: "Late Employees By Unit"
        }
    }
    });

</script>

<script>

    //---------------------------awol--------------------------------------------
    var xValues = 
    [<?php 
        foreach ($unitstats as $unit)
        {
            echo '"'. $unit["unit"] . '", ';
        }
    ?>];
    var yValues =     
    [<?php 
        foreach ($unitstats as $unit)
        {
            echo $unit["awol"] . ', ';
        }
    ?>];

    new Chart("unitawolstats", {
    type: "horizontalBar",
    data: {
        labels: xValues,
        datasets: [{
        backgroundColor: "#456bf1",
        data: yValues
        }]
    },
    options: {
        legend: {display: false},
        title: {
        display: true,
        text: "Awol Employees By Unit"
        }
    }
    });

</script>

<script>
    //---------------------------sick--------------------------------------------
    var xValues = 
    [<?php 
        foreach ($unitstats as $unit)
        {
            echo '"'. $unit["unit"] . '", ';
        }
    ?>];
    var yValues =     
    [<?php 
        foreach ($unitstats as $unit)
        {
            echo $unit["sick"] . ', ';
        }
    ?>];

    new Chart("unitsickstats", {
    type: "horizontalBar",
    data: {
        labels: xValues,
        datasets: [{
        backgroundColor: "#456bf1",
        data: yValues
        }]
    },
    options: {
        legend: {display: false},
        title: {
        display: true,
        text: "Sick Employees By Unit"
        }
    }
    });

</script>

<script>

    //---------------------------standard--------------------------------------------
    var xValues = 
    [<?php 
        foreach ($unitstats as $unit)
        {
            echo '"'. $unit["unit"] . '", ';
        }
    ?>];
    var yValues =     
    [<?php 
        foreach ($unitstats as $unit)
        {
            echo $unit["standardleave"] . ', ';
        }
    ?>];

    new Chart("unitstandardstats", {
    type: "horizontalBar",
    data: {
        labels: xValues,
        datasets: [{
        backgroundColor: "#456bf1",
        data: yValues
        }]
    },
    options: {
        legend: {display: false},
        title: {
        display: true,
        text: "Employees On Standard Leave By Unit"
        }
    }
    });
</script>

<?php
    include_once 'footer.php';
?>