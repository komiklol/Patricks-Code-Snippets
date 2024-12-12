<?php
  function playerchecker($messageContents, $gameClass): bool {
    if (!isset($gameClass->player1)) {
      return false;
    }
    if ($messageContents->client->resourceId === $gameClass->player1->resourceId) {
      return true;
    } else {
      return false;
    }
  }

  function PayPalIntegration(): string {
    $ppi = '<div class="Donation" id="donate-button-container">';
    $ppi .= '</div>';
    return $ppi;
  }

  // Makes the Styling for the Top and Bottom Info Panels
  function infoPanelBoxStyle($playerHUD, $pos1, $pos2, $color, $layer): string {
    $playerHUD .= 'style="grid-column: ' . $pos1 . '; grid-row: ' . $pos2 . '; background-color: ' . $color . '; ';
    $playerHUD .= 'border: #0e0e0e 3px solid; border-radius: 10px;">';
    $playerHUD .= (($layer) ? '<div class="SnowLayer"></div>' : "") . '</div>';
    return $playerHUD;
  }

  // Making Top Info Panel
  function makeTopInfoPanel($playerHUD, $pos1, $pos2, $color, $layer): string {
    $playerHUD .= '<div class="infoPanelTop" id="infoPanelTop" ';
    return infoPanelBoxStyle($playerHUD, $pos1, $pos2, $color, $layer);
  }

  // Returning the data of the Buttons for te Routing
  function setButton($routing, $button): string {
    $stringRouting = ' data-routing="' . $routing . '" ';
    $stringButton = ' data-otherData="' . $button . '" ';
    return $stringRouting . $stringButton;
  }

  // Making the Buttons depending on the Values in the 'buttons' array
  function infoPanelButtons($playerHUD, $buttons) {
    $extraStyle = "";
    if ($buttons == "CustomGame") {
      $extraStyle = 'style="height: 80%; ';
    }
    foreach ($buttons as $value) {
      $playerHUD .= '<div class="playerHUDButton" id="' . $value . '" ' . $extraStyle . setButton(("menu " . $value), $value) . '>';
      $playerHUD .= '<img src="../img/Assets/' . $value . '.png" alt="' . $value . '" style="width: 100%"></div>';
    }
    return $playerHUD;
  }

  // Creating the Ships, as Buttons for Playing them
  function shipButtons($gameClass, $messageContents): string {
    if (playerchecker($messageContents, $gameClass)) {
      $availableShips = $gameClass->playerShipsOne;
//      echo "\n Loading gameclass->playerShipOne\n";
//      var_dump($availableShips);
    } else {
      $availableShips = $gameClass->playerShipsTwo;
//      echo "\n Loading gameclass->playerShipTwo\n";
//      var_dump($availableShips);
    }

    $availableShipSizes = $gameClass->availableShips[1];
    $shipButtons = "";

    $index = 0;
    foreach ($availableShips as $ship) {
      if (playerchecker($messageContents, $gameClass)) {
        $available = $gameClass->playerShipsOne[$index][1];
      } else {
        $available = $gameClass->playerShipsTwo[$index][1];
      }

      $red = "";
      if ($available == 0) {
        $red = "-Red";
      }
      $shipButtons .= '<div class="ShipButtonCSS ShipButton' . $red . '" id="' . $ship[0] . '-Button" ' . setButton("game placement Initial", $ship[0] ) . ' data-shipsize="' . $availableShipSizes[$ship[0]] . '" >';
      $shipButtons .= '<img class="ShipPlacementButton" src="../img/Ships/' . $ship[0] . '/1600x200_' . $ship[0] . $red . '.png" alt="' . $ship[0] . '-Button">';
      $shipButtons .= '<div class="ShipRotationButtonContainer"><img class="ShipRotationButton ShipRotationButton-L" data-ship="' . $ship[0] . '"';
      $shipButtons .= ' data-rotation="l" id="Rotation-' . $ship[0];
      $shipButtons .= '" src="../img/Assets/Arrow.png" alt="Change Rotation"></div>';
      $shipButtons .= '<div class="ShipCounter" id="ShipCounter-' . $ship[0] . '"><p>' . $available . '</p></div>';
      $shipButtons .= '</div>';
      $index++;
    }
    return $shipButtons;
  }

  // Making the Middle Info Panel. If mode is "buttons" the menu Buttons are Created
  // If mode is "game" the game inputs are created
  function makeMiddleInfoPanel($playerHUD, $messageContents, $gameClass, $pos1, $pos2, $mode): string {
    $playerHUD .= '<div class="infoPanelMiddle" id="infoPanelMiddle" ';
    $playerHUD .= 'style="grid-column: ' . $pos1 . '; grid-row: ' . $pos2 . '; ';
    $playerHUD .= 'display: flex; flex-direction: column; justify-content: space-evenly; align-items: center;">';
    if ($mode == "buttons") {
      $buttons = ["Tutorial", "QuickPlaySolo", "QuickPlayMultiplayer", "CustomGame", "Credits"];
      $playerHUD = infoPanelButtons($playerHUD, $buttons);
    }
    if ($mode == "credits") {
      $playerHUD .= PayPalIntegration();
      $buttons = ["Back"];
      $playerHUD = infoPanelButtons($playerHUD, $buttons);
    }
    if ($mode == "customGame") {
      $buttons = ["Back"];
      $playerHUD = infoPanelButtons($playerHUD, $buttons);
    }
    if ($mode == "initial") {
      $playerHUD .= shipButtons($gameClass, $messageContents);
    }
    if ($mode == "inGame") {
      echo "game";
    }
    return $playerHUD . '</div>';
  }

  // Making the Bottom Info Panel
  function makeBottomInfoPanel($playerHUD, $pos1, $pos2, $color, $layer): string {
    $playerHUD .= '<div class="infoPanelBottom" id="infoPanelBottom" ';
    return infoPanelBoxStyle($playerHUD, $pos1, $pos2, $color, $layer);
  }

  // Making the LightBulb and the Turn Counter
  function makeOthers($playerHUD, $turnCounter, $turnCounterMax, $turnTimer, $lightBulbState): string {
    // LightBulb
    $playerHUD .= '<div class="lightBulb" id="LightBulb" ';
    $playerHUD .= 'style="grid-column: 1; grid-row: 1; display: flex; justify-content: center; align-items: center;">';
    if ($lightBulbState == -1) {
      $playerHUD .= '<img src="../img/Assets/LightBulb-Off.png" alt="LightBulb-Off" style="width: 40px;"></div>';
    } else if ($lightBulbState) {
      $playerHUD .= '<img src="../img/Assets/LightBulb-GREEN.png" alt="LightBulb-Green" style="width: 40px;"></div>';
    } else {
      $playerHUD .= '<img src="../img/Assets/LightBulb-RED.png" alt="LightBulb-RED" style="width: 40px;"></div>';
    }

    // Turn Counter
    $playerHUD .= '<div class="TurnCounter" id="TurnCounter" ';
    $playerHUD .= 'style="grid-column: 9 / 11; grid-row: 1; display: flex; justify-content: right; align-items: center;">';
    $playerHUD .= '<p style="margin: 0; padding-right: 10px; padding-left: 5px; font-size: x-large; overflow: hidden; white-space: nowrap">' . $turnCounter . ' / ' . $turnCounterMax . '</p></div>';
    // Turn Timer
    if (is_array($turnTimer)) {
      $turnTimer = $turnTimer["initial"];
    }
    if ($turnTimer != 0) {
      $playerHUD .= '<div class="TurnTimer" ';
      $playerHUD .= 'style="grid-column: 2 / 4; grid-row: 1; display: flex; justify-content: left; align-items: center;">';
      $time = ""; //sprintf("%d:%02d", floor($turnTimer / 60), ($turnTimer % 60)); Now it dosent show the turntime, indeed it shows nothing for a sec
      $playerHUD .= '<p id="TurnTimer" style="margin: 0; padding-right: 5px; padding-left: 10px; font-size: x-large; overflow: hidden; white-space: nowrap">' . $time . '</p></div>';
    }
    return $playerHUD;
  }

  function setInitialGameHUD($messageContents, $runningGames, $gamesAccess, $playerHUD, $lightBulbState = 1): string {
    $gameClass = $runningGames['game-' . $gamesAccess['access-' . $messageContents->client->resourceId]];
    $playerHUD = makeTopInfoPanel($playerHUD, "2 / 10", "2 / 7", "#39731f", true);
    $playerHUD = makeMiddleInfoPanel($playerHUD, $messageContents, $gameClass, "2 / 10", "7 / 19", "initial");
    $playerHUD = makeBottomInfoPanel($playerHUD, "2 / 10", "19 / 24", "#555555", true);
    $playerHUD = makeOthers($playerHUD, "0", "0", $gameClass->turnTime, $lightBulbState);
    return $playerHUD;
  }

  // Main Funktion which gets called by 'createMenu.php'
