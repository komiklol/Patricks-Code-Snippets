<?php
  function initialTimeUp($messageContents, $gameClass, $changeRouting = ""): void {
    if ($changeRouting != "") {
      $messageContents->routing = $changeRouting;
    }
    $tempSaveMessageRouting = $messageContents->routing;
    $messageContents->routing = ["game", "InGame", "war"];
    list($enemyField, $friendlyField) = makePlayingField($messageContents, "war"); // change field to not have collision boxes on it bc they not needed
    $messageContents->routing = $tempSaveMessageRouting;
    $type = "QuickPlayMultiplayer game war Ingame InitialOff Test FF EF TurnTimer";
    $messageContents->client->send(readyForSend($type, "null", $enemyField, $friendlyField, $gameClass->fieldSizeOne, $gameClass->fieldSizeTwo, $gameClass->turnTime, "gamePlacement11"));

    $type = "QuickPlayMultiplayer game war Ingame InitialOff Test PH TurnTimer";
    $playerHUD = makePlayerHUD($messageContents, 1);
    $messageContents->client->send(readyForSend($type, $playerHUD, "null", "null", $gameClass->fieldSizeOne, $gameClass->fieldSizeTwo, $gameClass->turnTime, "gamePlacement15"));

  }
  function allAvailableShipsUsed($gameClass, $messageContents, $player): int {
    // player = 0 : current player
    // player = 1 : Both Players
    $index = ($player) ? 2 : 1;
    $while = 0;
    $counter = 0;
    do {
      if (($player) ? $while : playerchecker($messageContents, $gameClass)) {
        $pl = "One";
      } else {
        $pl = "Two";
      }
      $objectPS = 'playerShips' . $pl;
////      echo "objectPS : $objectPS\n";
      foreach ($gameClass->$objectPS as $ship) {
        if ($ship[1] == 0) {
          $counter++;
////          echo "ship++ = $ship[0]\n";
        }
      }
      $while++;
    } while($while != $index);
////  echo "allAvailableShipsUsed\ncounter: $counter\ncount 1 + count 2:" . (count($gameClass->playerShipsOne) + count($gameClass->playerShipsTwo)) . "\n";
  if ($player && $counter == (count($gameClass->playerShipsOne) + count($gameClass->playerShipsTwo))) {
////    echo "player true\n";
    return 1;
  }
  if (!$player && $counter == count($gameClass->$objectPS)) {
////    echo "player false\n";
    return 1;
  }
  return 0;
  }



  function makeShipTile($gameClass, $rotation, $column, $row, $i, $ship, $shipStyle, $ships, $cellColumn, $cellRow, $preventShipListener = false): string {

    switch($rotation) {
      case "l":
        $cellColumn = $column + ($i - 1);
        break;
      case "r":
        $math = ($gameClass->availableShips[1][$ship] - $i) + 1;
        $cellColumn = $column + ($math - 1);
        break;
      case "u":
        $cellRow = $row + ($i - 1);
        break;
      case "d":
        $math = ($gameClass->availableShips[1][$ship] - $i) + 1;
        $cellRow = $row + ($math - 1);
        break;
    }

    if ($preventShipListener) {
      $shipString = '<div class="ShipTile " ';
    } else {
      $shipString = '<div class="ShipTile ShipTileEventListener" ';
    }
    $shipString .= 'data-column="' . $cellColumn . '" ';
    $shipString .= 'data-row="' . $cellRow . '" ';
    $shipString .= 'data-ship="' . $ship . '00" ';
    $shipString .= 'data-rotation="' . $rotation . '" ';
    $shipString .= 'style="position: relative; grid-column: ' . $cellColumn . '; grid-row: ' . $cellRow . ';">';

    $fileAddition = "";
    $fileEnding = "png";
    if ($i == 5 && $ship == "AircraftCarrier") {
      $fileEnding = "gif";
    }

    $shipString .= '<img src="../img/Ships/' . $ship . $fileAddition . '/' . $ship  . '-' . $i . '.' . $fileEnding . '" ';
    $shipString .= 'alt="Ship-' . $ship . '"' . $shipStyle . '>';
    $shipString .= '</div>';
    $ships .= $shipString;
    return $ships;
  }
  function attackHit($row, $column, $pl, $hit): string {

    $objectESP = "enemyShipPositioning" . $pl;
//    $ships = $gameClass->$objectESP ?? '';
//    $fieldSize = $gameClass->{"fieldSize" . $pl};

    $missedAttackStyle = ' style="width: 100%; height: 100%; position: absolute; bottom: 0; left: 0; ';
    $missedAttackStyle .= 'object-fit: overflow; object-position: bottom; pointer-events: none;" ';
    $divMissedAttackStyle = ' style="grid-row: ' . $row . '; grid-column: ' . $column . '; position: relative; "';

    if ($hit) {
      $mAS = '<div ' . $divMissedAttackStyle . ' class="AttackHit" >';
      $mAS .= '<img ' . $missedAttackStyle . ' src="../img/Assets/Attack-Hit.png" alt="Attack-Hit"></div>';
    } else {
      $mAS = '<div ' . $divMissedAttackStyle . ' class="AttackNoHit" >';
      $mAS .= '<img ' . $missedAttackStyle . ' src="../img/Assets/Attack-No-Hit.png" alt="Attack-No-Hit"></div>';
    }

    return $mAS;
  }

  function fieldHitMarker($row, $column, $field): string {
    $hitMarkerStyle = ' style="width: 100%; height: 100%; position: absolute; bottom: 0; left: 0; ';
    $hitMarkerStyle .= 'object-fit: overflow; object-position: bottom; pointer-events: none;" ';
    $divHitMarkerStyleStyle = ' style="grid-row: ' . $row . '; grid-column: ' . $column . '; position: relative; "';

    $hitMarker = '<div ' . $divHitMarkerStyleStyle . ' class="HitMarker" >';
    if ($field) {
      $hitMarker .= '<img ' . $hitMarkerStyle . ' src="../img/Assets/HitMarker.png" alt="HitMarker"></div>';
    } else {
      $hitMarker .= '<img ' . $hitMarkerStyle . ' src="../img/Assets/HitMarkerGrey.png" alt="HitMarker"></div>';
    }

    return $hitMarker;
  }

  function shipPlacement($messageContents, $ship = "", $position = [], $player = ""): string {
    global $gamesAccess;
    global $runningGames;
    $ship = ($ship == "") ? $messageContents->ship : $ship;
    $row = ($player == "") ? $messageContents->cellRow : $position[0];
    $column = ($player == "") ? $messageContents->cellColumn : $position[1];
    $rotation = ($player == "") ? $messageContents->shipRotation : $position[2];
    $gameClass = $runningGames['game-' . $gamesAccess['access-' . $messageContents->client->resourceId]];
//    echo "shipPlacement: $row, $column, $rotation\n";
    if ($player == "") {
      if (playerchecker($messageContents, $gameClass)) {
        $pl = "One";
      } else {
        $pl = "Two";
      }
    } else {
      $pl = $player;
    }

    $objectSP = "shipPositioning" . $pl;
    $objectESP = "enemyShipPositioning" . $pl;
    $objectCF = "collisionField" . $pl;
    $objectFS = "fieldSize" . $pl;

    $fieldSize = $gameClass->$objectFS;

////    echo "column: $row * fieldSize: $fieldSize[0] + row: $column\n";
    $position = ($row - 1) * $fieldSize[0] + $column;

////    echo "### \n row: $row, column: $column\nposition: $position\n";
    if ($player == "") {
      if ($rotation == "l" || $rotation == "r") {
//      var_dump($gameClass->$objectCF);
        if (!isset($gameClass->$objectCF[$ship][0][$position])) {
//          echo "######################\nHorizontal Position is not set\n";
//        var_dump($gameClass->$objectCF[$ship][0]);
//          echo "\nNeeded Position : $position\n\n";
          return "error";
        }
      } else {
        if (!isset($gameClass->$objectCF[$ship][1][$position])) {
          return "error";
        }
      }
    }

    if ($player == "") {
      $ships = $gameClass->$objectSP ?? '';
    } else {
      $ships = "";
    }


    $shipStyle = ' style="width: 100%; height: 100%; position: absolute; bottom: 0; left: 0; ';
    $shipStyle .= 'object-fit: overflow; object-position: bottom; ';
    switch ($rotation) {
      case "l":
        $shipStyle .= 'transform: scaleX(1) scaleY(1);" ';
        break;
      case "u":
        $shipStyle .= 'transform: scaleX(-1) rotate(90deg);" ';
        break;
      case "r":
        $shipStyle .= 'transform: scaleX(-1) scaleY(1);" ';
        break;
      case "d":
        $shipStyle .= 'transform: scaleY(-1) rotate(90deg);" ';
        break;
    }

    $cellRow = $row;
    $cellColumn = $column;
    if ($player != "") {
      $preventShipListener = true;
    } else {
      $preventShipListener = false;
    }

    if ($rotation == "l" || $rotation == "u") {
      for ($i = 1; $i <= $gameClass->availableShips[1][$ship]; $i++) {
        $ships = makeShipTile($gameClass, $rotation, $column, $row, $i, $ship, $shipStyle, $ships, $cellColumn, $cellRow, $preventShipListener);
      }
    } else {
      for ($i = $gameClass->availableShips[1][$ship]; $i >= 1 ; $i--) {
        $ships = makeShipTile($gameClass, $rotation, $column, $row, $i, $ship, $shipStyle, $ships, $cellColumn, $cellRow, $preventShipListener);
      }
    }

    if ($player == "") {
      $gameClass->$objectSP = $ships;
    } else {
      $gameClass->$objectESP .= $ships;
    }

    // collision should be done
    if ($player == "") {
      $collisions = shipCollisionHandler($gameClass, $messageContents, "Placement");
    } else {
      $collisions = "";
    }

    return $ships . $collisions;
  }
  function gamePlacement($messageContents): void {

    global $gamesAccess;
    global $runningGames;
    $gameClass = $runningGames['game-' . $gamesAccess['access-' . $messageContents->client->resourceId]];

/////////////////////////////////////////////////////////////////////////////////////////////
//                                PlayingFields  / Ship Placing /                          //
/////////////////////////////////////////////////////////////////////////////////////////////
    list($enemyField, $friendlyField) = makePlayingField($messageContents, "placement");
    if ($enemyField == "error") {
//      echo str_repeat("//", 20) . "\nError in 'gamePlacement.php'->'gamePlacement' = Error, Ship cannot find placing Position\n\n" . str_repeat("//", 20) . "\n";
      return;
    }


/////////////////////////////////////////////////////////////////////////////////////////////
//                                PlayerHUD  / Ship Placing /                              //
/////////////////////////////////////////////////////////////////////////////////////////////

    if (playerchecker($messageContents, $gameClass)) {
      $pl = "One";
    } else {
      $pl = "Two";
    }
    $objectPS = "playerShips" . $pl;
    foreach ($gameClass->$objectPS as $ship) {
////      echo "MessageContents Ship / ship[0]\n";
//      var_dump($messageContents->ship);
//      var_dump($ship[0]);
////      echo "\n";
      if ($ship[0] == $messageContents->ship) {
//        var_dump($gameClass->$objectPS);
//        var_dump(count($gameClass->$objectPS));
        for ($i = 0; $i < count($gameClass->$objectPS); $i++) {
//          var_dump($gameClass->$objectPS[$i][0]);
          if ($gameClass->$objectPS[$i][0] == $messageContents->ship) {
            $gameClass->$objectPS[$i][1]--;
          }
        }
      }
    }
    $playerHUD = makePlayerHUD($messageContents);

    $allShipsPlaced = 0;
    if (allAvailableShipsUsed($gameClass, $messageContents, 0)) {
////      echo "allAvailableShipsUsed ging durch :D \n";
      $allShipsPlaced = 1;
    }

    $type = "QuickPlayMultiplayer game placement Ingame InitialOn Test PH FF TurnTimer";
    $messageContents->client->send(readyForSend($type, $playerHUD, "null", $friendlyField, $gameClass->fieldSizeOne, $gameClass->fieldSizeTwo, $gameClass->turnTime, "gamePlacement238"));

//    echo "allShipsPlaced : $allShipsPlaced";
    if ($allShipsPlaced) {
      initialTimeUp($messageContents, $gameClass);
      if ($pl == "One"){
        $gameClass->playersReady = 1;
      } else {
        $gameClass->playersReady = 2;
      }
    }


    if (allAvailableShipsUsed($gameClass, $messageContents, 1)) {
//      echo "playerReady\n";
      $gameClass->playersReady = 3;
      playerReady($messageContents);
    }

    // PlayerHUD erstellen und senden
    // Button die jweiligen Daten besitzen
    // EventListener schreiben



  }




//var_dump($messageContents->cellField);
//var_dump($messageContents->cellColumn);
//var_dump($messageContents->cellRow);
//var_dump($messageContents->ship);
//var_dump($messageContents->otherData);
//var_dump($messageContents->shipRotation);
