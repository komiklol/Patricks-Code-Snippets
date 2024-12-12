<?php
  function addRunningGame($key, $runningGame): void {
    global $runningGames;
    $runningGames[$key] = $runningGame;
  }
  function addgamesAccess($key, $data): void {
    global $gamesAccess;
    $gamesAccess[$key] = $data;
  }
  function messageChecker($client, $payload): void {
    global $messageCheck;
    $messageCheck[$client] = $payload;
  }

  // Use global in every funtion who needs the Varibale
//global = $runningGames

  // Use following to add new Games to the Array:
//addRunningGame("Game");
