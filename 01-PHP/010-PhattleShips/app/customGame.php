<?php
  function customGame($messageContents) {

    /////////////////////////////////////////////////////////////////////////////////////////////
    //                              Enemy Field  / Title Screen                                //
    /////////////////////////////////////////////////////////////////////////////////////////////
    if ($messageContents->otherData == "CustomGame") {
      $enemyField = makePlayingField($messageContents, "Enemy");
    }
    if (str_contains($messageContents->otherData, "CustomGame1")) {

    }
    // Second Screen Create
    // Switch : AI or Multiplayer
    // if Multiplayer -> Set Lobby Password
    // if Multiplayer -> Generate Lobby ID

    /////////////////////////////////////////////////////////////////////////////////////////////
    //                           Friendly Field  / Silly Screen                                //
    /////////////////////////////////////////////////////////////////////////////////////////////
    if ($messageContents->otherData == "CustomGame") {
      $friendlyField = makePlayingField($messageContents, "Friendly");
    }
    if (str_contains($messageContents->otherData, "CustomGame1")) {

    }
    // Second Screen Create
    // Box 1 Enemy
    // Slider & input : Height of Field
    // Slider & input : Width of Field
    // Slider & input : Turns per Round
    // Ship List Checkable : Choose Ships
    // Counter : Amount of Ships

    // Box 2 Enemy
    // Switch : Island on/off
    // Checkable : Island Size Small/Medium/Big

    // Box 3 Friendly
    // Slider & input : Height of Field
    // Slider & input : Width of Field
    // Slider & input : Turns per Round
    // Ship List Checkable : Choose Ships
    // Counter : Amount of Ships

    // Box 4 Friendly
    // Switch : Island on/off
    // Checkable : Island Size Small/Medium/Big

    /////////////////////////////////////////////////////////////////////////////////////////////
    //                                PlayerHUD  / Menu                                        //
    /////////////////////////////////////////////////////////////////////////////////////////////
    if ($messageContents->otherData == "CustomGame") {
      $playerHUD = makePlayerHUD($messageContents);
    }

    if (str_contains($messageContents->otherData, "CustomGame1")) {





      $messageContents->tempData = '<test></test>';
      $playerHUD = makePlayerHUD($messageContents);
    }

    //Send Out///////////////////////////////////////////////////////////////////////////////////
    $type = "CustomGame PH EF FF";
    $messageContents->client->send(readyForSend($type, $playerHUD, $enemyField, $friendlyField, [38, 14], [38, 14], 0, "customGame69"));
  }






