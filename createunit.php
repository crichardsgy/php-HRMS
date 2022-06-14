<?php
//this integrates the navbar and the libraries used into this form
    include_once 'header.php';
    require_once "models/Department.php";

    $departmentModel = new Department;
    $departments = $departmentModel->findAllDepartments();
    unset($departmentModel);
?>

<section class="createunitform">
    <h3>Create Unit</h3>

    <!-- Sends data from the form to controllers/Departments.ctrl.php via POST -->
    <form action="controllers/Units.ctrl.php" method="post">
        
        <!-- the hidden input that tells the Department controller what function to call (check the bottom of the controllers/Departments.ctrl.php file to see what I mean) -->
        <input type="hidden" name="formtype" value="createunit">

        <!-- normal form elements -->
        <label for="unitName">Unit Name (*): </label>
        <input type="text" name="unitName" placeholder="Unit Name">
        <br/>

        <label for="unitDescription">Unit Description: </label>
        <input type="text" name="unitDescription" placeholder="Unit Description">
        <br/>

        <label for="department">Department (*): </label>
        <select id="department" name="departmentId">
        <option value="">Select A Department</option>
            <?php 
                foreach($departments as $department) 
                {
                    echo '<option value="'.$department->departmentId.'">'.$department->departmentName.'</option>';
                }          
            ?>
        </select>
        <br/><br/>

        <button class='btn btn-primary' type="submit" name="submit">Create Unit</button> 
        <a class='btn btn-danger' href="manageunits.php">Cancel</a>  
    </form>
    <br/>
    <?php
        //this reads GET variables and shows error messages (the controller sets the GET variables when an error is found during validation)
        include_once 'includes/errorhandler.inc.php'
    ?>
</section>

<?php
    include_once 'footer.php';
?>