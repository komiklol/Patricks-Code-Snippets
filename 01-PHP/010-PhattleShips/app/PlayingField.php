<?php

  function makePlayingField($messageContents, $field = "") {
    $game = false;
    if ($field == "placement") {
      $game = true;
    }
    // $field = "Friendly" / "Enemy"

    global $gamesAccess;
    global $runningGames;
    $gameClass = null;
    if (isset($gamesAccess['access-' . $messageContents->client->resourceId])) {
      if (isset($runningGames['game-' . $gamesAccess['access-' . $messageContents->client->resourceId]])) {
        $gameClass = $runningGames['game-' . $gamesAccess['access-' . $messageContents->client->resourceId]];
      }
    }

    $playfieldStyle1 = ' style="width: 100%; height: 100%; display: grid; grid-template-columns: repeat(';
    $playfieldStyle2 = ', 1fr); grid-template-rows: repeat(';
    $playfieldStyle3 = ', 1fr); gap: 0;"';
    $playfieldData1 = ' data-columns="';
    $playfieldData2 = '" data-rows="';
    $playfieldData3 = '">';
    $closingDiv = '</div>';

    if ($messageContents->otherData == "CustomGame") {
//      echo "Function -> makePlayingFieldCustomGameScreenZero($field)";
      return makePlayingFieldCustomGameScreenZero($field, "normal");
    }
    if ($messageContents->otherData == "CustomGame1") {
      echo "Function -> makePlayingFieldCustomGameScreenOne($field)";
//      return makePlayingFieldCustomGameScreenOne($field);
    }
    if($messageContents->otherData == "QuickPlaySolo") {
      echo "\nQuickPlaySolo\n";
    }
    switch($messageContents->routing[1]) {
      case "QuickPlayMultiplayer":
      case "CustomGame":
      case "InGame":
        $game = true;
        break;
    }
    if ($game) {
//      echo "routing: \n";
//      var_dump($messageContents->routing);

      if (playerchecker($messageContents, $gameClass)) {
        $sizeFr = $gameClass->fieldSizeOne;
        $sizeEn = $gameClass->fieldSizeTwo;
        $pl = "One";
      } else {
        $sizeFr = $gameClass->fieldSizeTwo;
        $sizeEn = $gameClass->fieldSizeOne;
        $pl = "Two";
      }
      $fieldFr = '<div id="GameFieldFriendlyGame"' . $playfieldStyle1 . $sizeFr[0] . $playfieldStyle2 . $sizeFr[1] . $playfieldStyle3;
      $fieldFr .= $playfieldData1 . $sizeFr[0] . $playfieldData2 . $sizeFr[1] . $playfieldData3;
      $fieldFr .= '<div id="ControlTile" style="grid-column: 1; grid-row: 1; width: 100%; height: 100%; background-color: red; z-index: -5"></div>';


      switch ($messageContents->routing[2]) {
        case "Initial":
          $fieldFr .= shipCollisionHandler($gameClass, $messageContents, "Initial");
          if ($field == "placement") {
            $fieldSP = shipPlacement($messageContents);
            if ($fieldSP == "error") {
              return array("error", "error");
            }
            $fieldFr .= $fieldSP;
          }
          $fieldFr .= $closingDiv;
          $fieldEn = null;
          if ($field != "placement") {
            $fieldEn = '<div id="GameFieldEnemy"' . $playfieldStyle1 . $sizeEn[0] . $playfieldStyle2 . $sizeEn[1] . $playfieldStyle3;
            $fieldEn .= $playfieldData1 . $sizeEn[0] . $playfieldData2 . $sizeEn[1] . $playfieldData3 . $closingDiv;
          }
          return array($fieldEn, $fieldFr);

        case "war":
//          echo "routing in war for player : $pl\n";
          $objectSP = "shipPositioning" . $pl;
          $shipPositioning = $gameClass->$objectSP;
          $position = strpos($shipPositioning, '<div class="ShipTile');
          if ($position) {
            // Search for the pattern,
//            echo "PlayersReady: " . $gameClass->playersReady;
            $objectPSC = "playerShipsClasses" . $pl;
            $objectESPA = "enemyShipPositionArray" . $pl;
            $fieldSize = $gameClass->{"fieldSize" . $pl};
            foreach ($gameClass->availableShips[0] as $ship) {
              // Search for (example:) "data-ship="AircraftCarrier00"", matches are saved in $indexes, flag to save position of finding
              preg_match_all('/data-ship="' . $ship . '00"/', $shipPositioning, $indexes, PREG_OFFSET_CAPTURE);
              // ShipTile Counter
              $counter = 1;
              // Ship Counter
              $shipCounter = 0;
              $rowShipClass = 0;
              $columnShipClass = 0;
              $rotationShipClass = "";
              // Iterate through all found Match
              foreach ($indexes[0] as $index) {
                // If counter is the same count as the ship Length
                // Get the Position of the String Match
                $pos = $index[1];
                $posOfInt = 11 + strlen($ship);
                // Get dataset row, column and rotation
                preg_match('/data-row="(\d+)"/', substr($shipPositioning, ($pos - 40)), $row);
                preg_match('/data-column="(\d+)"/', substr($shipPositioning, ($pos - 30)), $column);
                preg_match('/data-rotation="(\w)"/', substr($shipPositioning, $pos), $rotation);
//                echo "Counter = $counter\nShip = $ship\nrow = $row[1]\ncolumn = $column[1]\nrotation = $rotation[1]\n";
                if ($counter == 1) {
                  $rowShipClass = $row[1];
                  $columnShipClass = $column[1];
                  $rotationShipClass = $rotation[1];
                }
//                echo "row[1] = $row[1] | Counter = $counter | fieldsize[0] = $fieldSize[0] | column[1] = $column[1]\n";
                $positionMath = (($row[1] - 1) * $fieldSize[0] + $column[1]);
                // if the Number is less 10 then just replace 1 Number in the String, if Higher 10 replace both Numbers
                if ($shipCounter < 10) {
                  $shipPositioning[$pos + $posOfInt + 1] = $shipCounter;
                } else {
                  // Casting the ShipCounter to String to use it as array
                  $shipCounterString = "$shipCounter";
                  $shipPositioning[$pos + $posOfInt] = $shipCounterString[0];
                  $shipPositioning[$pos + $posOfInt + 1] = $shipCounterString[1];
                }
                // If Ship Counter less 10, make String 0 + ShipCounter as Example "05", if more use the Number
                if ($shipCounter < 10) {
                  $shipCounterString = "0" . ($shipCounter);
                } else {
                  $shipCounterString = ($shipCounter);
                }
                if ($counter == $gameClass->availableShips[1][$ship]) {
                  // reset ShipTile counter
                  $counter = 0;
                  $shipCounter++;
                  // create String to call the needed Class, example: "\MyApp\AircraftCarrier"
                  $shipClass = '\\MyApp\\' . $ship;
                  $shipKey = ($ship . $shipCounterString);
//                Make ShipKey with the Ship "AircraftCarrier" and the CounterString "01"
                  $gameClass->$objectPSC[$shipKey] = new $shipClass($shipKey, [$rowShipClass, $columnShipClass, $rotationShipClass], $pl);
                }

                echo "objectESPA = $objectESPA\nPositionMath = $positionMath\nShip . ShipCounterString = " . ($ship . $shipCounterString) . "\n";
                $gameClass->$objectESPA[$positionMath] = ($ship . $shipCounterString);
                $counter++;
              }
//              echo "Ship = $ship | Player: $pl |  counter = $counter | CountAC = $shipCounter \n";
            }
            FileDump($gameClass->$objectESPA, ("objectESPA" . $pl . ".txt"));
//            $shipPositioning = substr($shipPositioning, $position);
//            $fieldFr .= $shipPositioning;
//            $gameClass->$objectSP = $shipPositioning . $closingDiv;

//            $gameClass->playerTurn = rand(1,2);
            $gameClass->playerTurn = 1; // DEBUG TESTING, Remove for normal play

            $shipPositioning = substr($shipPositioning, $position);
            $fieldFr .= $shipPositioning;
            $gameClass->$objectSP = $shipPositioning ;
          } else {
            $fieldFr .= $shipPositioning;
          }
          $fieldFr .= $closingDiv . $closingDiv;
          $fieldEn = '<div id="GameFieldEnemy"' . $playfieldStyle1 . $sizeEn[0] . $playfieldStyle2 . $sizeEn[1] . $playfieldStyle3;
          $fieldEn .= $playfieldData1 . $sizeEn[0] . $playfieldData2 . $sizeEn[1] . $playfieldData3 . $closingDiv;
//          FileDump($fieldFr, "fieldFr-PF91.txt");
          return array($fieldEn, $fieldFr);
      }



    }


    // By automatic mathing by setting Height (rows) is rows * 2.7142 round()
    // Width (columns) is columns / 2.7142 round()







  }





















