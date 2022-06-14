<?php
    include_once 'header.php';
?>

<section class="createemployeeform">
    <h3>Request Standard Leave</h3>
    <form action="controllers/Leaves.ctrl.php" method="post">
        <input type="hidden" name="formtype" value="requeststandardleave">

        <label for="fname">Leave Start Date (*): </label>
        <input type="date" name="leaveStart" placeholder="Leave Start Date">
        <br/>

        <label for="lname">Leave End Date (*): </label>
        <input type="date" name="leaveEnd" placeholder="Leave End Date">
        <br/>

        <label for="lname">Reason For Leave: </label>
        <textarea name="leaveReason" placeholder="Reason For Leave" rows="4" cols="50"></textarea>
        <br/>

        <br/><br/>

        <button class='btn btn-primary' type="submit" name="submit">Request Leave</button> 
        <a class='btn btn-danger' href="myleave.php">Cancel</a>  
    </form>
    <br/>
    <?php
        include_once 'includes/errorhandler.inc.php'
    ?>
</section>

<?php
    include_once 'footer.php';
?>