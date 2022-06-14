<?php
    include_once 'header.php';
    require_once "models/Employee.php";
    require_once "models/Department.php";
    require_once "models/Unit.php";

    $employeeModel = new Employee;

    if(!isset($_POST['editId']))
    {
        $employeeid = $_SESSION['editId'];
    }
    else
    {
        $employeeid = $_POST['editId'];
    }

    $employeedetails = $employeeModel->findEmployeeById($employeeid);

    $departmentModel = new Department;
    $unitModel = new Unit;
    $departments = $departmentModel->findAllDepartments();
    $units = $unitModel->findAllUnits();
    unset($departmentModel);
    unset($unitModel);
    unset($employeeModel);

    if ($_SESSION['employeeRole'] !== "hr")
    {
        header("location: index.php");
    }
?>

<section class="taskupdater">
    <h3>Edit Employee</h3>
    <form action="controllers/Employees.ctrl.php" method="post">
        <input type="hidden" name="formtype" value="updateemployee">
        <input type="hidden" name="editId" value="<?php echo "$employeeid";?>">
        <?php
            echo "<input type='hidden' name='uid' placeholder='Employeename' value='$employeedetails->employeeUid'>";

            echo "<label for='fname'>First Name (*): </label>";
            echo "<input type='text' name='fname' placeholder='First Name' value='$employeedetails->employeeFName'>";
            echo "<br/>";

            echo "<label for='lname'>Last Name (*): </label>";
            echo "<input type='text' name='lname' placeholder='Last Name' value='$employeedetails->employeeLName'>";
            echo "<br/>";

            echo "<label for='pwd'>Password (No Change If Left Blank): </label>";
            echo "<input type='password' name='pwd' placeholder='Password'>";
            echo "<br/>";

            echo "<label for='pwdconfirm'>Repeat Password (No Change If Left Blank): </label>";
            echo "<input type='password' name='pwdconfirm' placeholder='Repeat Password'>";
            echo "<br/>";

            echo "<label for='address'>Address (*): </label>";
            echo "<input type='text' name='address' placeholder='Address' value='$employeedetails->employeeAddress'>";
            echo "<br/>";

            echo "<label for='phone'>Phone Number (*): </label>";
            echo "<input type='text' name='phone' placeholder='Phone Number' value='$employeedetails->employeePhone'>";
            echo "<br/>";

            echo "<label for='sickleaveEntitlement'>Sick Leave Entitlement (*): </label>";
            echo "<input type='number' name='sickleaveEntitlement' placeholder='Sick Leave Entitlement' value='$employeedetails->sickleaveEntitlement'>";
            echo "<br/>";

            echo "<label for='standardleaveEntitlement'>Standard Leave Entitlement (*): </label>";
            echo "<input type='number' name='standardleaveEntitlement' placeholder='Standard Leave Entitlement' value='$employeedetails->standardleaveEntitlement'>";
            echo "<br/>";

            echo "<label for='sickleaveAvailable'>Sick Leave Available (*): </label>";
            echo "<input type='number' name='sickleaveAvailable' placeholder='Sick Leave Available' value='$employeedetails->sickleaveAvailable'>";
            echo "<br/>";

            echo "<label for='standardleaveAvailable'>Standard Leave Available (*): </label>";
            echo "<input type='number' name='standardleaveAvailable' placeholder='Standard Leave Available' value='$employeedetails->standardleaveAvailable'>";
            echo "<br/>";
        ?>

        <label for="department">Department (*): </label>
        <select id="department" name="department" onclick="displayUnits()">
        <option value="">Select A Department</option>
            <?php 
                foreach($departments as $department) 
                {
                    echo '<option value="'.$department->departmentId.'">'.$department->departmentName.'</option>';
                }
                echo "<option value='$employeedetails->departmentId' selected>$employeedetails->departmentName</option>";   
            ?>
        </select>
        <br/>

        <label for="unit">Unit (*): </label>
        <select id="unit" name="unit">
            <?php echo "<option value='$employeedetails->unitId' selected>$employeedetails->unitName</option>"; ?>
        </select>
        <br/>

        <label for="employeeStatus">Status (*): </label>
        <select id="employeeStatus" name="employeeStatus">
            <option value='employed' <?php if ($employeedetails->employeeStatus == "employed"){echo "selected";}?>>Employed</option>
            <option value='oncontract' <?php if ($employeedetails->employeeStatus == "oncontract"){echo "selected";}?>>On Contract</option>
        </select>
        <br/>

        <label for="role">Role (*): </label>
        <select id="role" name="role">
            <option value='normal' <?php if ($employeedetails->employeeRole == "normal"){echo "selected";}?>>Normal Staff</option>
            <option value='hr' <?php if ($employeedetails->employeeRole == "hr"){echo "selected";}?>>Human Resources Staff</option>
            <option value='hod' <?php if ($employeedetails->employeeRole == "hod"){echo "selected";}?>>Head Of Department</option>
        </select>
        <br/><br/>

        <button class='btn btn-primary' type="submit" name="submit">Update Employee</button>
        <a class='btn btn-danger' href="manageemployees.php">Cancel</a>  
    </form>
    <br/>
    <?php
        include_once 'includes/errorhandler.inc.php'
    ?>
</section>

<script>
function displayUnits()
{
    var select = document.getElementById("unit");

    var i, L = select.options.length - 1;
    for(i = L; i >= 0; i--) {
        select.remove(i);
    }

    var e = document.getElementById("department");
    var selectedDepartmentId = e.options[e.selectedIndex].value;
    var unitIds = [<?php 
        foreach($units as $unit) 
        {
            echo "'$unit->unitId',";
        }  
    ?>];
    var unitNames = [<?php 
        foreach($units as $unit) 
        {
            echo "'$unit->unitName',";
        }  
    ?>];
    var departmentIdForUnits = [<?php 
        foreach($units as $unit) 
        {
            echo "'$unit->departmentId',";
        }  
    ?>];

    for(var i = 0; i < unitIds.length; i++) 
    {
        if(selectedDepartmentId == departmentIdForUnits[i])
        {
            var unitId = unitIds[i];
            var unitName = unitNames[i];
            var el = document.createElement("option");
            el.textContent = unitName;
            el.value = unitId;
            select.appendChild(el);
        }
    }
}

window.onload=function()
{
    displayUnits();
};

</script>

<?php
    include_once 'footer.php';
?>