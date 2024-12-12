// Create a new WebSocket
let socket = new WebSocket('ws://localhost:8080');
let action = document.getElementById('message');

// celled in socket.php
// receive data from Server
socket.onmessage = function (payload) {
  console.log("payload.data = " + payload.data);
  // Extracting the Data Transmitted, out of the Payload
  let payloadStrings = [];
  // tp = type, ph = PlayerHUD, ef = EnemyField, ff = FriendlyField
  let payloadPatterns = [/tp==(.*?)==tp/, /ph==(.*?)==ph/, /ef==(.*?)==ef/, /ff==(.*?)==ff/, /rc==(.*?)==rc/, /od==(.*?)==od/, /turntimer==(.*?)==turntimer/, /turntimermove==(.*?)==turntimermove/];
  // Convert payload data to string
  let payloadData = payload.data.toString();
  // iterate through the patterns to extract the data
  for (let i = 0; i < 8; i++) {
    payloadStrings[i] = payloadData.match(payloadPatterns[i]);
  }
  const completionSteps = [/completion1==(.*?)==1completion/, /completion2==(.*?)==2completion/, /completion3==(.*?)==3completion/]
  let completionFor = [];
  let completion = [];
  for (let i = 0; i < 3; i++) {
    completionFor[i] = payloadData.match(completionSteps[i]);
    completion[i] = completionFor[i][1];
  }
  if (completion.length === 3) {
    console.log("All completion Checks in Payload are Complete.\n");
  }
  let rowsColumns = payloadStrings[4][1].split(' ');
  // give the Strings data to its own data for clearance.
  let typeContent = payloadStrings[0][1];
  let playerHUDContent = payloadStrings[1][1];
  let enemyFieldContent = payloadStrings[2][1];
  let friendlyFieldContent = payloadStrings[3][1];
  let rowsEnemyField = rowsColumns[0];
  let columnsEnemyField = rowsColumns[1];
  let rowsFriendlyField = rowsColumns[2];
  let columnsFriendlyField = rowsColumns[3];
  let otherData = payloadStrings[4][1];
  let turnTime = payloadStrings[6][1];
  let turnTimeMove = payloadStrings[7][1];
  if (typeContent.includes("PH")) {
    // If the Content of the Message is not Null, it gets processed.
    if (playerHUDContent !== "null") {
      // Get the Div where the upcoming html code should be placed
      let playerHUDContainer = document.getElementsByClassName('Playing-HUD');
      // create Div Element which going to contain the HTML code
      let divPlayerHUD = document.createElement('div');
      // Add the Content to the just created div
      divPlayerHUD.innerHTML = playerHUDContent;
      // if there's an entry in the Container, delete it.
      if (playerHUDContainer[0].lastChild) {
        playerHUDContainer[0].removeChild(playerHUDContainer[0].lastChild);
      }
      // Push the new Data into the actual HTML Element
      playerHUDContainer[0].appendChild(divPlayerHUD.firstChild);
    }
    // console.log("Player HUD -> \n" + playerHUDContent)
  }
  if (typeContent.includes("EF")) {
    if (enemyFieldContent !== "null") {
      let enemyFieldContainer = document.getElementsByClassName('Enemy-Field');
      let divEnemyField = document.createElement('div');
      divEnemyField.innerHTML = enemyFieldContent;
      if (enemyFieldContainer[0].lastChild) {
        enemyFieldContainer[0].removeChild(enemyFieldContainer[0].lastChild);
      }
      enemyFieldContainer[0].appendChild(divEnemyField.firstChild);
    }
  }
  if (typeContent.includes("FF")) {
    if (friendlyFieldContent !== "null") {
      let friendlyFieldContainer = document.getElementsByClassName('Friendly-Field');
      let divFriendlyField = document.createElement('div');
      divFriendlyField.innerHTML = friendlyFieldContent;
      if (friendlyFieldContainer[0].lastChild) {
        friendlyFieldContainer[0].removeChild(friendlyFieldContainer[0].lastChild);
      }
      friendlyFieldContainer[0].appendChild(divFriendlyField.firstChild);
    }
  }
  if (typeContent.includes("CreateMenu")) {

    startInterval();
    adjustGridSize(rowsEnemyField, columnsEnemyField, rowsFriendlyField, columnsFriendlyField);
  } else {
    stopInterval();
  }
  // Console Logs.
  console.log("Coming in    -> " + typeContent)
  if (typeContent.includes("Ingame")) {
    if (typeContent.includes("InitialOn")) {
      addShipCursorEventListener();
      addInitialGameListener();
    }
    if (typeContent.includes("InitialOff")) {
      removeShipCursorEventListener();
    }
  } else {
    addEventListener();
  }
  if (typeContent.includes("AccessDenied-PW")) {
    const gamePWContainer = document.getElementById('GamePW');
    gamePWContainer.classList.add('AccessDenied');
    document.getElementById('TSGamePWText').innerText = 'Wrong Password';
  }
  if (typeContent.includes("AccessDenied-ID")) {
    const gameIDContainer = document.getElementById('GameID');
    gameIDContainer.classList.add('AccessDenied');
    document.getElementById('TSGameIDText').innerText = 'Wrong Game ID';
  }

  if (typeContent.includes("Credits")) {
    setupPayPalButton();
    console.log("PayPal Loaded");
  }
  if (typeContent.includes("CustomGame")) {
    eventListenerCreateGame();
  }
  if (typeContent.includes("ShipTileMenuListener")) {
    enemyFieldEventListener()
    addShipTileEventListener();
    ShipTileMenuListenerActivated = true;
    stopTimer = true;
  }
  if (typeContent.includes("ActionButtonListener")) {
    actionButtonEventListener()
    actionButtonCounterListener()
    actionButtonCounterMissionListener()
    stopTimer = true;

  }
  if (typeContent.includes("TurnTimerOff")) {
    stopTurnTimer();
    stopTimer = true;
  }
  if (typeContent.includes("TurnTimer")) {
    stopTimer = false;
    resetTurnTimer();
  }
  if (typeContent.includes("SetTimer")) {
    stopTimer = false;
    if (typeContent.includes("SetTimerMove")) {
      console.log("move: " + turnTimeMove + " time: " + turnTime);
      stopTurnTimer();
      turnTimer(turnTimeMove);
      return;
    }
    stopTurnTimer(turnTime);
    turnTimer(turnTime);
  }


  console.log("Completion Checks: " + completion.length + "\nCompeltion steps: " + completion);
};
