<?php

namespace MyApp;

class antiAir {
    protected int $accuracy;
    protected array $damage;
    protected int $shots;

    public function __construct($accuracy, $damage, $shots) {
        $this->accuracy = $accuracy;
        $this->damage = $damage;
        $this->shots = $shots;
    }
}
