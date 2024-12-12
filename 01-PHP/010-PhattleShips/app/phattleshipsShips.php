<?php

namespace MyApp;

// Ships can be saved in GameClass->playerShipsOne/Two

class phattleshipsShips {
  // PR = Per Round / PL = Per Length / LvL = Level
  protected string $shipName;
  protected string $shipNameNr;
  protected string $playerOwner; // "One" or "Two"
  protected int $shipLength;
  protected int $hitPointsPL;
  protected array $currentHitPointsPL;
  protected int $sinkingLvL;
  protected int $hitPointsTotal;
  protected int $currentHitPointsTotal;
  protected int $repairPRPL;
  protected mixed $weapons;
  protected array $actions;
  protected array $positioning; // saved as [row, column, "rotation"]

  public function __construct($shipName, $shipNameNr, $shipLength, $hitPointsPL, $sinkingLvL, $hitPointsTotal, $repairPRPL, $weapons, $positioning, $playerOwner) {
   $this->shipName = $shipName;
   $this->shipNameNr = $shipNameNr;
   $this->shipLength = $shipLength;
   $this->hitPointsPL = $hitPointsPL;
   $this->sinkingLvL = $sinkingLvL;
   $this->hitPointsTotal = $hitPointsTotal;
   $this->repairPRPL = $repairPRPL;
   $this->weapons = $weapons;
   $this->positioning = $positioning;
   for ($i = 0; $i < $shipLength; $i++) {
     $this->currentHitPointsPL[$i] = $hitPointsPL;
   }
   $this->currentHitPointsTotal = $hitPointsPL * $shipLength;
   $this->playerOwner = $playerOwner;
  }

  public function getShipName(): string {
    return $this->shipName;
  }
  public function getShipNameNr(): string {
    return $this->shipNameNr;
  }
  public function getPlayerOwner(): string {
    return $this->playerOwner;
  }
  public function getCellPos(): array {
    return $this->positioning;
  }
  public function getActions(): array {
    return $this->actions;
  }
  public function getShipLength(): int {
    return $this->shipLength;
  }
  public function getHitPointsPL(): int {
    return $this->hitPointsPL;
  }
  public function getCurrentHitPointsPL(): array {
    return $this->currentHitPointsPL;
  }
  public function getHitPointsTotal(): int {
    return $this->hitPointsTotal;
  }
  public function getSinkingLvL(): int {
    return $this->sinkingLvL;
  }
  public function getRepairPRPL(): int {
    return $this->repairPRPL;
  }

  protected function countAircrafts($aircrafts): array {
            //          ["spyPlanes", "attackPlanes", "helicopters"]
    $attackPlanesAction = [];
    $spyPlanesAction = [];
    $helicoptersAction = [];

    foreach ($aircrafts as $aircraft) {
      $availableCount = 0;
      $aircraftCount = count($this->$aircraft);

      for ($i = 0; $i < $aircraftCount; $i++) {
        //   $this->helicopters[$i]
        if (!$this->$aircraft[$i]->getCoolDown()) {
          $availableCount++;
        }
      }
      $coolDown = 0;
      if (!$availableCount) {
        for ($i = 0; $i < $aircraftCount; $i++) {
          if ($this->$aircraft[$i]->getCoolDown() == 1) {
            $coolDown = 1;
            break;
          }
          $coolDown = 2;
        }
      }
      ${$aircraft . "Action"} = [$availableCount, $aircraftCount, $coolDown];
    }
    return array($attackPlanesAction, $spyPlanesAction, $helicoptersAction);
  }

  protected function generateAA($aaAmount, $accuracy, $damage, $shots): array {
    $antiAirArray = [];
    for ($i = 0; $i < $aaAmount; $i++) {
      $antiAirArray[] = new antiAir($accuracy, $damage, $shots);
    }
    return $antiAirArray;
  }

  // Arrays like this:
  // $helicopters = [
  // "name" => "apache",
  // "hp" => 15,
  // "dmg" => [min, max],
  // "repair" => 25,
  // "usablePR" => 2,
  // "cd" => 1,
  // ]
  protected function generateAircraft($player, $helicopters = 0, $spyPlanes = 0, $attackPlanes = 0): array {
    $attackPlaneArray = [];
    $spyPlaneArray = [];
    $helicopterArray = [];
    if ($helicopters != 0) {
      foreach ($helicopters as $heli) {
        $name = $heli["name"];
        $hp = $heli["hp"];
        $dmg = $heli["dmg"];
        $repair = $heli["repair"];
        $upr = $heli["usablePR"];
        $cd = $heli["cd"];
        $helicopterArray[] = new Helicopter($name, $hp, $dmg, $repair, $upr, $cd, $this->shipNameNr, $player);
      }
    }
    if ($spyPlanes != 0) {
      foreach ($spyPlanes as $plane) {
        $name = $plane["name"];
        $hp = $plane["hp"];
        $sr = $plane["spyRadius"];
        $upr = $plane["usablePR"];
        $cd = $plane["cd"];
        $spyPlaneArray[] = new SpyPlane($name, $hp, $sr, $upr, $cd, $this->shipNameNr, $player);
      }
    }
    if ($attackPlanes != 0) {
      foreach ($attackPlanes as $plane) {
        $name = $plane["name"];
        $hp = $plane["hp"];
        $dmg = $plane["dmg"];
        $br = $plane["bombRadius"];
        $sdmgm = $plane["splashDMGMultiplier"];
        $upr = $plane["usablePR"];
        $cd = $plane["cd"];
        $attackPlaneArray[] = new AttackPlane($name, $hp, $dmg, $br, $sdmgm, $upr, $cd, $this->shipNameNr, $player);
      }
    }
    return array($helicopterArray, $spyPlaneArray, $attackPlaneArray);
  }

  public function getReadyPlanes($plane, $amount): array {
    $correctPlaneName = ["Spy-Plane" => "spyPlanes", "Attack-Plane" => "attackPlanes", "Helicopter" => "helicopters"];
    $counter = 0;
    $readyPlanes = [];
    for ($i = 0; $i < count($this->{$correctPlaneName[$plane]}); $i++) {
      if (!$this->{$correctPlaneName[$plane]}[$i]->getCoolDown()) {
        $counter++;
        $this->{$correctPlaneName[$plane]}[$i]->setInUse();
        $this->{$correctPlaneName[$plane]}[$i]->setCoolDown(3);
        $this->{$correctPlaneName[$plane]}[$i]->setStatus("attacking");
        $readyPlanes[] = $this->{$correctPlaneName[$plane]}[$i];
        unset($this->{$correctPlaneName[$plane]}[$i]);
        $this->{$correctPlaneName[$plane]} = array_values($this->{$correctPlaneName[$plane]});
      }
      if ($counter == $amount) {
        return $readyPlanes;
      }
    }
    return $readyPlanes;
  }
  public function addHomeComingAircrafts($name, $aircraft): void {
    $this->$name[] = $aircraft;
  }
}
