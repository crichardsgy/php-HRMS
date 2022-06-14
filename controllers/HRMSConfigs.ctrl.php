<?php
session_start();

require_once "../models/HRMSConfig.php";

class HRMSConfigs 
{
    private $hrmsconfigModel;

    public function __construct()
    {
        $this->hrmsconfigModel = new HRMSConfig;
    }

    public function updateConfig()
    {
        $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

        $fields = [
            'workStart' => trim($_POST["workStart"]),
            'workEnd' => trim($_POST["workEnd"]),
            'maxLeaveAccumulation' => trim($_POST["maxLeaveAccumulation"])
        ];

        if(empty($fields['workStart']) || empty($fields['workEnd']) || empty($fields['maxLeaveAccumulation']))
        {
            header("location: ../managehrms.php?error=emptyFields");
            exit();
        }

        if ($this->hrmsconfigModel->updateConfig($fields))
        {
            header("location: ../managehrms.php?error=none");
            exit();
        }
        else
        {
            die("Something Went Wrong. Please Try Again Later");
        }

    }

}

$init = new HRMSConfigs;

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if($_POST['formtype'] == 'updateconfig')
    {
        $init->updateConfig();
    }
}
