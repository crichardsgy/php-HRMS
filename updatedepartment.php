<?php
    include_once 'header.php';
    require_once "models/Department.php";

    $departmentModel = new Department;

    if(!isset($_POST['editId']))
    {
        $dptid = $_SESSION['editId'];
    }
    else
    {
        $dptid = $_POST['editId'];
    }

    $dptdetails = $departmentModel->findDepartmentById($dptid);

    unset($departmentModel);

    if ($_SESSION['employeeRole'] !== "hr")
    {
        header("location: index.php");
    }
?>

<section class="departmentupdater">
    <h3>Edit Department</h3>
    <form action="controllers/Departments.ctrl.php" method="post">
        <input type="hidden" name="formtype" value="updatedepartment">
        <input type="hidden" name="editId" value="<?php echo "$dptid";?>">
        <?php
            echo "<input type='hidden' name='dptId' placeholder='Department ID' value='$dptdetails->departmentId'>";

            echo "<label for='departmentName'>Department Name(*): </label>";
            echo "<input type='text' name='departmentName' placeholder='Name' value='$dptdetails->departmentName'>";
            echo "<br/>";

            echo "<label for='departmentDescription'>Department Description: </label>";
            echo "<input type='text' name='departmentDescription' placeholder='Description' value='$dptdetails->departmentDescription'>";
            echo "<br/>";

        ?>

        <button class='btn btn-primary' type="submit" name="submit">Update Department</button>
        <a class='btn btn-danger' href="managedepartment.php">Cancel</a>  
    </form>
    <br/>
    <?php
        include_once 'includes/errorhandler.inc.php'
    ?>
</section>

<?php
    include_once 'footer.php';
?>