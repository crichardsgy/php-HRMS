<?php
session_start();

//Connects to the Department Model
require_once "../models/Unit.php";

class Units 
{
    private $unitModel;

    //Creates an instance of the Department model that allows us to access its functions
    public function __construct()
    {
        $this->unitModel = new Unit;
    }

    public function createUnit()
    {
        $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

        //stores data sent by POST from the form
        $fields = [
            'unitName' => trim($_POST["unitName"]),
            'unitDescription' => trim($_POST["unitDescription"]),
            'departmentId' => trim($_POST["departmentId"])
        ];

        //checks if a field in the form is empty
        if(empty($fields['unitName']) || empty($fields['departmentId']))
        {
            //returns the user to the form if successful with the GET "error" variable showing the reason
            header("location: ../createunit.php?error=emptyFields");
            exit();
        }

        //checks size of string in specific field of the form (change the 128 to the max size of the database column)
        if(preg_match('/\S{128,}/',$fields['unitName'])) //https://stackoverflow.com/questions/12414258/how-to-find-out-if-some-word-in-a-string-is-bigger-than-50-characters-in-php
        {
                header("location: ../createunit.php?error=tooManyCharsInUid");
                exit();
        }
        if(preg_match('/\S{128,}/',$fields['unitDescription']))
        {
            header("location: ../createunit.php?error=tooManyCharsInFName");
            exit();
        }

        //sends the validated data from the fields to the createDepartment function in the model which will push it to the database
        if ($this->unitModel->createUnit($fields))
        {
            //returns the user to the homescreen if successful
            header("location: ../manageunits.php?error=none");
            exit();
        }
        else
        {
            die("Something Went Wrong. Please Try Again Later");
        }

    }

    public function updateUnit()
    {
        $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

        $fields = [
            'unitId' => trim($_POST["editId"]),
            'unitName' => trim($_POST["unitName"]),
            'unitDescription' => trim($_POST["unitDescription"]),
            'departmentId' => trim($_POST["departmentId"])
        ];

        $_SESSION['editId'] = $fields['unitId'];

        //checks if a field in the form is empty
        if(empty($fields['unitName']) || empty($fields['departmentId']))
        {
            //returns the user to the form if successful with the GET "error" variable showing the reason
            header("location: ../updateunit.php?error=emptyFields");
            exit();
        }

        //checks size of string in specific field of the form (change the 128 to the max size of the database column)
        if(preg_match('/\S{128,}/',$fields['unitName'])) //https://stackoverflow.com/questions/12414258/how-to-find-out-if-some-word-in-a-string-is-bigger-than-50-characters-in-php
        {
                header("location: ../updateunit.php?error=tooManyCharsInName");
                exit();
        }
        if(preg_match('/\S{128,}/',$fields['unitDescription']))
        {
            header("location: ../updateunit.php?error=tooManyCharsInDescription");
            exit();
        }

        if ($this->unitModel->updateUnit($fields))
        {
            unset($_SESSION['editId']);
            header("location: ../manageunits.php?error=none");
            exit();
        }
        else
        {
            unset($_SESSION['editId']);
            die("Something Went Wrong. Please Try Again Later");
        }

    }


}

//START HERE
//When the form sends data to the page, a hidden input in the form will state its purpose. The below code will read that and call the necessary function that corresponds to the required purpose.
$init = new Units;

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if($_POST['formtype'] == 'createunit')
    {
        $init->createUnit();
    }
    else if($_POST['formtype'] == 'updateunit')
    {
        $init->updateUnit();
    }

}
