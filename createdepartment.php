<?php
//this integrates the navbar and the libraries used into this form
    include_once 'header.php';
?>

<section class="createdepartmentform">
    <h3>Create Department</h3>

    <!-- Sends data from the form to controllers/Departments.ctrl.php via POST -->
    <form action="controllers/Departments.ctrl.php" method="post">
        
        <!-- the hidden input that tells the Department controller what function to call (check the bottom of the controllers/Departments.ctrl.php file to see what I mean) -->
        <input type="hidden" name="formtype" value="createdepartment">

        <!-- normal form elements -->
        <label for="uid">Department Name (*): </label>
        <input type="text" name="departmentName" placeholder="Department Name">
        <br/>

        <label for="fname">Department Description: </label>
        <input type="text" name="departmentDescription" placeholder="Department Description">
        <br/>

        <button class='btn btn-primary' type="submit" name="submit">Create Department</button> 
        <a class='btn btn-danger' href="managedepartment.php">Cancel</a>  
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