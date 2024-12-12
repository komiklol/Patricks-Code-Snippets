<?php

namespace MyApp;

class queTaskHTMLElements {
  public mixed $enemyFieldPlayerOne;
  public mixed $enemyFieldPlayerTwo;
  public mixed $friendlyFieldPlayerOne;
  public mixed $friendlyFieldPlayerTwo;

  public function __construct() {
    $this->enemyFieldPlayerOne = "";
    $this->friendlyFieldPlayerOne = "";
    $this->enemyFieldPlayerTwo = "";
    $this->friendlyFieldPlayerTwo = "";
  }
}
