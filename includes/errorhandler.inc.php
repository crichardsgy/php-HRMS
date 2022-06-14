<?php

if (isset($_GET["error"]))
{
    if($_GET["error"] == "none")
    {
        echo "<p>Success!</p>";
    }
    else if($_GET["error"] == "invalidLogin")
    {
        echo "<p>Username Or Password Incorrect. Please Try Again.</p>";
    }
    else if($_GET["error"] == "emptyFields")
    {
        echo "<p>Please Ensure All Required (*) Fields Are Filled In.</p>";
    }
    else if($_GET["error"] == "invalidUid")
    {
        echo "<p>Please Ensure Username Contains No Special Characters.</p>";
    }
    else if($_GET["error"] == "usernameTaken")
    {
        echo "<p>Sorry, Username Is Taken. Please Try Another.</p>";
    }
    else if($_GET["error"] == "invalidPwdMatch")
    {
        echo "<p>Please Ensure Passwords Match.</p>";
    }
    else if($_GET["error"] == "statementFailed")
    {
        echo "<p>Please Try Again Later.</p>";
    }
    else if($_GET["error"] == "tooManyCharsInDescription")
    {
        echo "<p>Description Exceeded 128 Characters.</p>";
    }
    else if($_GET["error"] == "tooManyCharsInName")
    {
        echo "<p>Name Exceeded 256 Characters.</p>";
    }
    else if($_GET["error"] == "tooManyCharsInUid")
    {
        echo "<p>Username Exceeded 128 Characters.</p>";
    }
    else if($_GET["error"] == "tooManyCharsInFName")
    {
        echo "<p>First Name Exceeded 128 Characters.</p>";
    }
    else if($_GET["error"] == "tooManyCharsInLName")
    {
        echo "<p>Last Name Exceeded 128 Characters.</p>";
    }
    else if($_GET["error"] == "tooManyCharsInPwd")
    {
        echo "<p>Password Exceeded 128 Characters.</p>";
    }
    else if($_GET["error"] == "tooManyCharsInReason")
    {
        echo "<p>Reason Exceeded 128 Characters.</p>";
    }
    else if($_GET["error"] == "invalidRole")
    {
        echo "<p>Role Must Either Be Admin Or Normal</p>";
    }
    else if($_GET["error"] == "leaveOutOfBounds")
    {
        echo "<p>Leave Start Must Be Earlier Than Leave End</p>";
    }
    else if($_GET["error"] == "notEnoughLeave")
    {
        echo "<p>You Do Not Have Enough Leave Days</p>";
    }
    else if($_GET["error"] == "noSickLeaveAvailable")
    {
        echo "<p>No Sick Leave Available</p>";
    }
    else if($_GET["error"] == "noStandardLeaveAvailable")
    {
        echo "<p>No Standard Leave Available</p>";
    }
}