<?php

namespace MyApp;

class Helicopter extends Aircrafts {

  protected array $damage; // [min, max]
  protected int $repair; // int in %
  protected mixed $mission; // "attack" or "repair"

  public function __construct($name, $hitPoints, $damage, $repair, $usablePR, $coolDown, $motherShip, $player) {
    parent::__construct($name, $hitPoints, $usablePR, $coolDown, $motherShip, $player);
    $this->damage = $damage;
    $this->repair = $repair;
    $this->mission = "none";
  }
  public function getPlaneType(): string {
    return "Helicopter";
  }
  public function getImageName(): string {
    return "Helicopter";
  }
  public function getMission() {
    return $this->mission;
  }
  // mission, "repair", "none", "attack"
  public function setMission($mission): void {
    $this->mission = $mission;
  }
}
