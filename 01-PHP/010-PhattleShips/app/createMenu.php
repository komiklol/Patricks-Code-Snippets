<?php

function setShipPosition($column, $row, $shipType, $field, $routing, $shipRotation, $otherData): string {
  $stringBegin = '<div class="ShipTile"';
//  $stringBegin = '<div class="ShipTile clickableShip"'; // Activate this for Interacting Menu Ships
  $stringColumn = ' data-column="' . $column . '"';
  $stringRow = ' data-row="' . $row . '"';
  $field = ' data-field="' . $field . '"'; // Enemy / Friendly
  $ship = ' data-ship="' . $shipType . '"';
  $rotation = ' data-rotation="' . $shipRotation . '"';
  $routing = ' data-routing="' . $routing . '"';
  $otherData = ' data-otherData="' . $otherData . '"';
  $stringStyleEnd = ' style="position: relative; grid-column: ' . $column . '; grid-row: ' . $row . ';">';
  return $stringBegin . $stringColumn . $stringRow . $field . $ship . $rotation . $routing . $otherData . $stringStyleEnd;
}
function setShipImage($ship, $length, $shipLength, $fileEnding, $mirror, $shipStyle, $closeDiv, $fileAddition): string {
  $extraStyle ="";
  if ($mirror) {
    $length = ($shipLength + 1) - $length;
    $extraStyle = ' transform: scaleX(-1);';
  }
  if ($length == 5 &&  $ship == "AircraftCarrier") {
    $fileEnding = "gif";
  }
  return '<img src="../img/Ships/' . $ship . $fileAddition . '/' . $ship  . '-' . $length . '.' . $fileEnding . '" alt="Ship-' . $ship . '"' . $shipStyle . $extraStyle . '"' . $closeDiv;
}
function shipCollisionCheck($collisionHandler, $column, $row, $lengthArray, $lengthIndex): bool {
  if ($lengthIndex == null) {
    $lengthArray = [$lengthArray];
    $lengthIndex = 0;
  }
  for ($k = 0; $k < count($collisionHandler); $k+=2) {
    if (isset($collisionHandler[$k])) {
      if (($column < ($collisionHandler[$k] + $lengthArray[$lengthIndex])) && ($column > ($collisionHandler[$k] - $lengthArray[$lengthIndex])) && ($row == $collisionHandler[1 + $k])) {
        return True;
      }
    }
  }
  return False;
}

function randomShipsGenerator($field, $shipStyle, $closeDiv, $usableFieldSize, $playField): string {
  // Change those Ships to the wanted ships to be displayed
  $shipsName = ["AircraftCarrier","BattleCarrier", "AircraftCarrier","BattleCarrier" ];
  // Change corresponding to the Ships the Length of the ship
  $shipsLength = [8, 7, 8, 7];
  $collisionHandler = [];
  for ($j = 0; $j < count($shipsName); $j++) {
    // Available Spaces for Ships: Column 1-26, Row 8 - 14
    do {
      $columnRand = rand($usableFieldSize[0], $usableFieldSize[1] - $shipsLength[$j]); // column min - max (- ShipLength to prevent out of bounce)
      $rowRand = rand($usableFieldSize[2], $usableFieldSize[3]); // row min - max
    } while(shipCollisionCheck($collisionHandler, $columnRand, $rowRand, $shipsLength, $j));
    $collisionHandler[$j * 2] = $columnRand;
    $collisionHandler[1 + $j * 2] = $rowRand;
    $position = [$columnRand, $rowRand]; // Column / Row
    $mirror = rand(0,1);
    for ($i = 1; $i <= $shipsLength[$j]; $i++) {
      $field .= setShipPosition($position[0] + $i - 1, $position[1], $shipsName[$j], $playField, "menu", "null" ,"null");
      $field .= setShipImage($shipsName[$j], $i, $shipsLength[$j], "png", $mirror, $shipStyle, $closeDiv, "");
    }
  }
  return $field;
}


function createMenu($client): void {
  // Set Type of Message
  $type = "CreateMenu PH EF FF";
  $shipStyle = ' style="width: 100%; height: 100%; position: absolute; bottom: 0; left: 0; object-fit: overflow; object-position: bottom;';
  $closeDiv = '></div>';

  $sizeEnemyField = [26, 14];    // row - column
  $sizeFriendlyField = [26, 14]; // row - column

/////////////////////////////////////////////////////////////////////////////////////////////
//                              Enemy Field  / Title Screen                                //
/////////////////////////////////////////////////////////////////////////////////////////////

  $usableFieldSize = [1, 26, 8, 14]; // column min / max, row min / max
  $enemyField = '<div class="TitleScreen" id="GameFieldEnemy" data-columns="' . $sizeEnemyField[0] . '" data-rows="' . $sizeEnemyField[1] . '" style="width: 100%; height: 100%; display: grid; grid-template-columns: repeat(' . $sizeEnemyField[0] . ', 1fr); grid-template-rows: repeat(' . $sizeEnemyField[1] . ', 1fr); gap: 0;">';
  $enemyField .= '<div style="position: relative; grid-column: 4 / 24; grid-row: 2 / 7;">';
  $enemyField .= '<img src="../img/Assets/PhattleShips-by_komiklool.png" alt="TitleScreen" style="width: 100%; height: 100%; object-fit: scale-down; object-position: center;">';
  $enemyField .= '</div>';
  $enemyField = randomShipsGenerator($enemyField, $shipStyle, $closeDiv, $usableFieldSize, "Enemy");
  $enemyField .= '</div>';

/////////////////////////////////////////////////////////////////////////////////////////////
//                           Friendly Field  / Silly Screen                                //
/////////////////////////////////////////////////////////////////////////////////////////////

  $usableFieldSize = [1, 26, 4, 14]; // column min / max, row min / max
  $friendlyField = '<div class="TitleScreenBottom" id="GameFieldFriendly" data-columns="' . $sizeFriendlyField[0] . '" data-rows="' . $sizeFriendlyField[1] . '" style="width: 100%; height: 100%; display: grid; grid-template-columns: repeat(' . $sizeFriendlyField[0] . ', 1fr); grid-template-rows: repeat(' . $sizeFriendlyField[1] . ', 1fr); gap: 0;">';
  $friendlyField = randomShipsGenerator($friendlyField, $shipStyle, $closeDiv, $usableFieldSize, "Friendly");
  $friendlyField .= '<div class="info" style="grid-column: 4 / 24; grid-row: 1 / 4;"><p>If you have a slow Internet Connection, with high Ping, pls be Patient while Playing the Game.</p>';
  $friendlyField .= '<div class="PingContainer"><p id="Ping"></p>';
  $friendlyField .= '<img data-content="Ping" class="ReloadButton" src="../img/Assets/ReloadButton.png" alt="Reload Button"></div>';
  $friendlyField .= '</div></div>';


/////////////////////////////////////////////////////////////////////////////////////////////
//                                PlayerHUD  / Menu                                        //
/////////////////////////////////////////////////////////////////////////////////////////////

  // Call 'makePlayerHUD' in 'PlayerHUD.php'. Give "Create" to let the function know, it should make the Start Menu.
  $playerHUD = makePlayerHUD("Create");


//Send Out///////////////////////////////////////////////////////////////////////////////////
  $client->send(readyForSend($type, $playerHUD, $enemyField, $friendlyField, $sizeEnemyField, $sizeFriendlyField, 0, "createMenu110"));
}












