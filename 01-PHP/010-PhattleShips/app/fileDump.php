<?php
  function FileDump($var, $fileName = "varDump.txt"): void {
    ob_start();
    var_dump($var);
    $fileName = 'logs/' . $fileName;
    file_put_contents($fileName, ob_get_clean());
  }
  // Example use:
  //echo "\nCollision:\n" . $field . "\n";
  //FileDump($field, "Field-SCH81.txt");


function collisionFieldChecker($collisionField, $row, $column, $ship, $rotation) {

    $field = "";

    if ($rotation == "v") {
      $rot = 1;
    } else {
      $rot = 0;

    }

    for ($j = 1; $j <= $column; $j++) {
      for ($i = 1; $i <= $row; $i++) {
        $math = (($j - 1) * $row) + $i;
//        echo "($j - 1) * row) = " . ($j - 1) * $row . " | + $i = " . (($j - 1) * $row) + $i . "\n";
//        echo "ship: $ship | rotation: $rot | position: $math \n";
        if (isset($collisionField[$ship][$rot][$math])) {
          $field .= '<div class="testTile" style="grid-row: ' . ($j) . ' ; grid-column: ' . ($i) . ' ; background-color: rgba(255,255,0,0.1); z-index: 10">' . $math . '</div>';
        } else {
          $field .= '<div class="testTile" style="grid-row: ' . ($j) . ' ; grid-column: ' . ($i) . ' ; background-color: rgba(255, 0, 0, 0.1); z-index: 10">' . $math . '</div>';
        }
      }
    }

    return $field;

  }
  // Example Use:
  //$row = $fieldSize[0];
  //$column = $fieldSize[1];
  //$collisionField = $gameClass->$objectCF;
  //$field .= collisionFieldChecker($collisionField, $row, $column, "AircraftCarrier", "h");
