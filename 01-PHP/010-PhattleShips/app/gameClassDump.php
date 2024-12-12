<?php
  function gameClassDump($gameClass) {
    var_dump($gameClass->uniqID);
    var_dump($gameClass->playerOneFriendlyField);
    var_dump($gameClass->playerOneEnemyField);
    var_dump($gameClass->playerOnePlayerHUD);
    var_dump($gameClass->playerOneLastCalled);
    var_dump($gameClass->playerTwoFriendlyField);
    var_dump($gameClass->playerTwoEnemyField);
    var_dump($gameClass->playerTwoPlayerHUD);
    var_dump($gameClass->playerTwoLastCalled);
    var_dump($gameClass->fieldSizeOne);
    var_dump($gameClass->fieldSizeTwo);
    var_dump($gameClass->collisionFieldOne);
    var_dump($gameClass->collisionFieldTwo);
    var_dump($gameClass->shipPositioningOne);
    var_dump($gameClass->shipPositioningTwo);
    var_dump($gameClass->islandFieldOne);
    var_dump($gameClass->islandFieldTwo);
    var_dump($gameClass->startDate);
    var_dump($gameClass->finishingDate);
    var_dump($gameClass->gameID);
    var_dump($gameClass->gamePW);
    var_dump($gameClass->gameMode);
//    var_dump($gameClass->player1);
//    var_dump($gameClass->player2);
    var_dump($gameClass->winner);
    var_dump($gameClass->playerShipsOne);
    var_dump($gameClass->playerShipsTwo);
    var_dump($gameClass->availableShips);
  }
