<?php

namespace MyApp;

class Aircrafts {
  protected string $name;
  protected string $playerOwner; // "One" or "Two"
  protected mixed $inUse;
  protected int $hitPoints;
  protected int $currentHitPoints;
  protected int $usablePR;
  protected int $coolDown;
  protected mixed $status;
  protected string $motherShip;
  protected mixed $targetPos;

  public function __construct($name, $hitPoints, $usablePR, $coolDown, $motherShip, $playerOwner) {
    $this->name = $name;
    $this->hitPoints = $hitPoints;
    $this->usablePR = $usablePR;
    $this->coolDown = $coolDown;
    $this->inUse = false;
    $this->currentHitPoints = $hitPoints;
    $this->status = "parked";
    $this->motherShip = $motherShip;
    $this->playerOwner = $playerOwner;
    $this->targetPos = []; // Row, Column
  }
  public function getCoolDown(): int {
    return $this->coolDown;
  }
  public function getPlayerOwner(): string {
    return $this->playerOwner;
  }
  public function getMotherShip(): string {
    return $this->motherShip;
  }
  public function setCoolDown($coolDown): void {
    $this->coolDown = $coolDown;
  }
  public function setInUse(): void {
    $this->inUse = !$this->inUse;
  }
  public function getStatus(): string {
    return $this->status;
  }
  public function setStatus($status): void {
    $this->status = $status;
  }
  public function getTargetPos(): array {
    echo "\nTarget: " . $this->targetPos[0] . " " . $this->targetPos[1] . "\n";
    return $this->targetPos;
  }
  public function setTargetPos($targetPos): void {
    $this->targetPos = $targetPos;
    echo "\nTarget set to: " . $targetPos[0] . " " . $targetPos[1] . "\n";
  }

}
