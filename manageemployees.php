<?php
    include_once 'header.php';
    require_once "models/Employee.php";

    $employeeModel = new Employee;

    if ($_SESSION['employeeRole'] !== "hr" && $_SESSION['employeeRole'] !== "hod")
    {
        header("location: index.php");
    }
?>

<?php if(isset($_SESSION["employeeRole"]) && $_SESSION["employeeRole"] == "hr"): ?>
    <?php
        $employeelist = $employeeModel->findAllEmployees();
        $terminatedemployeelist = $employeeModel->findAllTerminatedEmployees();
        unset($employeeModel);
    ?>
    <section class="employeemanage">
        <h3>Manage Employees</h3>
        <br/>
        <div>
            <input type="text" id="searchbar" onkeyup="searchFilter('searchbar','employeetable','searchoption')" placeholder="Search Via..">
            <input type="radio" name="searchoption" value="0"> Name
            <input type="radio" name="searchoption" value="1"> Department
            <input type="radio" name="searchoption" value="2"> Unit
            <input type="radio" name="searchoption" value="3"> Address
            <input type="radio" name="searchoption" value="4"> System Role
        </div>
        <br/>
        <a class='btn btn-dark' href="createemployee.php">Add Employee</a>
        <table id="employeetable" class="table table-bordered">
            <thead>
            <tr>
                <th>Name</th>
                <th>Department</th>
                <th>Unit</th>
                <th>Address</th>
                <th>Phone</th>
                <th>System Role</th>
                <th>Sick Leave Available (Days)</th>
                <th>Standard Leave Available (Days)</th>
                <th>Sick Leave Entitlement (Days Per Month)</th>
                <th>Standard Leave Entitlement (Days Per Year)</th>
            </tr>
            </thead>
            <tbody>
                <?php
                    foreach($employeelist as $employee) 
                    {
                        echo "<tr>";
                            $employeeFullNameAndUid = $employee->employeeFName . " " . $employee->employeeLName . " (" . $employee->employeeUid . ")";
                            echo "<td>$employeeFullNameAndUid</td>";
                            echo "<td>$employee->departmentName</td>";
                            echo "<td>$employee->unitName</td>";
                            echo "<td>$employee->employeeAddress</td>";
                            echo "<td>$employee->employeePhone</td>";
                            echo "<td>$employee->employeeRole</td>";
                            echo "<td>$employee->sickleaveAvailable</td>";
                            echo "<td>$employee->standardleaveAvailable</td>";
                            echo "<td>$employee->sickleaveEntitlement</td>";
                            echo "<td>$employee->standardleaveEntitlement</td>";
                            echo "<td>
                            <form action='updateemployee.php' method='post'>
                            <button class='btn btn-warning' type='submit' name='editId' value='$employee->employeeId'>Edit</button>
                            </form>
                            </td>";
                            echo "<td>
                            <form action='terminateemployee.php' method='post'>
                            <button class='btn btn-danger' type='submit' name='employeeId' value='$employee->employeeId'>Terminate</button>
                            </form>
                            </td>";
                        echo "</tr>";
                    }  
                ?>
            </tbody>
        </table>
    </section>

    <section class="terminatedemployeemanage">
        <br/>
        <br/>

        <h3>Terminated Employees</h3>
        <br/>
        <div>
            <input type="text" id="terminatedsearchbar" onkeyup="searchFilter('terminatedsearchbar','terminatedemployeetable','terminatedsearchoption')" placeholder="Search Via..">
            <input type="radio" name="terminatedsearchoption" value="0"> Termination Date
            <input type="radio" name="terminatedsearchoption" value="1"> Name
            <input type="radio" name="terminatedsearchoption" value="2"> Department
            <input type="radio" name="terminatedsearchoption" value="3"> Unit
            <input type="radio" name="terminatedsearchoption" value="4"> Address
            <input type="radio" name="terminatedsearchoption" value="5"> Status

        </div>
        <br/>

        <table id="terminatedemployeetable" class="table table-bordered">
            <thead>
            <tr>
                <th>Termination Date</th>
                <th>Name</th>
                <th>Department</th>
                <th>Unit</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Termination Reason</th>
            </tr>
            </thead>
            <?php if(!empty($terminatedemployeelist)):?>
            <tbody>
                <?php
                    foreach($terminatedemployeelist as $employee) 
                    {
                        echo "<tr>";
                            $employeeFullNameAndUid = $employee->employeeFName . " " . $employee->employeeLName . " (" . $employee->employeeUid . ")";
                            echo "<td>$employee->terminationDate</td>";
                            echo "<td>$employeeFullNameAndUid</td>";
                            echo "<td>$employee->departmentName</td>";
                            echo "<td>$employee->unitName</td>";
                            echo "<td>$employee->employeeAddress</td>";
                            echo "<td>$employee->employeePhone</td>";
                            echo "<td>$employee->employeeStatus</td>";
                            echo "<td>$employee->reason</td>";
                        echo "</tr>";
                    }  
                ?>
            </tbody>
            <?php endif;?>
        </table>
    </section>

