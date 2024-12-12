<?php

namespace MyApp;

class gameClass {
  // Saves the uniqID by wich the game is saved and found
  public mixed $uniqID;
  // Containing the HTML
  public mixed $playerOneFriendlyField;
  public mixed $playerOneEnemyField;
  public mixed $playerOnePlayerHUD;
  // Containing Amount of Turns [Current, Max]
  public array $playerOneTurns;
  // || NOT USED || Containing the Last sendet HTML Elements : [FF, EF, PH]
  public mixed $playerOneLastCalled;
  // Containing the HTML
  public mixed $playerTwoFriendlyField;
  public mixed $playerTwoEnemyField;
  public mixed $playerTwoPlayerHUD;
  // Containing Amount of Turns [Current, Max]
  public array $playerTwoTurns;
  // || NOT USED || Containing the Last sendet HTML Elements : [FF, EF, PH]
  public mixed $playerTwoLastCalled;
  // Field Sizes [Rows, Columns]
  public mixed $fieldSizeOne;
  public mixed $fieldSizeTwo;
  // Collision Fields [Ship0[Vertical(int), Horizontal(int)], Ship1[Vertical(int), Horizontal(int)],...]
  public mixed $collisionFieldOne;
  public mixed $collisionFieldTwo;
  // Positioning of Ships Containing whole HTML/CSS String Elements of all Ship-Tiles
  public mixed $shipPositioningOne;
  public mixed $shipPositioningTwo;
  // Store the HTML of the QueFunctionsVisibles [attack(), homerun()]
  public mixed $queFuncVisibleOne;
  public mixed $queFuncVisibleTwo;
  // Store the HTML for the Enemy Play Field
  public mixed $enemyShipPositioningOne;
  public mixed $enemyShipPositioningTwo;
  // Stores Playing Field numbers as key to the Ship on that position
  public mixed $enemyShipPositionArrayOne;
  public mixed $enemyShipPositionArrayTwo;
  // Positioning of Ships Containing whole HTML/CSS String Elements of all Ship-Tiles for Enemy if discovered
  public mixed $shipPositioningFoundOne;
  public mixed $shipPositioningFoundTwo;
  // || NOT USED || Placeholder for future planned Island, should contain the Collision-field
  public mixed $islandFieldOne;
  public mixed $islandFieldTwo;
  // Date of Creating the Game and Finish saved like "Year-Mo-Da Ho:Mi:Se"
  public string $startDate;
  public string $finishingDate;
  // The gameID is set by creating the Game either Automatic or by Custom-Game Custom
  public mixed $gameID;
  // Game Password for Joining
  public mixed $gamePW;
  // Contains the GameMode wich is currently played
  public string $gameMode;
  // The time in Seconds each Player has per round for his moves 0 = inf, ["initial" => a1, "move" => a1]
  public mixed $turnTime;
  // Contains the whole Client Ratchet is sending
  public mixed $player1;
  public mixed $player2;
  // Contains the Winner at the End
  public string $winner;
  // Contains Array of all Available Ships in the Game [Player1[Ship=>Amount, ...], Player2[Ship=>Amount, ...]]
  public array $playerShipsTotalCount;
  // The Ships the Players have Left to place
  public array $playerShipsOne;
  public array $playerShipsTwo;
  // Used for Storing the Ship-Classes
  public array $playerShipsClassesOne;
  public array $playerShipsClassesTwo;
  // Stores the Pre Made HTML/CSS created by the QUE-Tasks
  public object $queTaskHTML;
  // Contains the Available Ships with they're corresponding Length also used for finding the Ships and iterating
  public array $availableShips;
  // max Usable Aircraft ["Spy-Plane" => int, "Attack-Plane" => int, "Helicopter" => int]
  public array $maxAircraft;
  // Contains the Ready State of the Players 1 - Player 1 | 2 - Player 2 | 3 - Both Players
  public int $playersReady;
  // Which player is on Move 0 = No Player, 1 = Player 1, 2 = Player 2
  public int $playerTurn;
  // Array of Arrays for the Que of Tasks [[Que1], [Que2], [etc]]
  public array $taskQue;

  public function __construct() {
    $this->startDate = date("Y-m-d H:i:s");
    // If Time is not Changed by Initializing Game, 0 Represent no Timer
    $this->turnTime = 0;
    // If 0, no Player is ready
    $this->playersReady = 0;
    $this->shipPositioningOne = "";
    $this->shipPositioningTwo = "";
    // If -1 the counter isn't shown
    $this->playerOneTurns = [-1];
    $this->playerTwoTurns = [-1];
    // if 999 its infinite
    $this->maxAircraft = ["Spy-Plane" => 999, "Attack-Plane" => 999, "Helicopter" => 999];
    $this->playerTurn = 0;
    $this->playerShipsClassesOne = ["0" => 0];
    $this->playerShipsClassesTwo = ["0" => 0];
    $this->enemyShipPositioningOne = "";
    $this->enemyShipPositioningTwo = "";
    $this->queTaskHTML = new queTaskHTMLElements();
    $this->queFuncVisibleOne = ["enemy" => "", "friendly" => ""];
    $this->queFuncVisibleTwo = ["enemy" => "", "friendly" => ""];
  }
}
