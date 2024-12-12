<?php

namespace MyApp;

class AttackPlane extends Aircrafts {

  protected array $damage; // [min, max]
  protected int $bombRadius;
  protected int $splashDMGMultiplier;

  public function __construct($name, $hitPoints, $damage, $bombRadius, $splashDMGMultiplier, $usablePR, $coolDown, $motherShip, $player) {
    parent::__construct($name, $hitPoints, $usablePR, $coolDown, $motherShip, $player);
    $this->damage = $damage;
    $this->bombRadius = $bombRadius;
    $this->splashDMGMultiplier = $splashDMGMultiplier;
  }
  public function getPlaneType(): string {
    return "Attack-Plane";
  }
  public function getImageName(): string {
    return "Attack-Plane";
  }

}