<?php elseif(isset($_SESSION["employeeRole"]) && $_SESSION["employeeRole"] == "hod"): ?>
    <?php
        $employeelist = $employeeModel->findEmployeesByDepartmentId($_SESSION['departmentId']);
        $terminatedemployeelist = $employeeModel->findTerminatedEmployeesByDepartmentId($_SESSION['departmentId']);
        unset($employeeModel);
    ?>
    <section class="employeemanage">

        <?php echo"<h3>View Employees For The ".ucfirst($_SESSION["departmentName"])." Department</h3>"; ?>
        <br/>
        <div>
            <input type="text" id="searchbar" onkeyup="searchFilter('searchbar','employeetable','searchoption')" placeholder="Search Via..">
            <input type="radio" name="searchoption" value="0"> Name
            <input type="radio" name="searchoption" value="1"> Department
            <input type="radio" name="searchoption" value="2"> Unit
            <input type="radio" name="searchoption" value="3"> Address
            <input type="radio" name="searchoption" value="4"> System Role
        </div>
        <br/>
        <table id="employeetable" class="table table-bordered">
            <thead>
            <tr>
                <th>Name</th>
                <th>Unit</th>
                <th>Address</th>
                <th>Phone</th>
                <th>System Role</th>
                <th>Sick Leave Available (Days)</th>
                <th>Standard Leave Available (Days)</th>
                <th>Sick Leave Entitlement (Days Per Month)</th>
                <th>Standard Leave Entitlement (Days Per Year)</th>
            </tr>
            </thead>
            <tbody>
                <?php
                    foreach($employeelist as $employee) 
                    {
                        echo "<tr>";
                            $employeeFullNameAndUid = $employee->employeeFName . " " . $employee->employeeLName . " (" . $employee->employeeUid . ")";
                            echo "<td>$employeeFullNameAndUid</td>";
                            echo "<td>$employee->unitName</td>";
                            echo "<td>$employee->employeeAddress</td>";
                            echo "<td>$employee->employeePhone</td>";
                            echo "<td>$employee->employeeRole</td>";
                            echo "<td>$employee->sickleaveAvailable</td>";
                            echo "<td>$employee->standardleaveAvailable</td>";
                            echo "<td>$employee->sickleaveEntitlement</td>";
                            echo "<td>$employee->standardleaveEntitlement</td>";
                        echo "</tr>";
                    }  
                ?>
            </tbody>
        </table>
    </section>

    <section class="terminatedemployeemanage">
        <br/>
        <br/>

        <h3>Terminated Employees</h3>
        <br/>
        <div>
            <input type="text" id="terminatedsearchbar" onkeyup="searchFilter('terminatedsearchbar','terminatedemployeetable','terminatedsearchoption')" placeholder="Search Via..">
            <input type="radio" name="terminatedsearchoption" value="0"> Termination Date
            <input type="radio" name="terminatedsearchoption" value="1"> Name
            <input type="radio" name="terminatedsearchoption" value="2"> Department
            <input type="radio" name="terminatedsearchoption" value="3"> Unit
            <input type="radio" name="terminatedsearchoption" value="4"> Address
            <input type="radio" name="terminatedsearchoption" value="5"> Status

        </div>
        <br/>

        <table id="terminatedemployeetable" class="table table-bordered">
            <thead>
            <tr>
                <th>Termination Date</th>
                <th>Name</th>
                <th>Department</th>
                <th>Unit</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Termination Reason</th>
            </tr>
            </thead>
            <?php if(!empty($terminatedemployeelist)):?>
            <tbody>
                <?php
                    foreach($terminatedemployeelist as $employee) 
                    {
                        echo "<tr>";
                            $employeeFullNameAndUid = $employee->employeeFName . " " . $employee->employeeLName . " (" . $employee->employeeUid . ")";
                            echo "<td>$employee->terminationDate</td>";
                            echo "<td>$employeeFullNameAndUid</td>";
                            echo "<td>$employee->departmentName</td>";
                            echo "<td>$employee->unitName</td>";
                            echo "<td>$employee->employeeAddress</td>";
                            echo "<td>$employee->employeePhone</td>";
                            echo "<td>$employee->employeeStatus</td>";
                            echo "<td>$employee->reason</td>";
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
function searchFilter(searchbarid,tableid,radioboxid) {
  var input, filter, table, col, tr, td, i, txtValue;
  col = document.querySelector('input[name="'+radioboxid+'"]:checked').value;
  input = document.getElementById(searchbarid);
  filter = input.value.toUpperCase();
  table = document.getElementById(tableid);
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