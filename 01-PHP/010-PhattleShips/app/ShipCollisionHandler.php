<?php
  function collisionFieldMaker($playerShips, $fieldSize, $availableShips, $mode, $messageContents, $shipPositioning, $gameClass, $objectCF): string {
    $field = "";
    foreach ($playerShips as $ship) {
      if (isset($gameClass->$objectCF[$ship[0]])) {
        $collisionField = $gameClass->$objectCF;
      } else {
        $collisionField[$ship[0]][0][0] = 0;
        for ($i = 1; $i <= ($fieldSize[0] * $fieldSize[1]); $i++) {
          $collisionField[$ship[0]][0][$i] = $i;
          $collisionField[$ship[0]][1][$i] = $i;
        }
      }

      $shipLength = $availableShips[$ship[0]];



      // Horizontale:
      $horDataset = 'data-shipsize="' . $shipLength . '" data-ship="' . $ship[0] . '" data-rotation="horizontal"';
      $verDataset = 'data-shipsize="' . $shipLength . '" data-ship="' . $ship[0] . '" data-rotation="vertical"';
      if ($mode == "Placement") {

        $legacyShipLength = $availableShips[$messageContents->ship];
//        echo "Placement: ship: $ship[0]\nlength: $shipLength\n";
        $startPos = $messageContents->cellColumn;


        if ($messageContents->shipRotation == "l" || $messageContents->shipRotation == "r") {
          $endPos = $messageContents->cellColumn + $legacyShipLength - 1;
          $vertical = 0;
        } else {
          $endPos = $messageContents->cellColumn;
          $vertical = 1;
        }

          $maxShipPos = $startPos - $shipLength;
          if ($maxShipPos < 1) {
            $maxShipPos = 0;
          }

          $cellRow = $messageContents->cellRow;
          for ($j = $cellRow; $j <= (($vertical) ? ($cellRow + $shipLength) : ($cellRow)); $j++) {
            for ($i = $endPos; $i > $maxShipPos; $i--) {
              $position = ($j * $fieldSize[0]) - $fieldSize[0] + $i;
//          echo "math: \n" . $position . "\n";
              if ($position <= (($cellRow * $fieldSize[0]) - $fieldSize[0])) {
//            echo "break at: $position\nmessageContents->cellRow * fieldSize[0]\n$messageContents->cellRow * $fieldSize[0]\n";
                break;
              }
              unset($collisionField[$ship[0]][0][$position]);
            }
          }
          $horColumn = ($maxShipPos + 1) . " / " . ($endPos + 1);
          $horRow = ($vertical) ? ($cellRow . " / " . $cellRow + $legacyShipLength) : ($cellRow);


          $maxHeight = $messageContents->cellRow - ($shipLength - 1);
          if ($maxHeight <= 1) {
            $maxHeight = 1;
          }
          for ($i = $messageContents->cellColumn; $i < ($endPos + 1); $i++) {
            for ($j = $maxHeight; $j <= $messageContents->cellRow; $j++) {
              $position = ($j * $fieldSize[0]) - $fieldSize[0] + $i;
//          echo "math: \n" . $position . "\n";
              unset($collisionField[$ship[0]][1][$position]);
            }
          }
          $verColumn = $messageContents->cellColumn . " / " . ($endPos + 1);
//          echo "\nmaxHeight / (cellRow + legacyShipLength)\n$maxHeight .  /  . ($cellRow + $legacyShipLength)\n";
          $verRow = ($vertical) ? ($maxHeight . " /  " . ($cellRow + $legacyShipLength)) : ($maxHeight  . " / " . $cellRow + 1);

      } else {
        $maths = ($fieldSize[0] - ($shipLength - 2));
        $maxShip = (($fieldSize[0] - ($shipLength - 1)) < 1) ? 0 : ($fieldSize[0] - ($shipLength - 1));
        for ($j = 0; $j < $fieldSize[1]; $j++) {
          for ($i = $fieldSize[0]; $i > $maxShip; $i--) {
            unset($collisionField[$ship[0]][0][($i + ($j * $fieldSize[0]))]);
          }
        }
        if ($maths < 1) {
          $maths = 1;
        }
        $horColumn = $maths . " / " . ($fieldSize[0] + 1);
        $horRow = 1 . " / " . ($fieldSize[1] + 1);
        // Vertical:
        $verColumn = 1 . " / " . ($fieldSize[0] + 1);
        $maths = ($fieldSize[1] - ($shipLength - 2));
        $maxShip = ($shipLength - 1);

        for ($j = 1; $j <= $fieldSize[0]; $j++) {
          for ($i = $fieldSize[1]; $i > ($fieldSize[1] - $maxShip); $i--) {
            $math = ($i * $fieldSize[0]) - $fieldSize[0] + $j;
            unset($collisionField[$ship[0]][1][$math]);
          }
        }

        if ($maths < 1) {
          $maths = 1;
        }
        $verRow = $maths . " / " . ($fieldSize[1] + 1);
      }
      $field .= '<div class="ShipCollision" style="grid-row: ' . $horRow . ' ; grid-column: ' . $horColumn . ' ; background-color: #055524" ' . $horDataset . ' ></div>';
      $field .= '<div class="ShipCollision" style="grid-row: ' . $verRow . ' ; grid-column: ' . $verColumn . ' ; background-color: #0EE324" ' . $verDataset . ' ></div>';






//    var_dump($collisionField[$ship[0]]);

      $gameClass->$objectCF = $collisionField;
    }

    return $field . $shipPositioning;
  }

  function shipCollisionHandler($gameClass, $messageContents, $mode): string {
    $availableShips = $gameClass->availableShips[1];

    if (playerchecker($messageContents, $gameClass)) {
      $fieldSize = $gameClass->fieldSizeOne;
      $pl = "One";
    } else {
      $fieldSize = $gameClass->fieldSizeTwo;
      $pl = "Two";
    }

    $objectCF = "collisionField" . $pl;
    $objectSh = "playerShips" . $pl;
    $objectSP = "shipPositioning" . $pl;
    $playerShips = $gameClass->$objectSh;

    $field = collisionFieldMaker($playerShips, $fieldSize, $availableShips, null, $messageContents, null, $gameClass, $objectCF);

    if ($mode == "Placement") {
//      unset($playerShips);
//      $playerShips[0][0] = $messageContents->ship;
//      echo "Placement aufgerufen!\n";
//      var_dump($playerShips);
      $shipPositioning = $gameClass->$objectSP;
      $field .= collisionFieldMaker($playerShips, $fieldSize, $availableShips, $mode, $messageContents, $shipPositioning, $gameClass, $objectCF);
      $gameClass->$objectSP = $field;
    }


//    $row = $fieldSize[0];
//    $column = $fieldSize[1];
//    $collisionField = $gameClass->$objectCF;
//    $field .= collisionFieldChecker($collisionField, $row, $column, "AircraftCarrier", "v");

    return $field;
  }



