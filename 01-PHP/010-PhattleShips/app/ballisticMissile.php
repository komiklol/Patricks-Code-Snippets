<?php

namespace MyApp;

class ballisticMissile {
    protected int $coolDown;
    protected array $damage;
    protected int $amount;
    protected int $splashDamageRadius;
    protected float $splashDamageMultiplier;

    public function __construct($coolDown, $amount, $damage, $splashDamageRadius, $splashDamageMultiplier) {
        $this->coolDown = $coolDown;
        $this->amount = $amount;
        $this->damage = $damage;
        $this->splashDamageRadius = $splashDamageRadius;
        $this->splashDamageMultiplier = $splashDamageMultiplier;
    }
    public function getAction(): array {
      return array("BallisticMissile", $this->amount, $this->coolDown);
    }
    public function getAmount(): int {
      return $this->amount;
    }
    public function setAmount($value, $operation = "-"): void {
      // $operation: "+" "-" "=" "*" "/"
      if ($operation == "=") {
        $this->amount = $value;
        return;
      }
      $amount = $this->amount;
      eval("\$amount = \$amount $operation \$value;");
      $this->amount = $amount;
    }
    public function getDamageData(): array {
      return array($this->damage, $this->splashDamageRadius, $this->splashDamageMultiplier);
//      list($damage, $splashDmgRadius, $splashDmgMulti) =
    }
    public function getCoolDown(): int {
      return $this->coolDown;
    }
  public function getImageName(): string {
    return "BallisticMissile";
  }
}
