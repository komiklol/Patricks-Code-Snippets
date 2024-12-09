<?php
// PB
// If there is no Session startet, start Session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// PB
// Require the Log-Dumping File to call function logDumpToFile() to log Errors in the logs Folder
// Require GroupWorkshopStatus Model and create a new Instance of it
require // [Path Removed]
require // [Path Removed]
require // [Path Removed]
require // [Path Removed]
$db = $db ?? new GroupWorkshopStatus();
$groupInfo = $groupInfo ?? new GroupInfo();

// PB
// Get Group Count for the Select
$groupCount = $groupInfo->groupCount;

// [Code from Coworker, Removed]

// PB
// This is for Debugging Only, a Short Test Case for the Workshop View
// Note: This is obsolete due to Admin Login
//$_SESSION["workshopID"] = "6"; // DEBUG Avoid DB Error when ID and Name are not corresponding to each other
//$_SESSION["workshopName"] = "FiAe"; // DEBUG

// PB
// Save Session Variables in "Normal" Variables
$workshopID = $_SESSION["workshopID"] ?? null;
$workshopName = $_SESSION["workshopName"] ?? null;

// PB
// Initialize Variables
$return = null;
$dbInputDelete = null;
$dbInputStatus = null;
$dbInputLastVisited = null;

// PB
// Check if POST Key is set, if not set it to null
$_POST["deleteOne"] = $_POST["deleteOne"] ?? null;
$_POST["deleteTwo"] = $_POST["deleteTwo"] ?? null;

// PB
// Check if Method on opening is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // PB
    // If the POST Key "selectedGroup" is set as Key, extract the Group and save it in §selectedGroup.
    if (isset($_POST["selectedGroup"])) {
        $selectedGroup = $_POST["selectedGroup"];
        // PB
        // Check for the Different Scenarios
        // If selectedGroup equals "auswählen" or groupWaiting or groupActive    - break                - Do Nothing
        // If groupActive is not set and groupWaiting is not set                 - set as groupActive   - Set the selectedGroup as groupActive
        // If groupActive is set and groupWaiting is not set                     - set as groupWaiting  - Set the selectedGroup as groupWaiting
        // If groupActive is set and groupWaiting is set                         - Error                - Set Error "Gruppen Slots Voll. Neuer Eintrag nicht möglich!"
        // If something entirely different happens                               - Error                - Set Error "Ein unerwartetes Szenario ist Eingetreten!"
        switch (true) {
            case ($selectedGroup == $_SESSION["groupActive"] || $selectedGroup == $_SESSION["groupWaiting"] || $selectedGroup == "empty"):
                break;
            case ($_SESSION["groupActive"] == "Gruppe Aktiv" && $_SESSION["groupWaiting"] == "Gruppe Wartend"):
                $_SESSION["groupActive"] = $selectedGroup;
                // PB
                // Insert the new Group in the Database, corresponding on the selectedGroup and the WorkshopID
                // $return, only contains Data when an Error occurs
                $return = $db->insertNewGroupVisit ($selectedGroup, $workshopID);
                $groupInfo->updateGroupsLastVisit($selectedGroup, $workshopName);
                break;
            case ($_SESSION["groupActive"] != "Gruppe Aktiv" && $_SESSION["groupWaiting"] == "Gruppe Wartend"):
                $_SESSION["groupWaiting"] = $selectedGroup;
                // PB
                // Insert the new Group in the Database, corresponding on the selectedGroup and the WorkshopID
                // $return, only contains Data when an Error occurs
                $return = $db->insertNewGroupVisit ($selectedGroup, $workshopID);
                $groupInfo->updateGroupsLastVisit($selectedGroup, $workshopName);
                break;
            case ($_SESSION["groupActive"] != "Gruppe Aktiv" && $_SESSION["groupWaiting"] != "Gruppe Wartend"):
                $return["Error"] = "Gruppen Slots Voll. Neuer Eintrag nicht möglich! Switch Case. $selectedGroup, {$_SESSION["groupActive"]}, {$_SESSION["groupWaiting"]}";
                break;
            default:
                $return["Error"] = "Ein unerwartetes Szenario ist Eingetreten! Switch Case Default! $selectedGroup, {$_SESSION["groupActive"]}, {$_SESSION["groupWaiting"]}";
                break;
        }
    }

    // PB
    // Check If "deleteOne" or "done" is set as POST Key
    if (isset($_POST["deleteOne"]) || isset($_POST["done"])) {
        $groupID = $_SESSION["groupActive"];
        if ($_POST["done"]) {
            // PB
            // Update the Status of the Active Group to "done"
            $return = $db->updateStatus($workshopID);
        } else {
            // PB
            // Delete the DB Row of the Active Group
            $return = $db->deleteGroup($groupID, $workshopID);
        }
        // PB
        // Empty groupActive ("Gruppe Aktiv")
        $_SESSION["groupActive"] = "Gruppe Aktiv";

        // PB
        // If groupWaiting is not Empty ("Gruppe Wartend"), set groupWaiting as groupActive and groupWaiting Empty ("Gruppe Wartend")
        if ($_SESSION["groupWaiting"] != "Gruppe Wartend") {
            $groupID = $_SESSION["groupWaiting"];
            $_SESSION["groupActive"] = $_SESSION["groupWaiting"];
            $_SESSION["groupWaiting"] = "Gruppe Wartend";
            // PB
            // Update the Status of the Waiting Group to "active"
            if ($_POST["deleteOne"]) {
                $return = $db->updateStatus($workshopID);
            }
        }
    }

    // PB
    // Check if "deleteTwo" is set as Key. Delete the DB Row of the Waiting Group. Set Waiting as Empty ("Gruppe Wartend")
    if (isset($_POST["deleteTwo"])) {
        $groupID = $_SESSION["groupWaiting"];
        $return = $db->deleteGroup($groupID, $workshopID);
        $_SESSION["groupWaiting"] = "Gruppe Wartend";
    }

    // PB
    // Check if the $return Array contains an Error Message. If so, log the Error Message into the logs Folder
    if ($return["Error"]) {
        logDumpToFile("Workshop View - " . $return["Error"]);
    }

    // PB
    // After executing this Code, go back to the Overview
    header() // [Path Removed]
} else {
    refreshWSData ();
}

