<?php

namespace MyApp;

class BattleCarrier extends phattleshipsShips {
  // Attribute
  protected mixed $helicopters;
  public mixed $ballisticMissile;
  protected mixed $antiAir;

  public function __construct($shipNameNr, $positioning, $playerOwner) {
    $ballisticMissile = new ballisticMissile(3, 8, [95, 110], 4, 1.25);
    $this->ballisticMissile = $ballisticMissile;
    parent::__construct("BattleCarrier", $shipNameNr, 7, 115, 12, 805, 11, $ballisticMissile, $positioning, $playerOwner);

    $helicopterAmount = 25;
    $helicopterData = [
        "name" => "Altov 37",
        "hp" => 16,
        "dmg" => [30, 33],
        "repair" => 62,
        "usablePR" => 5,
        "cd" => 1,
    ];
    $helicopterArray = [];
    for ($i = 0; $i < $helicopterAmount; $i++) {
        $helicopterArray[] = $helicopterData;
    }
    list($this->helicopters, $null1, $null2) = $this->generateAircraft($playerOwner, $helicopterArray);
    unset($null1, $null2);

    // Generate the Anti Air with stats: 8 AAs, 90% Accuracy, 3-6 Dmg, 3 Shots per AA
    $this->antiAir = $this->generateAA(8, 90, [3, 6], 3);

    $aircrafts = ["helicopters"];
    list($attackPlanes, $spyPlanes, $helicopters) = $this->countAircrafts($aircrafts);
    $helicopterAction = ["Helicopter", $helicopters[0], $helicopters[1], $helicopters[2]];
    $ballisticMissileAction = $ballisticMissile->getAction();
    $this->actions = [$helicopterAction, $ballisticMissileAction];
  }

  public function getHelicoptersCount(): int {
    return count($this->helicopters);
  }


}
