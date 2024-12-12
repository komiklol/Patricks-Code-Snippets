<?php

namespace MyApp;

class AircraftCarrier extends phattleshipsShips {
    // Attribute
    protected mixed $attackPlanes;
    protected mixed $spyPlanes;
    protected mixed $helicopters;
    protected mixed $antiAir;

    // construct, add function generate guns
    public function __construct($shipNameNr, $positioning, $playerOwner) {
        parent::__construct("AircraftCarrier", $shipNameNr, 8, 100, 10, 800, 15, 0, $positioning, $playerOwner);
        // Helicopters, SpyPlanes, AttackPlanes
        $aircraftCount = [6, 5, 55];
        $aircraftData = [[
            // Array 0 || Helicopter
            "name" => "OceanBird", //temp name
            "hp" => 14,
            "dmg" => [20, 35],
            "repair" => 50,
            "usablePR" => 2,
            "cd" => 0,
        ], [
            // Array 1 || Spy Plane
            "name" => "ARF-44 Phantom",
            "hp" => 10,
            "spyRadius" => 2,
            "usablePR" => 1,
            "cd" => 0,
        ], [
            // Array 2 || Attack Plane
            "name" => "F-16", //temp name
            "hp" => 6,
            "dmg" => [14, 25],
            "bombRadius" => 2,
            "splashDMGMultiplier" => 50, // in %
            "usablePR" => 8,
            "cd" => 0,
        ]];
        $aircraftsArray = [[], [], []];
        for ($j = 0; $j < count($aircraftCount); $j++) {
            for ($i = 0; $i < $aircraftCount[$j]; $i++) {
                $aircraftsArray[$j][] = $aircraftData[$j];
            }
        }
        list($this->helicopters, $this->spyPlanes, $this->attackPlanes) = $this->generateAircraft($playerOwner, $aircraftsArray[0], $aircraftsArray[1], $aircraftsArray[2]);

        // Generate the Anti Air with stats: 12 AAs, 85% Accuracy, 3-5 Dmg, 3 Shots per AA
        $this->antiAir = $this->generateAA(12, 85, [3, 5], 3);

        // Adding Actions

        $aircrafts = ["spyPlanes", "attackPlanes", "helicopters"];
        list($attackPlanes, $spyPlanes, $helicopters) = $this->countAircrafts($aircrafts);
        $spyplaneAction = ["Spy-Plane", $spyPlanes[0], $spyPlanes[1], $spyPlanes[2]];
        $attackplaneAction = ["Attack-Plane", $attackPlanes[0], $attackPlanes[1], $attackPlanes[2]];
        $helicopterAction = ["Helicopter", $helicopters[0], $helicopters[1], $helicopters[2]];
        $this->actions = [$spyplaneAction, $attackplaneAction, $helicopterAction];
    }

    public function getAttackPlanesCount(): int {
        return count($this->attackPlanes);
    }
  public function getSpyPlanesCount(): int {
        return count($this->spyPlanes);
    }
  public function getHelicoptersCount(): int {
        return count($this->helicopters);
    }


}
