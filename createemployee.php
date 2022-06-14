<?php
    include_once 'header.php';
    require_once "models/Department.php";
    require_once "models/Unit.php";
    $departmentModel = new Department;
    $unitModel = new Unit;
    $departments = $departmentModel->findAllDepartments();
    $units = $unitModel->findAllUnits();
    unset($departmentModel);
    unset($unitModel);
?>

<section class="createemployeeform">
    <h3>Create Employee</h3>
    <form action="controllers/Employees.ctrl.php" method="post">
        <input type="hidden" name="formtype" value="register">

        <label for="uid">Employeename (*): </label>
        <input type="text" name="uid" placeholder="Employeename">
        <br/>

        <label for="fname">First Name (*): </label>
        <input type="text" name="fname" placeholder="First Name">
        <br/>

        <label for="lname">Last Name (*): </label>
        <input type="text" name="lname" placeholder="Last Name">
        <br/>

        <label for="pwd">Password (*): </label>
        <input type="password" name="pwd" placeholder="Password">
        <br/>

        <label for="pwdconfirm">Repeat Password (*): </label>
        <input type="password" name="pwdconfirm" placeholder="Repeat Password">
        <br/>

        <label for="address">Address (*): </label>
        <input type="text" name="address" placeholder="Address">
        <br/>

        <label for="phone">Phone Number (*): </label>
        <input type="text" name="phone" placeholder="Phone Number">
        <br/>

        <label for="sickleaveEntitlement">Sick Leave Entitlement (*): </label>
        <input type="number" name="sickleaveEntitlement" placeholder="Sick Leave Entitlement">
        <br/>
        
        <label for="standardleaveEntitlement">Standard Leave Entitlement (*): </label>
        <input type="number" name="standardleaveEntitlement" placeholder="Standard Leave Entitlement">
        <br/>

        <label for="department">Department (*): </label>
        <select id="department" name="department" onclick="displayUnits()">
        <option value="">Select A Department</option>
            <?php 
                foreach($departments as $department) 
                {
                    echo '<option value="'.$department->departmentId.'">'.$department->departmentName.'</option>';
                }          
            ?>
        </select>
        <br/>

        <label for="unit">Unit (*): </label>
        <select id="unit" name="unit">
            <option value="" selected>Select A Department Before Selecting A Unit</option>
        </select>
        <br/>

        <label for="employeeStatus">Status (*): </label>
        <select id="employeeStatus" name="employeeStatus">
            <option value='employed'>Employed</option>
            <option value='oncontract'>On Contract</option>
        </select>
        <br/>
        
        <label for="role">Role (*): </label>
        <select id="role" name="role">
            <option value="normal">Normal Staff</option>
            <option value="hod">Head Of Department</option>
            <option value="hr">Human Resources Staff</option>
        </select>
        <br/><br/>

        <button class='btn btn-primary' type="submit" name="submit">Create Employee</button> 
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
</script>

<?php
    include_once 'footer.php';
?>