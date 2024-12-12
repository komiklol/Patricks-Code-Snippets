<?php
  function switchGame($messageContents): void {
//    echo "## Switch Game ##\n";

    switch ($messageContents->routing[1]) {
      case "placement":
        gamePlacement($messageContents);

        break;
      case "war":
        inGame($messageContents);
        break;

      case "timeup";
          playerReady($messageContents, true);
        break;

      default:
        echo str_repeat("//", 20) . "\nError in 'switchGame'->'switchGame' = " . var_dump($messageContents->routing) . "\n" . str_repeat("//", 20) . "\n";
    }

  }
