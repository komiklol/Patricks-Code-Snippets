<?php

// Game settings:
function testGame($messageContents) {
  global $gamesAccess;
  global $runningGames;

  $version = "0";
  while (isset($gamesAccess["testGame" . $version])) {
    $version++;
  }

  createGame($messageContents->client, "testGame" . $version, "test");

  // get GameClass
  $gameClass = $runningGames['game-' . $gamesAccess['access-' . $messageContents->client->resourceId]];

  $clientID = $messageContents->client->resourceId;

  // Player 1. Ships that can be used.
  $playerShipsOne = [["AircraftCarrier", 2], ["BattleCarrier", 1]];
  // Player 2. Ships that can be used.
  $playerShipsTwo = [["BattleCarrier", 1], ["AircraftCarrier", 1]];
  // Total Amount of Ships each player uses.
  $playerShipsTotalCount = [["AircraftCarrier" => 2, "BattleCarrier" => 1], ["BattleCarrier" => 1, "AircraftCarrier", 1]];
  // Ships that can be used in that game.
  $availableShips[0] = ["AircraftCarrier", "BattleCarrier"];
  $availableShips[1] = ["AircraftCarrier" => 8, "BattleCarrier" => 7];

  $gameClass->player1 = $messageContents->client;
  $gameClass->playerShipsTotalCount = $playerShipsTotalCount;
  $gameClass->playerShipsOne = $playerShipsOne;
  $gameClass->playerShipsTwo = $playerShipsTwo;
  $gameClass->availableShips = $availableShips;
  $gameClass->gameMode = "Test-Game";
  $gameClass->turnTime = ["initial" => 20, "move" => 90]; // initial, move | initial = on Placing Ships, move = when the Players turn for Action
  $gameClass->playerOneTurns = [0, 1];
  $gameClass->playerTwoTurns = [0, 2];
  $gameClass->fieldSizeOne = [26, 14]; // Rows / Columns
  $gameClass->fieldSizeTwo = [14, 5];  // Rows / Columns
  $gameClass->maxAircraft = ["Spy-Plane" => 2, "Attack-Plane" => 5, "Helicopter" => 3];

  testGameplay($messageContents, $gameClass);
}
function testGameplay($messageContents) {
  global $gamesAccess;
  global $runningGames;
  $gameClass = $runningGames['game-' . $gamesAccess['access-' . $messageContents->client->resourceId]];

  if (!playerchecker($messageContents, $gameClass)) {
    $gameClass->player2 = $messageContents->client;
  }


/////////////////////////////////////////////////////////////////////////////////////////////
//                                PlayerHUD  / Ship Placing /                              //
/////////////////////////////////////////////////////////////////////////////////////////////
  $playerHUD = makePlayerHUD($messageContents);
  if (playerchecker($messageContents, $gameClass)) {
    $gameClass->playerOnePlayerHUD = $playerHUD;
//    echo "playerHUD player1 created\n";
  } else {
    $gameClass->playerTwoPlayerHUD = $playerHUD;
//    echo "playerHUD player2 created\n";
  }

/////////////////////////////////////////////////////////////////////////////////////////////
//                                PlayingFields  / Ship Placing /                          //
/////////////////////////////////////////////////////////////////////////////////////////////
  list($enemyField, $friendlyField) = makePlayingField($messageContents, "placing");
  if (playerchecker($messageContents, $gameClass)) {
    $gameClass->playerOneFriendlyField = $friendlyField;
    $gameClass->playerOneEnemyField = $enemyField;
//    echo "playingField player1 created\n";
  } else {
    $gameClass->playerTwoFriendlyField = $friendlyField;
    $gameClass->playerTwoEnemyField = $enemyField;
//    echo "playingField player2 created\n";
  }


/////////////////////////////////////////////////////////////////////////////////////////////
//                              Other Functions  / Ship Placing /                          //
/////////////////////////////////////////////////////////////////////////////////////////////




  $type = "QuickPlayMultiplayer InitialOn Ingame Test PH FF EF SetTimer";
//  echo "QuickPlaySolo InitialOn Ingame Test PH FF EF\n";
//  echo "/// Type\n $type\n";
//  echo "/// PlayerHUD\n $playerHUD\n";
//  echo "/// EnemyField\n $enemyField\n";
//  echo "/// FriendlyField\n $friendlyField\n";
//  echo "/// playerShipsOne\n";
//  var_dump($gameClass->playerShipsOne);
//  echo "/// availableShips\n";
//  var_dump($gameClass->availableShips);
  $messageContents->client->send(readyForSend($type, $playerHUD, $enemyField, $friendlyField, $gameClass->fieldSizeOne, $gameClass->fieldSizeTwo, $gameClass->turnTime, "null"));



  // for ingame routing
//  preg_match("/GID==(.*?)==GID/", $messageContents->routing, $gameID);




}