// PB
// This Function gathers the Data of the DB if Groups are entered as Active or Waiting. If so the Groups are set.
function refreshWSData () {
    // PB
    // Get Workshop ID
    $workshopID = $_SESSION["workshopID"] ?? null;
    $db = $db ?? new GroupWorkshopStatus();

    // PB
    // If the Method is not POST, get the Active and Waiting Group from the Database
    $return = $db->selectWorkshopActiveOrWaiting($workshopID);

    // PB
    // If the return array has a statusGroup, check if it is active or waiting and set the Session Variables accordingly
    if (isset($return[0]["statusGroup"])) {
        if ($return[0]["statusGroup"] == "active") {
            $_SESSION["groupActive"] = $return[0]["groupID"];
        }
        if ($return[0]["statusGroup"] == "waiting") {
            $_SESSION["groupWaiting"] = $return[0]["groupID"];
        }
        if (isset($return[1]["statusGroup"])) {
            if ($return[1]["statusGroup"] == "active") {
                $_SESSION["groupActive"] = $return[1]["groupID"];
            }
            if ($return[1]["statusGroup"] == "waiting") {
                $_SESSION["groupWaiting"] = $return[1]["groupID"];
            }
        }
        // PB
        // If the Aktive Group is empty and the Waiting Group has an Entry. Set the Waiting to Active, and Update the Waiting in DB.
        if (($_SESSION["groupActive"] == "Gruppe Aktiv") && ($_SESSION["groupWaiting"] != "Gruppe Wartend")) {
            $_SESSION["groupActive"] = $_SESSION["groupWaiting"];
            $_SESSION["groupWaiting"] = "Gruppe Wartend";
            $error = $db->updateStatus($workshopID);
        }
    }

    // PB
    // If an error is thrown, Dump it like a Dumpster.
    if (isset($error["Error"])) {
        logDumpToFile("Workshop View - " . $return["Error"]);
    }
}

