<?php
//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//
//##//                                      Other Functions                                                 //##//
//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//
  function playerMoveChecker($messageContents, $gameClass): int {
    // Checks if the Sender is Ready
    if (playerchecker($messageContents, $gameClass)) {
      if ($gameClass->playersReady == 1) {
        return 1;
      }
    } else {
      if ($gameClass->playersReady == 2) {
        return 1;
      }
    }
    return 0;
  }

  function playerTurnChecker($gameClass, $pl): bool {
    if (($gameClass->playerTurn == 1 && $pl == "One") || ($gameClass->playerTurn == 2 && $pl == "Two")) {
      return true;
    } else {
      return false;
    }
  }
function checkTurnTimer($gameClass, $i, $string = "TurnTimer" ): string { // i : 0 = placement, 1 = move
//    echo "turnTime: " . var_dump($gameClass->turnTime);
  if ($gameClass->turnTime[$i]) {
    return $string;
  } else {
    return "";
  }
}

//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//
//##//                               Over The Top Info Panel Functions                                      //##//
//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//
  function lightBulb($messageContents, $gameClass, $pl): string {
    $lb = '<div class="lightBulb" id="LightBulb" ';
    $lb .= 'style="grid-column: 1; grid-row: 1; display: flex; justify-content: center; align-items: center;">';
    if (playerTurnChecker($gameClass, $pl)) {
      $lb .= '<img src="../img/Assets/LightBulb-GREEN.png" alt="LightBulb-Green" style="width: 40px;"></div>';
    } else {
      $lb .= '<img src="../img/Assets/LightBulb-RED.png" alt="LightBulb-RED" style="width: 40px;"></div>';
    }
    return $lb;
  }

  function timerPanel($gameClass): string {
    $tp = "";
    // If both Players not Ready (playersReady not 3) the turnTimer for Initial is used, if both ready turnTimer move is used
    if ($gameClass->playersReady != 3) {
      $turnTimer = $gameClass->turnTime["initial"];
    } else {
      $turnTimer = $gameClass->turnTime["move"];
    }
    // Create the Div container containing the Timer
    if (($turnTimer != 0)) {
      $tp .= '<div class="TurnTimer" ';
      $tp .= 'style="grid-column: 2 / 4; grid-row: 1; display: flex; justify-content: left; align-items: center;">';
      $time = sprintf("%d:%02d", floor($turnTimer / 60), ($turnTimer % 60));
      $tp .= '<p id="TurnTimer" style="margin: 0; padding-right: 5px; padding-left: 10px; font-size: x-large; overflow: hidden; white-space: nowrap">' . $time . '</p></div>';
    }
    return $tp;
  }

  function movesPanel($gameClass, $pl): string {
    $mp = "";
    $objectPT = "player" . $pl . "Turns";
    // Create the div which contains the moves counter
    if ($gameClass->$objectPT[0] != -1) {
      // get the current move count of the player
      $turnCounter = $gameClass->$objectPT[0];
      // get the max amount of moves  the player can do
      $turnCounterMax = $gameClass->$objectPT[1];
      $mp .= '<div class="TurnCounter" id="TurnCounter" ';
      $mp .= 'style="grid-column: 9 / 11; grid-row: 1; display: flex; justify-content: right; align-items: center;">';
      $mp .= '<p style="margin: 0; padding-right: 10px; padding-left: 5px; font-size: x-large; overflow: hidden; white-space: nowrap">';
      $mp .= ($turnCounter + 1) . ' / ' . $turnCounterMax . '</p></div>';
    }
    return $mp;
  }

