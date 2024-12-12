<?php
  function quedAttack($messageContents): bool {
    switch ($messageContents->attack[0]) {
      case "Helicopter":
      case "Attack-Plane":
      case "Spy-Plane":
      case "BallisticMissile":
        return true;
      default:
        return false;
    }
  }
  function readyAircrafts($messageContents, $gameClass, $pl, $plane, $amount) {
    FileDump($gameClass->{"playerShipsClasses" . $pl}, "playerShipsClasses" . $pl . ".txt");
    return $gameClass->{"playerShipsClasses" . $pl}[$messageContents->ship]->getReadyPlanes($plane, $amount);
  }
  function addingToQue($messageContents, $gameClass): void {
    if (playerchecker($messageContents, $gameClass)) {
      $pl = "One";
    } else {
      $pl = "Two";
    }

    $attack = $messageContents->attack[0];
    $amount = $messageContents->attack[1];

    switch ($attack) {
      case "Helicopter":
      case "Attack-Plane":
      case "Spy-Plane":
        $que = 3;
        $aircrafts = readyAircrafts($messageContents, $gameClass, $pl, $attack, $amount);
        if (method_exists($aircrafts[0], 'getMission')) {
          if ($aircrafts[0]->getMission() == "repair") {
            $que = 1;
          }
        }
        foreach ($aircrafts as $aircraft) {
          $aircraft->setTargetPos([$messageContents->cellRow, $messageContents->cellColumn]);
        }
        $gameClass->taskQue[$que][] = $aircrafts;
        // Saved like taskQue=[3=>[0=>[Flugzeug1, Flugzeug2, etc],]]
        break;
      case "BallisticMissile":
        $ballisticMissile = $gameClass->{"playerShipsClasses" . $pl}[$messageContents->ship]->ballisticMissile;
        $amount = $ballisticMissile->getAmount();
        if ($amount > 0) {
          $gameClass->{"playerShipsClasses" . $pl}[$messageContents->ship]->ballisticMissile->setAmount(1, "-");
          $gameClass->taskQue[1][] = $ballisticMissile;
        }
        break;
    }


  }

  function coolDownHandlerOne($messageContents, $gameClass, $index) {
    echo "coolDownHandlerOne\n";
    $action = "";
    $status = "";
    $motherShip = "";
    $pl = "";

    if (!isset($gameClass->taskQue[0])) {
      $gameClass->taskQue[0] = [];
    }

    foreach($gameClass->taskQue[0] as $foreachIndex=>$task) {
      if (method_exists($task[0], 'getPlaneType')) {
        $action = $task[0]->getPlaneType();
        $status = $task[0]->getStatus();
        $motherShip = $task[0]->getMotherShip();
      }
      if (method_exists($task[0], 'getPlayerOwner')) {
        $pl = $task[0]->getPlayerOwner();
      }

      switch ($action) {
        case "Attack-Plane":
          switch ($status) {
            case "attacking":
              $taskCount = count($task);
              // TODO Set the Image on the Target Ship
              $pos = $task[0]->getTargetPos();
              queFunctionVisibles($gameClass, $task[0], $taskCount, 5, $motherShip, $pl, $pos[0], $pos[1], "0", "enemy");
              // TODO Make Damage
              // Setting the Status to "homerun" and setting the Que to 3
              for ($i = 0; $i < $taskCount; $i++) {
                $task[$i]->setStatus("homerun");
              }
              $gameClass->taskQue[2][] = $task;
              break;
            case "homerun":
              $taskCount = count($task);
              // TODO Set the Image on MotherShip$taskCount = count($task);
              $pos = $gameClass->{"playerShipsClasses" . $pl}[$task[0]->getMotherShip()]->getCellPos();
              queFunctionVisibles($gameClass, $task[0], $taskCount, 5, $motherShip, $pl, $pos[0], $pos[1], "180", "friendly");
              // Setting the Status to "parked", Flip InUse to false, set all Aircrafts in Task back to its MotherShip
              for ($i = 0; $i < $taskCount; $i++) {
                $task[$i]->setStatus("parked");
                $task[$i]->setInUse();
                $gameClass->{"playerShipsClasses" . $pl}[$motherShip]->addHomeComingAircrafts("attackPlanes", $task[$i]);
              }
              break;
            case "parked":
              for ($i = 0; $i < count($task); $i++) {
                $task[$i]->setCoolDown(0);
              }
              break;
          }
          break;
        case "Spy-Plane":
          switch ($status) {
            case "attacking":
              $taskCount = count($task);
              // TODO Set the Image on the last scanning field
              // TODO uncover the field
              // Setting the Status to "homerun" and setting the Que to 3
              for ($i = 0; $i < $taskCount; $i++) {
                $task[$i]->setStatus("homerun");
              }
              $gameClass->taskQue[2][] = $task;
              break;
            case "homerun":
              $taskCount = count($task);
              // TODO Set the Image on MotherShip
              // Setting the Status to "parked", Flip InUse to false, set all Aircrafts in Task back to its MotherShip
              for ($i = 0; $i < $taskCount; $i++) {
                $task[$i]->setStatus("parked");
                $task[$i]->setInUse();
                $gameClass->{"playerShipsClasses" . $pl}[$motherShip]->addHomeComingAircrafts("spyPlanes", $task[$i]);
              }
              break;
            case "parked":
              // Settiing Cooldown to 0 for all Values in Task
              for ($i = 0; $i < count($task); $i++) {
                $task[$i]->setCoolDown(0);
              }
              break;
          }
          break;
        case "Helicopter":
          switch ($status) {
            case "attacking":
              $taskCount = count($task);
              $mission = $task[0]->getMission();
              if ($mission == "attack") {
                // TODO Set the Image on the Enemy Ship
                // Make Damage
                $que = 2;
              } else /*"repair"*/ {
                // TODO Set the Image on the Friendly Ship
                // do Repair
                $que = 1;
              }
              // Setting the Status to "homerun" and setting the Que to either 2 or 3 depending on Mission
              for ($i = 0; $i < $taskCount; $i++) {
                $task[$i]->setStatus("homerun");
              }
              $gameClass->taskQue[$que][] = $task;
              break;
            case "homerun":
              $taskCount = count($task);
              // TODO Set the Image on MotherShip
              // Setting the Status to "parked", Flip InUse to false, set all Aircrafts in Task back to its MotherShip
              for ($i = 0; $i < $taskCount; $i++) {
                $task[$i]->setStatus("parked");
                $task[$i]->setInUse();
                $gameClass->{"playerShipsClasses" . $pl}[$motherShip]->addHomeComingAircrafts("helicopters", $task[$i]);
              }
              break;
            case "parked":
              // Set all Cooldown of Values in Task to 0
              for ($i = 0; $i < count($task); $i++) {
                $task[$i]->setCoolDown(0);
              }
              break;
          }
          break;
      case "BallisticMissile":
        // TODO Set Image to Target Ship
        // TODO Make Damage
        break;
    }

      // Unsetting the task after finishing it
//      var_dump($gameClass->taskQue[0][$foreachIndex]);
      unset($gameClass->taskQue[0][$foreachIndex]);
    }
  }
  function coolDownHandlerTwo($messageContents, $gameClass, $index) {
    echo "coolDownHandlerTwo\n";
    $action = "";
    $status = "";
    $pl = "";
    $motherShip = "";


    if (!isset($gameClass->taskQue[1])) {
      $gameClass->taskQue[1] = [];
    }

    foreach($gameClass->taskQue[1] as $foreachIndex=>$task) {
      if (method_exists($task[0], 'getPlaneType')) {
        $action = $task[0]->getPlaneType();
        $status = $task[0]->getStatus();
      }
      if (method_exists($task[0], 'getPlaneType')) {
        $motherShip = $task[0]->getMotherShip();
      }
      if (method_exists($task[0], 'getPlayerOwner')) {
        $pl = $task[0]->getPlayerOwner();
      }
      $fieldSizeFriendly = $gameClass->{"fieldSize" . $pl};
      $fieldSizeEnemy = $gameClass->{"fieldSize" . (($pl == "One") ? "Two" : "One" )};

      switch ($action) {
        case "Attack-Plane":
          switch ($status) {
            case "attacking":
              $taskCount = count($task);
              // TODO Set the Image on the Lower Row of Enemy Field
              $pos = $task[0]->getTargetPos();
              queFunctionVisibles($gameClass, $task[0], $taskCount, 5, $motherShip, $pl, $fieldSizeEnemy[1], $pos[1], "0", "enemy");
              break;
            case "homerun":
              $taskCount = count($task);
              // TODO Set the Image on the Top Row of Friendly Field
              $pos = $gameClass->{"playerShipsClasses" . $pl}[$task[0]->getMotherShip()]->getCellPos();
              queFunctionVisibles($gameClass, $task[0], $taskCount, 5, $motherShip, $pl, "0", $pos[1], "180", "friendly");
              break;
            case "parked":
              // Settiing Cooldown to 1 for all Values in Task
              for ($i = 0; $i < count($task); $i++) {
                $task[$i]->setCoolDown(1);
              }
              break;
          }
          break;
        case "Spy-Plane":
          switch ($status) {
            case "attacking":
              $taskCount = count($task);
              // TODO Set the Image on the first scanning field
              break;
            case "homerun":
              $taskCount = count($task);
              // TODO Set the Image on the Top Row of Friendly Field
              break;
            case "parked":
              // Settiing Cooldown to 1 for all Values in Task
              for ($i = 0; $i < count($task); $i++) {
                $task[$i]->setCoolDown(1);
              }
              break;
          }
          break;
        case "Helicopter":
          $mission = $task[0]->getMission();
          switch ($status) {
            case "attacking":
              $taskCount = count($task);
              if ($mission == "attack") {
                // TODO Set the Image on the Lower Row of Enemy Field
              } else /*"repair"*/ {
                // TODO Set the Image on the MotherShip
              }
              break;
            case "homerun":
              $taskCount = count($task);
              if ($mission == "attack") {
                // TODO Set the Image on the Top Row of Friendly Field
              } else /*"repair"*/ {
                // TODO Set the Image on the Repair Ship
              }
              break;
            case "parked":
              // Set all Cooldown of Values in Task to 0
              for ($i = 0; $i < count($task); $i++) {
                $task[$i]->setCoolDown(1);
              }
              break;
          }
          break;
        case "BallisticMissile":
          // TODO Set Image to MotherShip
          break;
      }
      // Unsetting the task after finishing it and setting them into Que 0
//      var_dump($gameClass->taskQue[1][$foreachIndex]);
      $gameClass->taskQue[0][] = $gameClass->taskQue[1][$foreachIndex];
      unset($gameClass->taskQue[1][$foreachIndex]);
    }
  }
