<?php
    include_once 'header.php';
    require_once "models/Department.php";
    require_once "models/Unit.php";

    $departmentModel = new Department;
    $unitModel = new Unit;

    if(!isset($_POST['editId']))
    {
        $unitId = $_SESSION['editId'];
    }
    else
    {
        $unitId = $_POST['editId'];
    }

    $unitdetails = $unitModel->findUnitByUnitId($unitId);
    $departments = $departmentModel->findAllDepartments();

    unset($departmentModel);
    unset($unitModel);

    if ($_SESSION['employeeRole'] !== "hr")
    {
        header("location: index.php");
    }
?>

<section class="unitupdater">
    <h3>Edit Unit</h3>
    <form action="controllers/Units.ctrl.php" method="post">
        <input type="hidden" name="formtype" value="updateunit">
        <input type="hidden" name="editId" value="<?php echo "$unitId";?>">
        <?php
            echo "<label for='unitName'>Unit Name(*): </label>";
            echo "<input type='text' name='unitName' placeholder='Unit Name' value='$unitdetails->unitName'>";
            echo "<br/>";

            echo "<label for='unitDescription'>Unit Description: </label>";
            echo "<input type='text' name='unitDescription' placeholder='Unit Description' value='$unitdetails->unitDescription'>";
            echo "<br/>";
        ?>

        <label for="department">Department (*): </label>    
        <select id="department" name="departmentId">
        <option value="">Select A Department</option>
            <?php 
                foreach($departments as $department) 
                {
                    echo '<option value="'.$department->departmentId.'">'.$department->departmentName.'</option>';
                }
                echo "<option value='$unitdetails->departmentId' selected>$unitdetails->departmentName</option>";   
            ?>
        </select>
        <br/><br/>

        <button class='btn btn-primary' type="submit" name="submit">Update Unit</button>
        <a class='btn btn-danger' href="manageunits.php">Cancel</a>  
    </form>
    <br/>
    <?php
        include_once 'includes/errorhandler.inc.php'
    ?>
</section>

<?php
    include_once 'footer.php';
?>