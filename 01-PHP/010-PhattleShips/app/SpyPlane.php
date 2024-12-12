<?php

namespace MyApp;

class SpyPlane extends Aircrafts {

  protected int $spyRadius;

  public function __construct($name, $hitPoints, $spyRadius, $usablePR, $coolDown, $motherShip, $player) {
    parent::__construct($name, $hitPoints, $usablePR, $coolDown, $motherShip, $player);
    $this->spyRadius = $spyRadius;
  }
  public function getPlaneType(): string {
    return "Spy-Plane";
  }
  public function getImageName(): string {
    return "Spy-Plane";
  }
}
