<?php



  function JoinGame($messageContents, $mode) {
    global $gamesAccess;
    global $runningGames;

    if ($mode == "JoinCustomGame") {
      // Get the PW and ID out of the $messageContents
      preg_match("/ID=(.*?)=ID/", $messageContents->otherData, $receivedGameID);
      preg_match("/PW=(.*?)=PW/", $messageContents->otherData, $receivedGamePW);
      $receivedGameID = $receivedGameID[1];
      $receivedGamePW = $receivedGamePW[1];
      if ($receivedGamePW == "") {
        $receivedGamePW = "AccessDeniedDueToEmptyPasswordInput9979";
      }
      if ($receivedGameID == "") {
        $receivedGameID = "DueToEmptyGameIdInputThe9979AccessIsDenied";
      }

      $gamePW = null;
      $gameID = null;

        if (isset($gamesAccess[$receivedGameID])) {
          if (isset($runningGames['game-' . $gamesAccess[$receivedGameID]])) {
            $gameID = $runningGames['game-' . $gamesAccess[$receivedGameID]]->gameID;
            $gamePW = $runningGames['game-' . $gamesAccess[$receivedGameID]]->gamePW;
          }
        }

      // If the Key of the Clients ID exists as game its true
//      if (array_key_exists(('access-' . $gameID), $gamesAccess)) {
      if ($gameID == $receivedGameID) {

        // Check if the Password is the right one
        if ($gamePW == $receivedGamePW) {

          // Add first ingame screen here
          echo "GAME IS STARTING TEST\n";


          $key = 'access-' . $messageContents->client->resourceId;
          addgamesAccess($key, $runningGames['game-' . $gamesAccess[$receivedGameID]]->uniqID);
          if (isset($runningGames['game-' . $gamesAccess[$receivedGameID]]->player1) && isset($runningGames['game-' . $gamesAccess[$receivedGameID]]->player2) ) {
            echo "--- Both Players are set already\n";
            return;
          } else {
            if (isset($runningGames['game-' . $gamesAccess[$receivedGameID]]->player1)) {
              $runningGames['game-' . $gamesAccess[$receivedGameID]]->player2 = $messageContents->client;
              echo "--- set $key - as player2\n";
            } else if (isset($runningGames['game-' . $gamesAccess[$receivedGameID]]->player2)) {
              $runningGames['game-' . $gamesAccess[$receivedGameID]]->player1 = $messageContents->client;
              echo "--- set $key - as player1\n";
            }
          }
          testGameplay($messageContents);

        } else {
          // Add Wrong Password Screen
          $messageContents->client->send(readyForSend("CustomGame AccessDenied-PW", "null", "null", "null", "null", "null", 0, "WrongPasswordScreen"));
        }
      } else {
        // Add Wrong Game ID Screen
        $messageContents->client->send(readyForSend("CustomGame AccessDenied-ID", "null", "null", "null", "null", "null", 0, "WrongGameIDScreen"));
      }
    }
  }
