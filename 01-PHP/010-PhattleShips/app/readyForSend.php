<?php
  function readyForSend($type, $playerHUD, $enemyField, $friendlyField, $sizeEnemyField, $sizeFriendlyField, $turnTime, $otherData): string {
    if ($sizeFriendlyField == "null") {
      $sizeFriendlyField = [38, 14];
      $sizeEnemyField = [38, 14];
    }
    $type = "tp==" . $type . "==tp,";
    $sC1 = "completion1==1==1completion";
    $playerHUD = "ph==" . $playerHUD . "==ph,";
    $enemyField = "ef==" . $enemyField . "==ef,";
    $sC2 = "completion2==2==2completion";
    $friendlyField = "ff==" . $friendlyField . "==ff,";
//    echo "\n$friendlyField\n"; // why is there an array ?!?!?!?
    $rowsColumns = "rc==" . $sizeEnemyField[0] . " " . $sizeEnemyField[1] . " " . $sizeFriendlyField[0] . " " . $sizeFriendlyField[1] . "==rc,";
    $otherDataString = "od==" . $otherData . "==od,";
    $sC3 = "completion3==3==3completion";
    if (is_array($turnTime)) {
      $turnTimerString = 'turntimer==' . $turnTime["initial"] . '==turntimer';
      $turnTimerStringMove = 'turntimermove==' . $turnTime["move"] . '==turntimermove';
    } else {
      $turnTimerString = 'turntimer==' . $turnTime . '==turntimer';
      $turnTimerStringMove = 'turntimermove==0==turntimermove';
    }
    return ($type . $sC1 . $playerHUD . $enemyField . $sC2 . $friendlyField . $rowsColumns . $otherDataString . $sC3 . $turnTimerString . $turnTimerStringMove);
  }