function coolDownHandlerThree($messageContents, $gameClass, $index) {
  echo "coolDownHandlerThree\n";
  $action = "";
  $status = "";
  $motherShip = "";
  $pl = "";

  if (!isset($gameClass->taskQue[2])) {
    $gameClass->taskQue[2] = [];
  }

  foreach($gameClass->taskQue[2] as $foreachIndex=>$task) {
    if (method_exists($task[0], 'getPlaneType')) {
      $action = $task[0]->getPlaneType();
      $status = $task[0]->getStatus();
    }
    if (method_exists($task[0], 'getPlayerOwner')) {
      $pl = $task[0]->getPlayerOwner();
    }

    $fieldSizeFriendly = $gameClass->{"fieldSize" . $pl};
    $fieldSizeEnemy = $gameClass->{"fieldSize" . (($pl == "One") ? "Two" : "One") };
    echo "FieldSizeEnemy for player $pl is: " . $fieldSizeEnemy[0] . " and " . $fieldSizeEnemy[1] . "\n";

    switch ($action) {
      case "Attack-Plane":
        switch ($status) {
          case "attacking":
            $taskCount = count($task);
            // TODO Set the Image on the Top Row Friendly
            $pos = $task[0]->getTargetPos();
            queFunctionVisibles($gameClass, $task[0], $taskCount, 5, $motherShip, $pl, "0", $pos[1], "0", "friendly");
            break;
          case "homerun":
            $taskCount = count($task);
            // TODO Set the Image on the Lower Row Enemy
            $pos = $gameClass->{"playerShipsClasses" . $pl}[$task[0]->getMotherShip()]->getCellPos();
            queFunctionVisibles($gameClass, $task[0], $taskCount, 5, $motherShip, $pl, $fieldSizeEnemy[1], $pos[1], "180", "enemy");
            break;
          case "parked":
            // Setting Cooldown to 1 for all Values in Task
            for ($i = 0; $i < count($task); $i++) {
              $task[$i]->setCoolDown(2);
            }
            break;
        }
        break;
      case "Spy-Plane":
        switch ($status) {
          case "attacking":
            $taskCount = count($task);
            // TODO Set the Image on the Top Row Friendl
            break;
          case "homerun":
            $taskCount = count($task);
            // TODO Set the Image on the Lower Row Enemy
            break;
          case "parked":
            // Setting Cooldown to 1 for all Values in Task
            for ($i = 0; $i < count($task); $i++) {
              $task[$i]->setCoolDown(2);
            }
            break;
        }
        break;
      case "Helicopter":
        $mission = $task[0]->getMission();
        switch ($status) {
          case "attacking":
            $taskCount = count($task);
            if ($mission == "attack") {
              // TODO Set the Image on the Top Row Friendly
            }
            break;
          case "homerun":
            $taskCount = count($task);
            if ($mission == "attack") {
              // TODO Set the Image on the Lower Row Enemy
            }
            break;
          case "parked":
            // Set all Cooldown of Values in Task to 0
            for ($i = 0; $i < count($task); $i++) {
              $task[$i]->setCoolDown(2);
            }
            break;
        }
        break;
  }
    // Unsetting the task after finishing it and setting them into Que 0
//    var_dump($gameClass->taskQue[2][$foreachIndex]);
    $gameClass->taskQue[1][] = $gameClass->taskQue[2][$foreachIndex];
    unset($gameClass->taskQue[2][$foreachIndex]);
  }
}
function coolDownHandlerFour($messageContents, $gameClass, $index) {
  echo "coolDownHandlerFour\n";
  $action = "";
  $status = "";
  $motherShip = "";


  if (!isset($gameClass->taskQue[3])) {
    $gameClass->taskQue[3] = [];
  }

  foreach($gameClass->taskQue[3] as $foreachIndex=>$task) {
    if (method_exists($task[0], 'getPlaneType')) {
      $action = $task[0]->getPlaneType();
      $status = $task[0]->getStatus();
    }
    if (method_exists($task[0], 'getPlayerOwner')) {
      $pl = $task[0]->getPlayerOwner();
    }
    $fieldSizeFriendly = $gameClass->{"fieldSize" . $pl};
    $fieldSizeEnemy = $gameClass->{"fieldSize" . (($pl == "One") ? "Two" : "One") };

    switch ($action) {
      case "Attack-Plane":
        switch ($status) {
          case "attacking":
            $taskCount = count($task);
            // TODO Set the Image on the MotherShip
            $pos = $gameClass->{"playerShipsClasses" . $pl}[$task[0]->getMotherShip()]->getCellPos();
            queFunctionVisibles($gameClass, $task[0], $taskCount, 5, $motherShip, $pl, "1", $pos[1], "0", "friendly");
            break;
          case "parked":
            // Setting Cooldown to 1 for all Values in Task
            for ($i = 0; $i < count($task); $i++) {
              $task[$i]->setCoolDown(3);
            }
            break;
        }
        break;
      case "Spy-Plane":
        switch ($status) {
          case "attacking":
            $taskCount = count($task);
            // TODO Set the Image on the MotherShi
            break;
          case "parked":
            // Setting Cooldown to 1 for all Values in Task
            for ($i = 0; $i < count($task); $i++) {
              $task[$i]->setCoolDown(3);
            }
            break;
        }
        break;
      case "Helicopter":
        $mission = $task[0]->getMission();
        switch ($status) {
          case "attacking":
            $taskCount = count($task);
            if ($mission == "attack") {
              // TODO Set the Image on the MotherShip
            }
            break;
          case "parked":
            // Set all Cooldown of Values in Task to 0
            for ($i = 0; $i < count($task); $i++) {
              $task[$i]->setCoolDown(3);
            }
            break;
        }
        break;
  }
    // Unsetting the task after finishing it and setting them into Que 0
//    var_dump($gameClass->taskQue[3][$foreachIndex]);
    $gameClass->taskQue[2][] = $gameClass->taskQue[3][$foreachIndex];
    unset($gameClass->taskQue[3][$foreachIndex]);
  }
}
//  function queHandler($messageContents, $gameClass) {
//    $cooldownList = array_keys($gameClass->taskQue);
//    foreach ($cooldownList as $cooldown) {
//      switch ($cooldown) {
//        case 0:
//          coolDownHandlerOne($messageContents, $gameClass, $cooldown);
//          break;
//        case 1:
//          coolDownHandlerTwo($messageContents, $gameClass, $cooldown);
//          break;
//        case 2:
//          coolDownHandlerThree($messageContents, $gameClass, $cooldown);
//          break;
//        case 3:
//          coolDownHandlerFour($messageContents, $gameClass, $cooldown);
//          break;
//        default:
//          // Decrease TaskQue 1 step and unset the taskQue where it was so the next Que can move in
//          $gameClass->taskQue[($cooldown - 1)] = $gameClass->taskQue[$cooldown];
//          unset($gameClass->taskQue[$cooldown]);
//          break;
//      }
//    }
//  }

function queHandler($messageContents, $gameClass) {
  $gameClass->queFuncVisibleOne["friendly"] = "";
  $gameClass->queFuncVisibleOne["enemy"] = "";
  $gameClass->queFuncVisibleTwo["friendly"] = "";
  $gameClass->queFuncVisibleTwo["enemy"] = "";

    coolDownHandlerOne($messageContents, $gameClass, "");
    coolDownHandlerTwo($messageContents, $gameClass, "");
    coolDownHandlerThree($messageContents, $gameClass, "");
    coolDownHandlerFour($messageContents, $gameClass, "");
}











