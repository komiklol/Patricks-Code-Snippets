<?php
function createGame($client, $gameID, $gamePW) {

  // Extracting Client ID
  $clientID = $client->resourceId;

  // Creating an unique ID
  $uniqID = uniqid();

  $deny = true;
  if ($gameID == null) {
    $gameID = $uniqID;
    $deny = false;
  }

  // creating a new GameCLass with the name "game-`client ID`" z.b.: game-64
  ${'game-' . $uniqID} = new \MyApp\gameClass();
  // set the Game ID in the Class
  ${'game-' . $uniqID}->gameID = $gameID;
  ${'game-' . $uniqID}->uniqID = $uniqID;
  // set the Game PW in the Class
  if ($gamePW != "") {
    ${'game-' . $uniqID}->gamePW = $gamePW;
  }
  // set the Key in gamesAcces to z.b.: access-64 and the data is the uniqID
  $key = 'access-' . $clientID;
  addgamesAccess($key, $uniqID);
  if ($deny) {
    addgamesAccess($gameID, $uniqID);
  }

  // set the key to z.b.: game-64 and the data is the whole class
  $key = 'game-' . $uniqID;
  addRunningGame($key, ${'game-' . $uniqID});



}
