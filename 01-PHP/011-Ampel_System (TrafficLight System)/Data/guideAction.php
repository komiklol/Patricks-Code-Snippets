<?php
    // PB
    // Require the Log-Dumping File to call function logDumpToFile() to log Errors in the logs Folder
    require // [Path Removed]
    require // [Path Removed]

    // PB
    // Require the GroupWorkshopStatus Model and create a new Instance of it
    require // [Path Removed]
    require // [Path Removed]
    $db = $db ?? new GroupWorkshopStatus();
    $loginData = $loginData ?? new LogInData();

    // PB
    // If the Session is not started, start it
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // [Code from Coworker, Removed]

    // PB
    // Get the Group Information from the Database and save it in $return.
    // $return contains the Data as well as the Error Message if there is one.
    if ($_SESSION["role"] == "admin") {
        $_SESSION["groupID"] = 1;
    }
    
    $return = $db->getWorkshopStatuses($_SESSION["groupID"]);

    // PB
    // If there is an Error in the $return Array, log the Error Message
    // Else, iterate through the $return Array and save 'status' and 'groupName' in the $guideArray
    // The $guideArray will be used to display the Group Information in the Guide View
    if (isset($return["Error"])) {
        logDumpToFile("Guide View" . $return["Error"]);
    } else {
        foreach ($return as $key => $value) {
            $workshops[] = ['status' => $value['workshopFill'], 'groupName' => $value['workshopName']];
        }
    }


