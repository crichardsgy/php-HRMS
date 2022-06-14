<?php
    include_once 'header.php';
    require_once "models/HRMSConfig.php";

    $hrmsconfigModel = new HRMSConfig;

    $settings = $hrmsconfigModel->findConfigs();
    $mariadbvariables = $hrmsconfigModel->showVariables();

    unset($hrmsconfigModel);

    if ($_SESSION['employeeRole'] !== "hr")
    {
        header("location: index.php");
    }
?>

<section class="hrmssettings">
    <h3>Settings</h3>
    <form action="controllers/HRMSConfigs.ctrl.php" method="post">
        <input type="hidden" name="formtype" value="updateconfig">
        <?php
            echo "<label for='fname'>Work Start Time (*): </label>";
            echo "<input type='time' name='workStart' placeholder='Work Start Time' value='$settings->workStart'>";
            echo "<br/>";

            echo "<label for='lname'>Work End Time (*): </label>";
            echo "<input type='time' name='workEnd' placeholder='Work End Time' value='$settings->workEnd'>";
            echo "<br/>";

            echo "<label for='maxLeaveAccumulation'>Maximum Years Of Leave Accumulation (*): </label>";
            echo "<input type='number' name='maxLeaveAccumulation' placeholder='Maximum Years Of Leave Accumulation' value='$settings->maxLeaveAccumulation'>";
            echo "<br/>";

            echo "<p>".strtoupper($mariadbvariables->Variable_name)." is ".strtoupper($mariadbvariables->Value)."</p>";
        ?>
        <br/>
        <button class='btn btn-primary' type="submit" name="submit">Update Settings</button>
        <a class='btn btn-danger' href="index.php">Cancel</a>  
    </form>
    <br/>
    <?php
        include_once 'includes/errorhandler.inc.php'
    ?>
</section>

<?php
    include_once 'footer.php';
?>