//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//
//##//                                      Top Info Panel Functions                                        //##//
//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//
  function topInfoPanalIG($messageContents, $gameClass, $pl): string {
    // Make the
    $tip = '<div class="infoPanelTop" id="infoPanelTop" ';
    $tip = infoPanelBoxStyle($tip, "2 / 10", "2 / 7", "#39731f", true); // Contains closing </div>
    $tip .= healthBarIG($messageContents, $gameClass, $pl);
    return $tip;
  }
  function healthBarIG($messageContents, $gameClass, $pl): string {
    if (($messageContents->ship != null) && (playerTurnChecker($gameClass, $pl))) {

      // Set main Div for HealthBarDiv
      $hb = '<div class="HealthBarDiv" ';
      $hb .= 'style="grid-column: 2 / 9; grid-row: 7; display: flex; align-items: center;">';

      // Get the ShipClass
      $objectPSC = "playerShipsClasses" . $pl;

      echo "Ship : $messageContents->ship | player : $pl\n";
      FileDump($gameClass->$objectPSC, "rGPHUD97.txt");
      $shipClasses = $gameClass->$objectPSC[$messageContents->ship];
      // get ship length
      $shipLength = $shipClasses->getShipLength();
      // get max tile hp
      $shipMaxTileHP = $shipClasses->getHitPointsPL();
      // get tile hp
      $shipTileHPArray = $shipClasses->getCurrentHitPointsPL();
      // get sinking level
      $shipSinkingLevel = $shipClasses->getSinkingLvl();
      // get max total ship hp
      $shipMaxHP = $shipClasses->getHitPointsTotal();
      // get Repair Per Length
      $shipRepairPL = $shipClasses->getRepairPRPL();
      // math total ship hp
      $shipTotalHP = 0;
      foreach ($shipTileHPArray as $tileHP) {
        $shipTotalHP += $tileHP;
      }
      // make the Health-Bar here
      // create div with grid. grid length = ship length
      $hb .= '<div class="HealthBar" style="display: grid; grid-template-rows: repeat(3, 1fr);';
      $hb .= 'grid-template-columns: repeat(' . $shipLength . ',1fr); gap: 0;">';
      // create small bars for each tile taking 1 slot in grid
      for ($i = 0; $i < $shipLength; $i++) {
        // Math Ship Tile HP Percent
        $shipTileHPPercent = ($shipTileHPArray[$i] / $shipMaxTileHP) * 100;
        $hbTile = '<div class="HealthBarTile" style="grid-column: ' . ($i + 1) . ';"><div class="HealthBarTileInner" style="grid-column: ' . ($i + 1) . '; width: ' . $shipTileHPPercent . '%"></div></div>';
        $hb .= $hbTile;
      }
      // create big bar over whole length containing max hp
      $shipHPPercent = ($shipTotalHP / $shipMaxHP) * 100;
      echo "MATHS: \n shipTotalHP / shipMaxHP * 100\n$shipTotalHP / $shipMaxHP\n";
      $hbTotal = '<div class="HealthBarTotal" style="grid-column: 1 / ' . ($shipLength + 1) . ';">';
      $hbTotal .= '<div class="HealthBarTotalInner" style="width: ' . $shipHPPercent . '%; grid-column: 1 / ' . ($shipLength + 1) . ';"></div></div>';
      $hbTotal .= '<div class="HealthBarTotalSinkingLvL" style="width: ' . $shipSinkingLevel . '%; grid-column: 1 / ' . ($shipLength + 1) . ';"></div>';
      $hb .= $hbTotal . '</div>'  ;

      // make the repair per round here
      $repairPLT = '<div class="RepairPerTile" ';
      $repairPLT .= 'style="grid-column: 9 ; grid-row: 7; display: flex; align-items: center; justify-content: center;">';
      $repairPLT .= '<p>+' . $shipRepairPL . '/R</p>';

      return $hb . '</div>' . $repairPLT . '</div>';
    }
    return "";
  }