function makePlayerHUD($messageContents, $allShipsPlaced = 0) {
    global $gamesAccess;
    global $runningGames;

    // Creating the Base of the PlayerHUD
    $playerHUD = '<div style="width: 100%; height: 100%; display: grid; grid-template-columns: repeat(10, 1fr); grid-template-rows: repeat(24, 1fr); gap: 0">';

    // $messageContents either contains and Object or the words "Create" or "Credits" depending on where it's called from
    // For Creating the First menu when Joining the Site
    if ($messageContents == "Create") {
      $playerHUD = makeTopInfoPanel($playerHUD, "2 / 10", "2 / 7", "#39731f", false);
      $playerHUD = makeMiddleInfoPanel($playerHUD, $messageContents, "", "2 / 10", "7 / 19", "buttons");
      $playerHUD = makeBottomInfoPanel($playerHUD, "2 / 10", "19 / 24", "#555555", false);
      $playerHUD = makeOthers($playerHUD, " 0", "0", 0, -1);
      return $playerHUD . '</div>';
    }

    // For Creating the Credit Screen
    if ($messageContents == "Credits") {
      $playerHUD = makeTopInfoPanel($playerHUD, "2 / 10", "2 / 7", "#39731f", false);
      $playerHUD = makeMiddleInfoPanel($playerHUD, $messageContents, "", "2 / 10", "7 / 19", "credits");
      $playerHUD = makeBottomInfoPanel($playerHUD, "2 / 10", "19 / 24", "#555555", false);
      $playerHUD = makeOthers($playerHUD, "0", "0", 0, -1);
      return $playerHUD . '</div>';
    }


    // Main Game Logic
    switch ($messageContents->routing[1]) {
      case "Tutorial":
        /*tutorial();*/
        echo "Tutorial\n";
        break;
      case "QuickPlaySolo":
        break;
      case "CustomGame":
        if ($messageContents->otherData == "CustomGame") {
          echo "function -> makePlayerHUD , CustomGame\n";
          $playerHUD = makeTopInfoPanel($playerHUD, "2 / 10", "2 / 7", "#39731f", false);
          $playerHUD = makeMiddleInfoPanel($playerHUD, $messageContents, "", "2 / 10", "7 / 19", "customGame");
          $playerHUD = makeBottomInfoPanel($playerHUD, "2 / 10", "19 / 24", "#555555", false);
          $playerHUD = makeOthers($playerHUD, "0", "0", 0, -1);
          return $playerHUD . '</div>';
        }
        if ($messageContents->otherData == "CustomGame1") {
          echo "function -> makePlayerHUD , CustomGame1\n";

        }
        echo "CustomGame\n";
        if (str_contains($messageContents->otherData, "JoinCustomGame")) {
          echo "Creating Initial Game HUD";
          return setInitialGameHUD($messageContents, $runningGames, $gamesAccess, $playerHUD) . '</div>';
        }
        break;
      case "QuickPlayMultiplayer":
        // get GameClass
        return setInitialGameHUD($messageContents, $runningGames, $gamesAccess, $playerHUD) . '</div>';
      case "placement":
        $lightBulbState = ($allShipsPlaced) ? 0 : 1;
        return setInitialGameHUD($messageContents, $runningGames, $gamesAccess, $playerHUD, $lightBulbState) . '</div>';
      default:
        echo str_repeat("//", 20) . "\nError in 'PlayerHUD.php'->'switchRouting' = " . var_dump($messageContents->routing) . "\n" . str_repeat("//", 20) . "\n";
    }


  }

// Aufruf aus CreateMenu aus:
// makePlayerHUD("Create");






