<?php

// PB
// Require the Log-Dumping File to call function logDumpToFile() to log Errors in the logs Folder
require // [Path Removed]
require // [Path Removed]

    // PB
    // If the Session is not started, start it
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // [Code from Coworker, Removed]

    // PB
    // Require the GroupInfo Model and create a new Instance of it
    require // [Path Removed]
    $groupInfo = $db ?? new GroupInfo();

    // PB
    // Get the Teacher Information from the Database and save it in $return.
    // $return contains the Data as well as the Error Message if there is one.
    $return = $groupInfo->selectGroupLastVisit();

    // PB
    // If there is an Error in the $return Array, log the Error Message
    // Else, loop through the count of $return and save 'groupNr' and 'workshop' in the $teacherGroups
    // The $teacherGroups will be used to display the Group Information in the Teacher View
    if (isset($return["Error"])) {
        logDumpToFile("Teacher View - " . $return["Error"]);
    } else {
        foreach ($return as $key => $value) {
            $teacherGroups[] = ['groupNr' => $value['groupID'], 'workshop' => $value['lastVisited']];
        }
    }


