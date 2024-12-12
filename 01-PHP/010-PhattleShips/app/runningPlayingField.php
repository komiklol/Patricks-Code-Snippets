<?php
function runningPlayingFieldEnemy($messageContents, $gameClass) {
  if (playerchecker($messageContents, $gameClass)) {
    $pl = "Two";
  } else {
    $pl = "One";
  }

  echo "\n\nrunningPlayingFiledEnemy\nFor Player : " . $pl . "\n\n";
  $fieldSize = $gameClass->{"fieldSize" . $pl};
  $objectESPA = "enemyShipPositionArray" . $pl;
  $objectESP = "enemyShipPositioning" . $pl;

  $playfieldStyle1 = ' style="width: 100%; height: 100%; display: grid; grid-template-columns: repeat(';
  $playfieldStyle2 = ', 1fr); grid-template-rows: repeat(';
  $playfieldStyle3 = ', 1fr); gap: 0;"';
  $playfieldData1 = ' data-columns="';
  $playfieldData2 = '" data-rows="';
  $playfieldData3 = '">';
  $closingDiv = '</div>';

  $fieldEn = '<div id="GameFieldEnemy"' . $playfieldStyle1 . $fieldSize[0] . $playfieldStyle2 . $fieldSize[1] . $playfieldStyle3;
  $fieldEn .= $playfieldData1 . $fieldSize[0] . $playfieldData2 . $fieldSize[1] . $playfieldData3;

  $positionArray = $gameClass->$objectESPA;
  $positionMath = (($messageContents->cellRow) * $fieldSize[0] + $messageContents->cellColumn);

  $fieldEn .= $gameClass->$objectESP ?? "";

  echo "positionMath : " . $positionMath . "\n";
  if (isset($positionArray[($positionMath + 1)])) {
    $shipName = $positionArray[($positionMath + 1)];
    $shipClass = $gameClass->{"playerShipsClasses" . $pl}[$shipName];
    $fieldEn .= shipPlacement($messageContents, $shipClass->getShipName(), $shipClass->getCellPos(), $pl);
    $fieldEn .= attackHit(($messageContents->cellRow + 1), ($messageContents->cellColumn + 1), $pl, true);
  } else {
    $fieldEn .= attackHit(($messageContents->cellRow + 1), ($messageContents->cellColumn + 1), $pl, false);
    $gameClass->$objectESP .= fieldHitMarker(($messageContents->cellRow + 1), ($messageContents->cellColumn + 1), true);
  }

  $fieldEn .= $gameClass->{"queFuncVisible" . $pl}["enemy"];
//  echo ("runningPlayingField.php\n---------------\n queFuncVisible + $pl + ['enemy']");
//  echo ("runningPlayingField.php\n---------------\n" . $gameClass->{"queFuncVisible" . $pl}["enemy"]);

  $fieldEn .= $closingDiv;

  return $fieldEn;
}