//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//
//##//                                   Middle Info Panel Functions                                        //##//
//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//
  function middleInfoPanelIG($messageContents, $gameClass, $pl): string {
    $objectPSC = "playerShipsClasses" . $pl;
    $mip = '<div class="infoPanelMiddle" id="infoPanelMiddle" ';
    $mip .= 'style="grid-column: 2 / 10; grid-row: 8 / 19; ';
    $mip .= 'display: flex; flex-direction: column; justify-content: space-evenly; align-items: center;">';

    // Change this section only called when one player is calling

    if (playerTurnChecker($gameClass, $pl)) {
      if (isset($messageContents->ship)) {
        $shipClasses = $gameClass->$objectPSC;
        $ship = $messageContents->ship;
        $mip .= makeActionButtons($gameClass->maxAircraft, $shipClasses[$ship]); // change to the ship wich is called
      } else {
        $mip .= '<p class="middleInfoPanelText01">Pls Select a Ship</p>';
      }
    } else {
      $mip .= '<p class="middleInfoPanelText01">Pls wait for your Turn</p>';
    }

    return $mip . '</div>';
  }
  function makeCountable($name, $count, $aircraftTotal): string {
    $countable = '<div class="Countable" >';
    if ($name == "Helicopter") {
      $repairString = '<div class="Mission-Selector-EL" id="Mission-Repair"><img src="../img/Assets/Mission-Repair-Icon.png" alt="Repair-Mission-Selector"></div>';
      $attackString = '<div class="Mission-Selector-EL" id="Mission-Attack"><img src="../img/Assets/Mission-Attack-Icon.png" alt="Attack-Mission-Selector"></div>';
      $mission = '<div class ="Countable-Mission">' . $repairString . $attackString . '</div>';
    } else {
      $mission = "";
    }
    $minus = '<div class="Countable-Bundle"><p class="Countable-Button Countable-Minus-Button" data-name="' . $name . '" id="Countable-Minus-Button-' . $name . '" style="color: #780505FF; font-family: Impact,serif; font-size: xx-large;">-</p>';
    $counter = '<p class="Countable-Number"><span class="Countable-Number" id="Countable-Number-' . $name . '" data-count="' . $count . '" data-name="' . $name . '" data-amount="1">1</span>';
    $counter .= ($count) ? ' / ' . $count : '';
    $counter .= '</p>';
    $plus = '<p class="Countable-Button Countable-Plus-Button" data-name="' . $name . '" id="Countable-Plus-Button-' . $name . '" style="color: #067832FF; font-family: Impact,serif; font-size: xx-large;">+</p></div>';
    $amountLeft = makeAmountLeft($name, $aircraftTotal);
    return $countable . $mission . $minus . $counter . $plus . $amountLeft . '</div>';
  }
  function makeAmountLeft($name, $amountLeftInt): string {
    return '<p class="Countable-AmountLeft" id="Countable-AmountLeft-' . $name . '" style="color: #000000FF; font-family: Impact,serif; font-size: xx-large;">' . $amountLeftInt . '</p>';
  }
  function makeActionButtons($maxAircraft, $ship) { // do $gameClass->$maxAircraft vorher

    // Beziehe aus Ship Class die Aktionen
    // Action Contains :
    // Aircraft = [Name, AvailableCount, Count, CoolDown]
    $actions = $ship->getActions();

    // Array of Buttons
    $buttons = '';
    foreach ($actions as $action) {
      // Erstelle Button <div>
      $button = '<div class="Action-Button ShipButtonCSS" data-action="' . $action[0] . '" id="AcBu-' . $action[0] . '" ';
      $button .= setButton(" ", $ship->getShipNameNr()) . ' >';

      // Erstelle Aktion Bild
      $button .= '<div class="Action-Image-Container">';
      $button .= '<img class="Action-Image" src="../img/Assets/' . $action[0] . '_Action.png" alt="Action-Img"></div>';

      // Erstelle Cooldown
      $button .= '';

      // Erstelle Counter
      $aircraftsLeft = -1;
      switch ($action[0]) {
        case "Attack-Plane":
          $aircraftsLeft = $ship->getAttackPlanesCount();
          break;
        case "Spy-Plane":
          $aircraftsLeft = $ship->getSpyPlanesCount();
          break;
        case "Helicopter":
          $aircraftsLeft = $ship->getHelicoptersCount();
          break;
        case "BallisticMissile":
          $button .= '<div class="BallisticMissileButton">';
          $button .= makeAmountLeft($action[0], $ship->ballisticMissile->getAmount());
          $button .= '</div>';
          break;

      }
      if ($aircraftsLeft != -1) {
        if ($maxAircraft[$action[0]] != 999) {
          $button .= makeCountable($action[0], $maxAircraft[$action[0]], $aircraftsLeft);
        } else {
          $button .= makeCountable($action[0], 0, $aircraftsLeft);
        }
      }

      // Schließe Button </div>
      $buttons .= $button . '</div>';
    }
    return $buttons;
  }

//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//
//##//                                   Bottom Info Panel Functions                                        //##//
//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//
  function bottomInfoPanelIG($messageContents, $gameClass) {
    $tip = '<div class="infoPanelBottom" id="infoPanelBottom" ';
    return infoPanelBoxStyle($tip, "2 / 10", "19 / 24", "#555555", true);
  }


//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//
//##//                                          Main Functions                                              //##//
//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//##//
  function runningGamePH($messageContents, $gameClass, $player = 0) {

    if ($player == 1) {
      $pl = "One";
    } elseif ($player == 2) {
      $pl = "Two";
    } else {
      if (playerchecker($messageContents, $gameClass)) {
        $pl = "One";
      } else {
        $pl = "Two";
      }
    }

//    var_dump($messageContents->routing);

    $playerHUD = "";

    $standardPH = '<div style="width: 100%; height: 100%; display: grid; grid-template-columns: repeat(10, 1fr); grid-template-rows: repeat(24, 1fr); gap: 0">';
    $closingDiv = '</div>';

    // game, war, ...
    switch ($messageContents->routing[2]) {
      case "empty-Hud":
      case "shipmenu":
      case "timesup":
//        echo "execute empty-hud\n";
        $playerHUD = $standardPH;
        $playerHUD .= lightBulb($messageContents, $gameClass, $pl);
        $playerHUD .= timerPanel($gameClass);
        $playerHUD .= movesPanel($gameClass, $pl);
        $playerHUD .= topInfoPanalIG($messageContents, $gameClass, $pl);
        $playerHUD .= middleInfoPanelIG($messageContents, $gameClass, $pl);
        $playerHUD .= bottomInfoPanelIG($messageContents, $gameClass, $pl);
        $playerHUD .= $closingDiv;
        break;

    }


    return $playerHUD;


  }
