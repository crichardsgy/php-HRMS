<?php
    include_once 'header.php';

    if(!isset($_POST['employeeId']))
    {
        $employeeId = $_SESSION['terminationId'];
    }
    else
    {
        $employeeId = $_POST['employeeId'];
    }
?>

<section class="terminationform">
    <h3>Terminate Employee</h3>
    <br/>

    <form action="controllers/Employees.ctrl.php" method="post">
        <input type="hidden" name="formtype" value="terminateemployee">
        <input type="hidden" name="employeeId" value="<?php echo "$employeeId";?>">
        <label for="employeeStatus">Status (*): </label>
        <select id="employeeStatus" name="employeeStatus">
        <option value='retired'>Retired</option>
            <option value='contractend'>Contract Ended</option>
            <option value='terminated'>Terminated</option>
        </select>
        <br/>

        <label for="reason">Reason (If Applicable): </label><br/>
        <textarea name="reason" placeholder="Reason For Termination" rows="4" cols="50"></textarea>
        <br/>
        <br/>

        <button class='btn btn-danger' type="submit" name="submit">Terminate</button> 
        <a class='btn btn-secondary' href="manageemployees.php">Cancel</a>  
    </form>
    <br/>
    <?php
        include_once 'includes/errorhandler.inc.php'
    ?>
</section>

<?php
    include_once 'footer.php';
?>