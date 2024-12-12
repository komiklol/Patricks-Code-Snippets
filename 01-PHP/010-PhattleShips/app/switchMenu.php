<?php
  function switchMenu($messageContents): void {
    if (count($messageContents->routing) == 1) {
      echo " 'switchMenu.php' -> This is Only for Testing Purpose!\n";
      return;
    }
    switch ($messageContents->routing[1]) {
      case "Tutorial":
        /*tutorial();*/
        echo "Tutorial\n";
        break;
      case "QuickPlaySolo":
        /*quickPlaySolo($messageContents);*/
        echo "QuickPlaySolo\n";
        break;
      case "QuickPlayMultiplayer":
        $messageContents->routing[2] = "Initial";
        testGame($messageContents);
        /*quickPlayMultiplayer($messageContents);*/
        echo "QuickPlayMultiplayer\n";
        break;
      case "CustomGame":
        // Routing to 'JoinCustomGame.php'
        if (str_contains($messageContents->otherData, "JoinCustomGame")) {
          JoinGame($messageContents, "JoinCustomGame");
//          echo "///// Routing in 'switchMenu.php' to 'JoinCustomGame.php' ////////////\n";
//          echo "Joining Custom Game\n";
//          echo "//////////////////////////////////////////////////////////////////////\n";
          break;
        }
        // Routing to 'customGame.php'
        customGame($messageContents);
//        echo "///// Routing in 'switchMenu.php' to 'customGame.php' ////////////////\n";
//        echo "customGame\n";
//        echo "//////////////////////////////////////////////////////////////////////\n";
        break;
      case "Credits":
        credits($messageContents);
        echo "Credits\n";
        break;
      case "Back":
        createMenu($messageContents->client);
        break;
      default:
        echo str_repeat("//", 20) . "\nError in 'switchMenu'->'switchMenu' = " . var_dump($messageContents->routing) . "\n" . str_repeat("//", 20) . "\n";
    }

  }
