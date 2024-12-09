<?php
    // Set the Max RAM Usage to 4000 MB
    ini_set('memory_limit', '4000M');


    // Pin Code Generator.
    // dl1, dl2 and al1 sets the Length of each Part
    function pinGenerator($dL1, $dL2, $aL1) {
        $pin = "";
        for ($i = 0; $i < $dL1; $i++) {
            $pin .= rand(0, 9);
        }
        for ($i = 0; $i < $aL1; $i++) {
            $pin .= chr(rand(97, 122));
        }
        for ($i = 0; $i < $dL2; $i++) {
            $pin .= rand(0, 9);
        }
        return $pin;
    }

    // Splits the Pin into single Chars and sorting them into Arrays
    function pinSplitter($pinSplitStorage, $pin) {
        $currentPinStorageArray = &$pinSplitStorage;
        for ($i = 0; $i < strlen($pin); $i++) {
            if (!isset($currentPinStorageArray[$pin[$i]])) {
                $currentPinStorageArray[$pin[$i]] = [];
            }
            $currentPinStorageArray = &$currentPinStorageArray[$pin[$i]];
        }
        return $pinSplitStorage;
    }

    // Search for the Pin by each Char.
    // If the Whole Pin is found, return false.
    // If a char of the Pin is not found at its corresponding array position, return true
    function pinSplitterFinder($pinSplitStorage, $pin) {
        $currentPinStorageArray = &$pinSplitStorage;
        for ($i = 0; $i < strlen($pin); $i++) {
            if (!isset($currentPinStorageArray[$pin[$i]])) {
                return true;
            }
            $currentPinStorageArray = &$currentPinStorageArray[$pin[$i]];
        }
        return false;
    }

    // The "Easy way" to add a Pin
    function pinAdd ($pinArrayStorage, $pin) {
        $pinArrayStorage[] = $pin;
        return $pinArrayStorage;
    }

    // The "Easy way" to search for a Pin
    function pinArrayFinder ($pinArrayStorage, $pin) {
        foreach ($pinArrayStorage as $entry) {
            if ($entry == $pin) {
                return false;
            }
        }
        return true;
    }
    // Another easy way to find a Pin
    function pinArrayFinder2 ($pinArrayStorage, $pin) {
        if (in_array($pin, $pinArrayStorage)) {
            return false;
        } else {
            return true;
        }
    }


    // Set the Sample Number
    // Fun Fact. It Took around 1:30 to 2 Hours for 500.000 Pins to Check with the "Easy Way"
    $runs = 5000;
    // Initialize Empty Arrays
    $pinArray = [];
    $pinSplitStorage = [];
    $pinArrayStorage = [];
    $pinArrayStorage2 = [];
    $pinSplitterResults = ["Run" => [], "Time" => [], "Found" => []];
    $pinArrayResults = ["Run" => [], "Time" => [], "Found" => []];
    $pinArrayResults2 = ["Run" => [], "Time" => [], "Found" => []];

    echo "### Start Pin Generation ###\n";

    // Generate the Pins
    for ($i = 1; $i <= $runs; $i++) {
        $pinArray[$i] = pinGenerator(12,4,4);
    }

    echo "### Start Splitter Run ###\n";

    // Go Through the Pin Storage, if the Pin isn't Set, Store it in the Array.
    // Here is my way of it used.
    for ($i = 1; $i <= $runs; $i++) {
        $pin = $pinArray[$i];
        // Track the Time this Method is taking
        $SplitterTimerStart = microtime(true);
        // If the Pin is not set in the Storage, store it
        if (pinSplitterFinder($pinSplitStorage, $pin)) {
            $pinSplitStorage = pinSplitter($pinSplitStorage, $pin);
            $pinFound = false;
        } else {
            $pinFound = true;
        }
        // Stop The Time and Save which run we where
        $SplitterTimerStop = microtime(true);
        $pinSplitterResults["Run"][$i] = $i;
        $pinSplitterResults["Time"][$i] = ($SplitterTimerStop - $SplitterTimerStart);
        $pinSplitterResults["Found"][$i] = "$pinFound";
    }

    echo "### Start Array Run ###\n";
    // Same as before just with the "Easy Way"
    for ($i = 1; $i <= $runs; $i++) {
        $pin = $pinArray[$i];
        $ArrayTimerStart = microtime(true);
        if (pinArrayFinder($pinArrayStorage, $pin)) {
            $pinArrayStorage = pinAdd($pinArrayStorage, $pin);
            $pinFound = false;
        } else {
            $pinFound = true;
        }
        $ArrayTimerStop = microtime(true);
        $pinArrayResults["Run"][$i] = $i;
        $pinArrayResults["Time"][$i] = ($ArrayTimerStop - $ArrayTimerStart);
        $pinArrayResults["Found"][$i] = "$pinFound";
        echo "$i ";
    }

    echo "### Start Array Run 2 ###\n";
    // Same as before just with the "Easy Way2"
    for ($i = 1; $i <= $runs; $i++) {
        $pin = $pinArray[$i];
        $ArrayTimerStart = microtime(true);
        if (pinArrayFinder2($pinArrayStorage2, $pin)) {
            $pinArrayStorage2 = pinAdd($pinArrayStorage2, $pin);
            $pinFound = false;
        } else {
            $pinFound = true;
        }
        $ArrayTimerStop = microtime(true);
        $pinArrayResults2["Run"][$i] = $i;
        $pinArrayResults2["Time"][$i] = ($ArrayTimerStop - $ArrayTimerStart);
        $pinArrayResults2["Found"][$i] = "$pinFound";
        echo "$i ";
    }

    echo "\n";
    echo "### Start Time Mathing Run ###\n";

    // Math the Whole Time it took to run through all Pins
    $allTime = 0;
    foreach ($pinSplitterResults["Time"] as $time) {
        $allTime += $time;
    }
    $allTimeSplitter = $allTime;
    $averageSplitterTime = $allTime / count($pinArray);
    $allTime = 0;
    foreach ($pinArrayResults["Time"] as $time) {
        $allTime += $time;
    }
    $allTimeArray = $allTime;
    $averageArrayTime = $allTime / count($pinArray);
    $allTime = 0;

    echo "----------------------------------------------------------------------------\n";
    echo "Array avg.: " . number_format($averageArrayTime, 10) . "\n";
    echo "Split avg.: " . number_format($averageSplitterTime, 10) . "\n";
    echo "Array Time: " . number_format($allTimeArray, 10) . "\n";
    echo "Split Time: " . number_format($allTimeSplitter, 10) . "\n";

    // Wait for User Input.
    // The User can Enter an Pin and check if its stored. 3 Pins are provide.
    // Returns the Time it took to find the Pin in Storage.
    $input = "";
    while ($input != "stop") {
        echo "----------------------------------------------------------------------------\n";
        echo "First  Entry of Pins  : " . $pinArray[1] . "\n";
        echo "Random Entry of Pins  : " . $pinArray[array_rand($pinArray)] . "\n";
        echo "Latest Entry of Pins  : " . end($pinArray) . "\n";
        echo "----------------------------------------------------------------------------\n";
        $input = trim(fgets(STDIN));
        $ArrayTimerStart = microtime(true);
        if (pinArrayFinder($pinArrayStorage, $input)) {
            echo "Nothing Found\n";
        } else {
            echo "Found It\n";
        }
        $ArrayTimerStop = microtime(true);
        $ArrayTimer = ($ArrayTimerStop - $ArrayTimerStart);

        $SplitterTimerStart = microtime(true);
        if (pinSplitterFinder($pinSplitStorage, $input)) {
            echo "Nothing Found\n";
        } else {
            echo "Found It\n";
        }
        $SplitterTimerStop = microtime(true);
        $SplitterTimer = ($SplitterTimerStop - $SplitterTimerStart);

        $ArrayTimerStart = microtime(true);
        if (pinArrayFinder2($pinArrayStorage2, $input)) {
            echo "Nothing Found\n";
        } else {
            echo "Found It\n";
        }
        $ArrayTimerStop = microtime(true);
        $ArrayTimer2 = ($ArrayTimerStop - $ArrayTimerStart);

        echo "----------------------------------------------------------------------------\n";
        echo "Array Time  : " . number_format($ArrayTimer, 12) . "\n";
        echo "Array2 Time : " . number_format($ArrayTimer2, 12) . "\n";
        echo "Split Time  : " . number_format($SplitterTimer, 12) . "\n";
        unset($SplitterTimer, $SplitterTimerStart, $SplitterTimerStop, $ArrayTimer, $ArrayTimerStart, $ArrayTimerStop);
    }


    // Data I found out (times in Seconds):
    // Array avg.: All Time of "Easy Way" / Pins
    // Split avg.: All Time of my Method / Pins
    // Array Time: All Time of "Easy Way"
    // Split Time: All Time of my Method

    // 500.000 Pins:
    // Array avg.: 0.017436509181023
    // Split avg.: 7.7328094005585E-5
    // Array Time: 8718.2545905113
    // Split Time: 38.664047002792

    // Searching for the First Entry:
    // Array Time: 0.00015497207641602
    // Split Time: 0.0021171569824219

    // Searching a Random Entry:
    // Array Time: 0.021354913711548
    // Split Time: 0.0014798641204834

    // Searching for the Last Entry:
    // Array Time: 0.027944803237915
    // Split Time: 0.0010480880737305
