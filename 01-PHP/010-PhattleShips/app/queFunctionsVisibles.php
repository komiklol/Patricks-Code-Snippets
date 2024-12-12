<?php
  function queFunctionVisibles($gameClass, $task, $taskCount, $maxTaskCount, $motherShip, $pl, $row, $column, $rotation, $status): void {

    $imageCount = min($taskCount, $maxTaskCount);

    $image = $task->getImageName() . "-Flying-Format" . $imageCount . ".png";

    $style1 = 'style="width: 100%; height: 100%; position: absolute; bottom: 0; left: 0; ';
    $style2 = 'object-fit: overflow; object-position: bottom; pointer-events: none; rotation: ' . $rotation . 'deg; " ';
    $style3 = ' style="grid-row: ' . $row . '; grid-column: ' . $column . '; position: relative;"';

    $string = '<div ' . $style3 . ' class="QueFunctionVisible">';
    $string .= '<img ' . $style1 . $style2 . ' src="../img/Planes/' . $image . '" alt="FunctionVisibility" ></div>';

    $gameClass->{"queFuncVisible" . $pl}[$status] .= $string;

    echo "Setting Image for Player $pl on Position r-$row col-$column\n";
    FileDump($string, "$pl-$row-$column-$status.txt");
  }
