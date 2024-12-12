<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
require 'messageContents.php';
require 'createMenu.php';
require 'switchMenu.php';
require 'readyForSend.php';
require 'PlayerHUD.php';
require 'Credits.php';
require 'globalfunctions.php';
require 'PlayingField.php';
require 'PlayingFieldCustomGame.php';
require 'customGame.php';
require 'JoinCustomGame.php';
require 'testGame.php';
require 'CreateGame.php';
require 'ShipCollisionHandler.php';
require 'gamePlacement.php';
require 'switchGame.php';
require 'fileDump.php';
require 'runningGame.php';
require 'runningGamePlayerHUD.php';
require 'runningPlayingField.php';
require 'queFunctions.php';
require 'queFunctionsVisibles.php';

class socket implements MessageComponentInterface {

  protected \SplObjectStorage $clients;
  public array $games;
  public function __construct() {
    $this->clients = new \SplObjectStorage;
    $this->games = [];

    $controlUnit = [];
    addRunningGame("controlUnit", $controlUnit);
  }

  public function onOpen(ConnectionInterface $conn): void {
    // Store the new connection in $this->clients
    $this->clients->attach($conn);
    echo "New connection! ({$conn->resourceId})\n";

    messageChecker($conn->resourceId, "Join");

    createMenu($conn);


//    echo "///// Game ID /////////////////////////////////////////\n";
//    $ID = 'game-' . $gamesAccess['access-' . $conn->resourceId];
//    echo "GameID: " . $ID . "\n\n";
//    echo "GameID aus gameClass: " . $runningGames[$ID]->gameID . "\n";
//    echo "///////////////////////////////////////////////////////\n";

  }
  public function onMessage(ConnectionInterface $from,  $msg): void {
      // Use echos for Console Logs
    echo "Message Income! $msg \n";

    global $messageCheck;
    if ($msg == $messageCheck[$from->resourceId]) {
      echo "Same Message. Cancel Request\n";
      return;
    }
    messageChecker($from->resourceId, $msg);
//    echo "this shouldnt be twice\n";

    // TODO Cut the Clients Unnecessary things out:
//    FileDump($from, "socket67-from.txt");

    $explodeArray = [];
    $searchingPattern = ["/fd=(.*?)=fd/", "/cl=(.*?)=cl/", "/rw=(.*?)=rw/", "/sp=(.*?)=sp/", "/rt=(.*?)=rt/", "/od=(.*?)=od/", "/sr=(.*?)=sr/", "/ac=(.*?)=ac/", "/am=(.*?)=am/"];
    for ($i = 0; $i < 9; $i++) {
      if (preg_match($searchingPattern[$i], $msg, $match)) {
        $explodeArray[] = $match[1];
      } else {
        $explodeArray[] = "null";
      }
    }
    $messageContents = new messageContents();
    $messageContents->routing = explode(' ', $explodeArray[4]);
    $messageContents->client = $from;
    $messageContents->cellField = $explodeArray[0];
    $messageContents->cellColumn = $explodeArray[1];
    $messageContents->cellRow = $explodeArray[2];
    $messageContents->ship = $explodeArray[3];
    $messageContents->otherData = $explodeArray[5];
    $messageContents->shipRotation = $explodeArray[6];
    $messageContents->attack[0] = $explodeArray[7];
    $messageContents->attack[1] = $explodeArray[8];

//      var_dump($messageContents->routing);
////      var_dump($messageContents->client);
//      var_dump($messageContents->cellField);
//      var_dump($messageContents->cellColumn);
//      var_dump($messageContents->cellRow);
//      var_dump($messageContents->ship);
//      var_dump($messageContents->otherData);
//      var_dump($messageContents->shipRotation);


    switch ($messageContents->routing[0]) {
      case "menu":
        switchMenu($messageContents);
        break;
      case "game":
        switchGame($messageContents);
        break;
      default:
        str_repeat("//", 20) . "\nError in 'onMessage'->'switch' = " . var_dump($messageContents->routing) . "\n\n" . str_repeat("//", 20) . "\n";
        break;
    }

  }
  public function onClose(ConnectionInterface $conn): void {
      echo "Closing Connection with ({$conn->resourceId})\n";
      $conn->close();
  }
  public function onError(ConnectionInterface $conn, \Exception $e): void {
      echo "Error!\n";
      var_dump($conn);
      var_dump($e);
  }
}
