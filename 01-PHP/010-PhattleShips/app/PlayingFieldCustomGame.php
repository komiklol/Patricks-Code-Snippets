<?php


  function makePlayingFieldCustomGameScreenZero($fieldSide, $otherData) {
    $size = [38, 14];
    $playfieldStyle1 = 'style="width: 100%; height: 100%; display: grid; grid-template-columns: repeat(';
    $playfieldStyle2 = ', 1fr); grid-template-rows: repeat(';
    $playfieldStyle3 = ', 1fr); gap: 0;">';
    $bigImageStyle = 'style="width: 100%; object-fit: scale-down; object-position: center;"';

    if ($fieldSide == "Enemy") {
      // Base Grid
      $field = '<div ' . $playfieldStyle1 . $size[0] . $playfieldStyle2 . $size[1] . $playfieldStyle3;
      // Custom Game Sign
      $field .= '<div style="grid-column: 2 / 15; grid-row: 2 / 7; display: flex; flex-direction: column; justify-content: start;">';
      $field .= '<img src="../img/Assets/CustomGame.png" alt="Custom Game" style="width: 150%; ; object-fit: scale-down; object-position: center;">';
      $field .= '<img src="../img/Assets/CurrentlyIn.png" alt="Currently In" style="width: 43%; ; object-fit: scale-down; object-position: center; margin-left: 35%;">';
      $field .= '</div>';
      // Title Sign
      $field .= '<div style="grid-column: 25 / 38; grid-row: 2 / 7;">';
      $field .= '<img src="../img/Assets/PhattleShips-by_komiklool.png" alt="TitleScreen" ' . $bigImageStyle . '>';
      $field .= '</div>';
      // Create Custom Game Button
      $field .= '<div style="grid-column: 13 / 27; grid-row: 8 / 14;">';
      $field .= '<div class="playerHUDButton" id="CreateCustomGame" ' . setButton(("menu CustomGame"), "CustomGame1") . '>';
      $field .= '<img src="../img/Assets/CreateCustomGame.png" alt="Create Custom Game Button" style="width: 100%"></div>';
      $field .= '</div>';
      return $field . '</div>';
    }
    if ($fieldSide == "Friendly") {
      // Base Grid
      $field = '<div ' . $playfieldStyle1 . $size[0] . $playfieldStyle2 . $size[1] . $playfieldStyle3;
      // Box Element
      $field .= '<div class="BoxElement" style="grid-row: 5 / 11; grid-column: 9 / 31; display: flex; flex-direction: row; justify-content: space-evenly; align-content: center" >';
      // Openning a new div for Placement in BoxElement
      $field .= '<div class="TopSecretContainer">';
      if ($otherData == "normal") {
        // Game ID Element
        $field .= '<div class="TopSecret"">';
      }
      if ($otherData == "denied") {
        // Game ID Element
        $field .= '<div class="TopSecretDenied"">';

      }
      $field .= '<input type="password" id="GameID" placeholder="xxx-xxxxx">';
      $field .= '<img id="TopSecretImgID" data-state="on" src="../img/Assets/TopSecretOn.png" alt="Top Secret Button">';
      $field .= '</div>';
      $field .= '<div class="TSGameID"><p class="TSGameIDText" id="TSGameIDText" style="padding: 2px;">Enter The Game ID Here</p></div>';
      // Game Password Element
      $field .= '<div class="TSGamePW"><p class="TSGamePWText" id="TSGamePWText" style="padding: 2px;">Enter The Game Password Here</p></div>';
      $field .= '<div class="TopSecret"">';
      $field .= '<input type="password" id="GamePW" placeholder="xxxxx">';
      $field .= '<img id="TopSecretImgPW" data-state="on" src="../img/Assets/TopSecretOn.png" alt="Top Secret Button">';
      $field .= '</div>';
      // Closing the div TopSecretContainer
      $field .= '</div>';
      // Creating the Join Game Button
      $field .= '<div class="playerHUDButton" id="JoinGame" style="width: 30%; height: 70%; margin-right: 2%;" ' . setButton(("menu CustomGame Initial"), "JoinCustomGame") . '>';
      $field .= '<img src="../img/Assets/JoinGameS.png" alt="Join Game Button" style="width: 80%"></div>';
      $field .= '</div>';
      return $field . '</div></div>';
    }




    return ;
  }


