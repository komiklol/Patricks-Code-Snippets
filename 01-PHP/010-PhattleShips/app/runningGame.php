<?php
  function playerReady($messageContents, $timesUp = 0) {

    global $gamesAccess;
    global $runningGames;
    $gameClass = $runningGames['game-' . $gamesAccess['access-' . $messageContents->client->resourceId]];
////    echo "7 - gameClass->playersReady : $gameClass->playersReady\n";

    if (($gameClass->playersReady == 3) && $timesUp) {
//      echo "Both players done, timesup, return\n";
      return;
    }

    $changeRouting = ["game", "placement", "Initial"];
    if ($gameClass->playersReady != 3) {
//      var_dump($messageContents->routing);
      if ($gameClass->playersReady == 0) {
//        echo "Both players where not ready\n";
        $messageContents->client = $gameClass->player1;
        initialTimeUp($messageContents, $gameClass, $changeRouting);
        if (isset($gameClass->player2)) {
          $messageContents->client = $gameClass->player2;
          initialTimeUp($messageContents, $gameClass, $changeRouting);
        } else {
//          Echo "Player Two not Set\n";
        }
      }
      if (playerchecker($messageContents, $gameClass)) {
        if ($gameClass->playersReady == 1) {
//          echo "msg from p1 but p1 already ready\n";
          return;
        }
//        echo "msg from p1 and p1 not ready\n";
        $messageContents->client = $gameClass->player1;
      } else {
        if ($gameClass->playersReady == 2) {
//          echo "msg from p2 but p2 already ready\n";
          return;
        }
//        echo "msg from p2 and p2 not ready\n";
        $messageContents->client = $gameClass->player2;
      }
    }


//    if ($gameClass->playersReady == 3) {
//      return;
//    }

//    echo "Both players Ready, sending out new HUD  (runningGame-50)\n";
    initialTimeUp($messageContents, $gameClass, $changeRouting);
    $messageContents->routing[2] = "empty-Hud";
    $messageContents->ship = null;

    // new playerhud sending here


//    $playerHUD = ""; // erstelle playerHUD for p1
    shipMenuPH($messageContents, $gameClass, "gamePlacementDone");
//    FileDump($gameClass, "gameClass-RG54.txt");
  }

  function shipMenuPH($messageContents, $gameClass, $activateEL = "", $enemyField = "", $playerFieldOrigin = "", $otherPlayerField = "", $otherPlayerFieldPl = 0) {

    for ($i = 1; $i <= 2; $i++) {
      $objectP = "player$i";
////      echo "playerTurn = " . $gameClass->playerTurn . " | i = $i (runningGame-66)\n";
      $enemyType = "";
      $playerFieldType = "";
      if ($enemyField != "") {
        if (playerchecker($messageContents, $gameClass)) {
          if ($i == 1) {
            $enemyType = "EF ";
          } else {
            $playerFieldType = "FF ShipTileMenuListener ";
          }
        } else {
          if ($i == 1) {
            $playerFieldType = "FF ShipTileMenuListener ";
          } else {
            $enemyType = "EF ";
          }
        }
      }
      if ($otherPlayerFieldPl == $i) {
        $playerFieldType = "FF ";
        $playerField = $otherPlayerField;
      } else {
        $playerField = $playerFieldOrigin;
      }

      if ($activateEL == "gamePlacementDone") {
        $type = "QuickPlayMultiplayer game war Ingame Test PH ". $enemyType . $playerFieldType . checkTurnTimer($gameClass, "move", "SetTimerMove" ) . " ShipTileMenuListener";
      } else {
        $type = "QuickPlayMultiplayer game war Ingame Test PH ". $enemyType . $playerFieldType . checkTurnTimer($gameClass, "move", "SetTimerMove" );
      }
//      echo "\nType\n------------\n$type\n";
      $playerHUD = runningGamePH($messageContents, $gameClass, $i);
      echo "Sending out: $objectP\n";
      FileDump($enemyField, "$objectP-enemy.txt");
      FileDump($playerField, "$objectP-friendly.txt");
      if ($enemyField == "") {
        $gameClass->$objectP->send(readyForSend($type, $playerHUD, "null", "null", $gameClass->fieldSizeOne, $gameClass->fieldSizeTwo, $gameClass->turnTime, "runningGame.php72"));
      } else {
        if ($i == 1) {
          $gameClass->$objectP->send(readyForSend($type, $playerHUD, $enemyField , $playerField, $gameClass->fieldSizeOne, $gameClass->fieldSizeTwo, $gameClass->turnTime, "runningGame.php72"));
        } else {
          $gameClass->$objectP->send(readyForSend($type, $playerHUD, $enemyField , $playerField, $gameClass->fieldSizeOne, $gameClass->fieldSizeTwo, $gameClass->turnTime, "runningGame.php72"));
        }
      }
    }
  }

  function makeAttackFriendlyField($messageContents, $gameClass, $switch = false): array {
    $playfieldStyle1 = ' style="width: 100%; height: 100%; display: grid; grid-template-columns: repeat(';
    $playfieldStyle2 = ', 1fr); grid-template-rows: repeat(';
    $playfieldStyle3 = ', 1fr); gap: 0;"';
    $playfieldData1 = ' data-columns="';
    $playfieldData2 = '" data-rows="';
    $playfieldData3 = '">';
    $closingDiv = '</div>';
    if (playerchecker($messageContents, $gameClass)) {
      $sizeFr = $gameClass->fieldSizeTwo;
      $pl = "Two";
    } else {
      $sizeFr = $gameClass->fieldSizeOne;
      $pl = "One";
    }
    if ($switch) {
      if (playerchecker($messageContents, $gameClass)) {
        $pl = "One";
        $sizeFr = $gameClass->fieldSizeOne;
      } else {
        $pl = "Two";
        $sizeFr = $gameClass->fieldSizeTwo;
      }
    }

    $fieldSize = $gameClass->{"fieldSize" . $pl};
    $objectESPA = "enemyShipPositionArray" . $pl;

    $positionArray = $gameClass->$objectESPA;
    $positionMath = (($messageContents->cellRow) * $fieldSize[0] + $messageContents->cellColumn);

    $objectSP = "shipPositioning" . $pl;
    $playerField = '<div id="GameFieldFriendlyGame"' . $playfieldStyle1 . $sizeFr[0] . $playfieldStyle2 . $sizeFr[1] . $playfieldStyle3;
    $playerField .= $playfieldData1 . $sizeFr[0] . $playfieldData2 . $sizeFr[1] . $playfieldData3;
    $playerField .= '<div id="ControlTile" style="grid-column: 1; grid-row: 1; width: 100%; height: 100%; background-color: red; z-index: -5"></div>';
    $playerField .= $gameClass->$objectSP;
    if (!quedAttack($messageContents)) {
      if (isset($positionArray[($positionMath + 1)])) {
        $playerField .= attackHit(($messageContents->cellRow + 1), ($messageContents->cellColumn + 1), $pl, true);
      } else {
        $playerField .= attackHit(($messageContents->cellRow + 1), ($messageContents->cellColumn + 1), $pl, false);
        $gameClass->$objectSP .= fieldHitMarker(($messageContents->cellRow + 1), ($messageContents->cellColumn + 1), false);
      }
    }

    if ($switch) {
      if (playerchecker($messageContents, $gameClass)) {
        $pl = "One";
      } else {
        $pl = "Two";
      }
      $playerField .= $gameClass->{"queFuncVisible" . $pl}["friendly"];
      $returnString = $playerField . $closingDiv . $closingDiv;
      return array($returnString, $pl);
    }
    $returnString = $playerField . $closingDiv . $closingDiv;
    return array($returnString, $pl);
  }

  function inGame($messageContents) {
    global $gamesAccess;
    global $runningGames;
    $gameClass = $runningGames['game-' . $gamesAccess['access-' . $messageContents->client->resourceId]];

    $enemyField = "";
    $playerField = "";
    $otherPlayerField = "";
    $otherPlayerFieldPL = 0;
    switch ($messageContents->routing[2]) {
      case "shipmenu":
        $type = "QuickPlayMultiplayer game war Ingame Test PH " . checkTurnTimer($gameClass, "move") . " ActionButtonListener";
        $playerHUD = runningGamePH($messageContents, $gameClass);
        if (playerchecker($messageContents, $gameClass)) {
          $objectP = "player1";
        } else {
          $objectP = "player2";
        }
        $gameClass->$objectP->send(readyForSend($type, $playerHUD, "null", "null", $gameClass->fieldSizeOne, $gameClass->fieldSizeTwo, $gameClass->turnTime, "runningGame.php70"));

        break;
      case "attack":
////        echo "Attack Triggered!  (runningGame-164)\n"; // DEBUG

        addingToQue($messageContents, $gameClass);
        queHandler($messageContents, $gameClass);

        $enemyField = runningPlayingFieldEnemy($messageContents, $gameClass);
        list($playerField, $pl) = makeAttackFriendlyField($messageContents, $gameClass);
        if (quedAttack($messageContents)) {
          list($otherPlayerField, $pl) = makeAttackFriendlyField($messageContents, $gameClass, true);
          $otherPlayerFieldPL = ($pl == "One") ? 1: 2;
        }
        if (playerchecker($messageContents, $gameClass)) {
          $objectP = "player1";
        } else {
          $objectP = "player2";
        }


        // Math Damage to the Ship
        // if planes are used, add them to waiting-que as time 3, place on friendly field top row, position of aircraftcarrier


      case "timesup":
//        echo "Times Up Triggered!  (runningGame-98)\n"; // DEBUG
        $messageContents->ship = null;
        $messageContents->routing[2] = "timesup";
        $gameClass->playerTurn = ($gameClass->playerTurn == 1) ? 2: 1;
        FileDump($gameClass->taskQue, "taskQue-RG-" . date("mm-ss") . ".txt");
        shipMenuPH($messageContents, $gameClass, "", $enemyField, $playerField, $otherPlayerField, $otherPlayerFieldPL);
        break;


    }

  }



