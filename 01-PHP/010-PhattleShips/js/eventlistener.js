// ///////////////////////////////////////////////////////////////
function addEventListener() {
  const clickableShip = document.querySelectorAll('.clickableShip');
  const playerHUDButton = document.querySelectorAll('.playerHUDButton');
  const reloadButton = document.querySelectorAll('.ReloadButton');

  clickableShip.forEach(ship => {
    ship.addEventListener('click', () => {
      checkClickState();

      let field = "fd=" + ship.dataset.field + "=fd,";
      let column = "cl=" + ship.dataset.column + "=cl,";
      let row = "rw=" + ship.dataset.row + "=rw,";
      let shipname = "sp=" + ship.dataset.ship + "=sp,";
      let routing = "rt=" + ship.dataset.routing + "=rt,";
      let otherData = "od=" + ship.dataset.otherData + "=od,";
      let shipRotation = "sr=" + ship.dataset.rotation + "=sr,";
      let payload = routing + field + column + row + shipname + otherData + shipRotation;

      console.log(payload);
      socket.send(payload);
    })
  })

  playerHUDButton.forEach(button => {
    if (!('evlsap' in button.dataset)) {
      console.log("Eventlistener Added!\n");
      button.addEventListener('click', () => {
        JoinCustomGameButton(button, );
      })
    } else {
      console.log("Eventlistener Blocked!\n");
    }
  })

  reloadButton.forEach(reloadButton => {
    reloadButton.addEventListener('click', () => {
      if (reloadButton.dataset.content === "Ping") {
        measurePing();
      }
    })
  })

console.log("Hello, eventListener Loaded!");
//////////////////////////////////////////////////////////////
}

function JoinCustomGameButton(button) {
  let otherData = "od=" + button.dataset.otherdata + "=od,";
  let routingData = "rt=" + button.dataset.routing + "=rt,";

  button.dataset.evlsap = 'true';

  if (button.dataset.otherdata === "JoinCustomGame") {
    const gameIDContainer = document.getElementById('GameID');
    const gamePWContainer = document.getElementById('GamePW');
    let gamePW = gamePWContainer.value;
    let gameID = gameIDContainer.value;
    otherData = "od=" + button.dataset.otherdata + " ID=" + gameID + "=ID PW=" + gamePW + "=PW =od,";
    if (gameIDContainer.classList.contains("AccessDenied")) {
      gameIDContainer.classList.remove("AccessDenied");
      document.getElementById('TSGameIDText').innerText = 'Enter The Game ID Here';
    }
    if (gamePWContainer.classList.contains("AccessDenied")) {
      gamePWContainer.classList.remove("AccessDenied");
      document.getElementById('TSGamePWText').innerText = 'Enter The Game Password Here';
    }
  }
  let payload = routingData + otherData;
  console.log(payload);
  socket.send(payload);
}

function addShipTileEventListener() {
  let shipTiles = document.querySelectorAll('.ShipTileEventListener');
  shipTiles.forEach(ship => {
    ship.addEventListener('click', () => {
      let shipname = "sp=" + ship.dataset.ship + "=sp,";
      let routing = "rt=game war shipmenu=rt,";
      let payload = routing + shipname;

      console.log(payload);
      socket.send(payload);
    })
  })
}



function eventListenerCreateGame() {
  const gamePW = document.getElementById('GamePW');
  const gameID = document.getElementById('GameID');
  let TopSecretImgID = document.getElementById('TopSecretImgID');
  let TopSecretImgPW = document.getElementById('TopSecretImgPW');

  if (!('evlsap' in TopSecretImgID.dataset)) {
    TopSecretImgID.dataset.evlsap = 'true';
    TopSecretImgID.addEventListener('click', () => {
      if (TopSecretImgID.dataset.state === 'on') {
        TopSecretImgID.src = '../img/Assets/TopSecretOff.png'
        TopSecretImgID.dataset.state = 'off';
      } else {
        TopSecretImgID.src = '../img/Assets/TopSecretOn.png'
        TopSecretImgID.dataset.state = 'on';
      }
      const type = gameID.getAttribute('type') === 'password' ? 'text' : 'password';
      gameID.setAttribute('type', type);
    })
  }

  if (!('evlsap' in TopSecretImgPW.dataset)) {
    TopSecretImgPW.dataset.evlsap = 'true';
    TopSecretImgPW.addEventListener('click', () => {
      if (TopSecretImgPW.dataset.state === 'on') {
        TopSecretImgPW.src = '../img/Assets/TopSecretOff.png'
        TopSecretImgPW.dataset.state = 'off';
      } else {
        TopSecretImgPW.src = '../img/Assets/TopSecretOn.png'
        TopSecretImgPW.dataset.state = 'on';
      }
      const type = gamePW.getAttribute('type') === 'password' ? 'text' : 'password';
      gamePW.setAttribute('type', type);
    })
  }

  console.log("")
}


function addInitialGameListener() {
  const shipButtonRotation = document.querySelectorAll('.ShipRotationButton');
  const shipButton = document.querySelectorAll('.ShipButton');
  const friendlyField = document.getElementById('GameFieldFriendlyGame');

  shipButtonRotation.forEach(rotationButton => {
    rotationButton.addEventListener('click', (event) => {
      checkClickState();

      const customCursor = document.getElementById('custom-cursor');

      let button = event.currentTarget;
      let rotation = button.dataset.rotation;

      rotationswitch(rotation, button, customCursor);

      event.stopPropagation();
    })
  })

  shipButton.forEach(ship => {
    ship.addEventListener('click', (event) => {
      if (checkClickState()) {
        return;
      }
      let button = event.currentTarget;

      console.log("Ship Button Triggered!\n" + button.dataset.otherdata);

      let div = document.getElementById('custom-cursor');
      let shipClass = 'ShipCursor-' + button.dataset.otherdata;
      div.classList.add(shipClass);
      let controlShipTile = document.getElementById('ControlTile')
      let ship = button.dataset.otherdata;
      if (controlShipTile) {
        let dataRot = div.dataset.rotation;

        document.querySelectorAll('.ShipRotationButton').forEach(rotButtons => {
          if (ship === rotButtons.dataset.ship) {
            dataRot = rotButtons.dataset.rotation;
            rotationswitch(dataRot, button, div);
          }
        })

        let vertical = false;
        vertical = dataRot === "d" || dataRot === "u";
        let shipSize = button.dataset.shipsize
        console.log("shipsize:  " + shipSize);
        let width = (vertical) ? (controlShipTile.offsetHeight * shipSize) : (controlShipTile.offsetWidth * shipSize);
        let height = (vertical) ? controlShipTile.offsetWidth : controlShipTile.offsetHeight;

        if (vertical) {
          div.children[0].src = '../img/Ships/' + ship + '/Full_v_' + ship + '.png'
        } else {
          div.children[0].src = '../img/Ships/' + ship + '/Full_' + ship + '.png'
        }
        div.children[0].style.display = 'block';
        console.log("Vertical: " + vertical);

        div.dataset.shipsize = button.dataset.shipsize;
        div.dataset.ship = ship;

        div.style.width = (vertical) ? height + 'px' : width + 'px';
        div.style.height = (vertical) ? (width) + 'px' : height + 'px';
        div.style.display = 'block';
        let shipCollision = document.querySelectorAll('.ShipCollision');
        shipCollision.forEach(function (shipCollision) {
          let shipInElement = shipCollision.dataset.ship;
          let rotation = shipCollision.dataset.rotation;
          let cursorRotation = div.dataset.rotation;
          if (cursorRotation === 'r' || cursorRotation === 'l') {
            cursorRotation = 'vertical';
          } else {
            cursorRotation = 'horizontal';
          }
          console.log("rotation = " + rotation + "\ncursorRotation = " + cursorRotation);
          if (ship === shipInElement  && rotation === cursorRotation) {
            shipCollision.style.zIndex = '5';
          }
        });
        shipButtonMouseMove(event);
      }
    })
  })

  friendlyField.addEventListener('click', (event) => {
    if (document.getElementById('custom-cursor').dataset.blocked !== 'yes') {
      if (checkClickState()) {
        let customCursor = document.getElementById('custom-cursor');
        let buttonShip = customCursor.dataset.ship;
        buttonShip = buttonShip + "-Button";
        let button = document.getElementById(buttonShip)

        let rect = friendlyField.getBoundingClientRect();

        let cellRow = (event.clientY - rect.top) / (friendlyField.offsetHeight / friendlyField.dataset.rows);
        let cellColumn = (event.clientX - rect.left) / (friendlyField.offsetWidth / friendlyField.dataset.columns);
        console.log("Row: " + friendlyField.dataset.rows);
        console.log("Columns: " + friendlyField.dataset.columns);

        cellRow = Math.floor(cellRow) + 1;
        cellColumn = Math.floor(cellColumn) + 1;

        cellRow = "rw=" + cellRow + "=rw,";
        cellColumn = "cl=" + cellColumn + "=cl,";

        let shipname = "sp=" + button.dataset.otherdata + "=sp,";
        let routing = "rt=" + button.dataset.routing + "=rt,";
        // let otherData = "od=" + event.dataset.otherData + "=od,";

        let rotation;
        switch (button.dataset.rotation) {
          case "u":
            rotation = "l";
            break;
          case "r":
            rotation = "u";
            break;
          case "d":
            rotation = "r";
            break;
          case "l":
            rotation = "d";
            break;
        }
        let shipRotation = "sr=" + rotation + "=sr,";
        let payload = routing + cellColumn + cellRow + shipname + shipRotation;
        console.log("row: " + cellRow + ", column: " + cellColumn);
        socket.send(payload);

      }

    }
  })
  console.log("InitialOn Event listener Loaded");
}

function rotationswitch(rotation, button, customCursor) {
  switch (rotation) {
    case "l":
      button.classList.remove('ShipRotationButton-L');
      button.classList.add('ShipRotationButton-U');
      button.dataset.rotation = "u";
      customCursor.dataset.rotation = "u";
      customCursor.style.transform = 'scaleY(1) scaleX(1)'
      break;
    case "u":
      button.classList.remove('ShipRotationButton-U');
      button.classList.add('ShipRotationButton-R');
      button.dataset.rotation = "r";
      customCursor.dataset.rotation = "r";
      customCursor.style.transform = 'scaleX(-1) scaleY(1)';
      break;
    case "r":
      button.classList.remove('ShipRotationButton-R');
      button.classList.add('ShipRotationButton-D');
      button.dataset.rotation = "d";
      customCursor.dataset.rotation = "d";
      customCursor.style.transform = 'scaleY(1) scaleX(-1)'
      break;
    case "d":
      button.classList.remove('ShipRotationButton-D');
      button.classList.add('ShipRotationButton-L');
      button.dataset.rotation = "l";
      customCursor.dataset.rotation = "l";
      customCursor.style.transform = 'scaleY(-1) scaleX(1)'
      break;
    default:
      console.log("Error, Something went Wrong in 'GameEventListener' -> 'rotationButton' -> 'switch' !! \n");
      break;
  }
}

function addShipCursorEventListener() {
  document.addEventListener('mousemove', shipButtonMouseMove);
  let shipCollision = document.querySelectorAll('.ShipCollision');
  shipCollision.forEach(function (shipCollision) {
    shipCollision.addEventListener('mouseenter', addCollisionShowEnterEventListener);
    shipCollision.addEventListener('mouseleave', addCollisionShowLeaveEventListener);
  })
}

function removeShipCursorEventListener() {
  document.removeEventListener('mousemove', shipButtonMouseMove);
  let shipCollision = document.querySelectorAll('.ShipCollision');
  shipCollision.forEach(function (shipCollision) {
    shipCollision.removeEventListener('mouseenter', addCollisionShowEnterEventListener);
    shipCollision.removeEventListener('mouseleave', addCollisionShowLeaveEventListener);
  })
}

function addCollisionShowEnterEventListener(event) {
  const cursor = document.getElementById('custom-cursor');
  let shipSize = document.getElementById('custom-cursor').dataset.shipsize;
  let sizeElement = event.target.dataset.shipsize;
  let rotation = event.target.dataset.rotation;
  let cursorRotation;
  if (cursor.dataset.rotation === 'r' || cursor.dataset.rotation === 'l') {
    cursorRotation = 'vertical';
  } else {
    cursorRotation = 'horizontal';
  }
  if (shipSize === sizeElement && rotation === cursorRotation) {
    cursor.dataset.blocked = 'yes';
    document.querySelector('#custom-cursor img').style.filter = 'sepia(100%) saturate(1100%) hue-rotate(-30deg) brightness(70%) opacity(99%)';
  }
}
function addCollisionShowLeaveEventListener() {

  const cursor = document.getElementById('custom-cursor');
  cursor.dataset.blocked = 'no';
  document.querySelector('#custom-cursor img').style.filter = 'none';
}

function shipButtonMouseMove(event) {
  const cursor = document.getElementById('custom-cursor');
  const controlShipTile = document.getElementById('ControlTile')

  let windowHeight = window.innerHeight;
  let windowWidth = window.innerWidth;
  let divHeight = cursor.offsetHeight;
  let divWidth = cursor.offsetWidth;

  let tileHeight = controlShipTile.offsetHeight / 2;
  let tileWidth = controlShipTile.offsetWidth / 2;

  let posX = event.clientX - tileWidth;

  let posY = event.clientY - tileHeight;

  if (posX + divWidth > windowWidth) {
    posX = windowWidth - divWidth;
  }
  if (posY + divHeight > windowHeight) {
    posY = windowHeight - divHeight;
  }
  if (posY < 0) {
    posY = 0;
  }
  if (posX < 0) {
    posX = 0;
  }
  cursor.style.left = posX + 'px';
  cursor.style.top = posY + 'px';

}

function checkClickState() {
  let div = document.getElementById('custom-cursor');
  let shipCursor = checkClass(div, 'ShipCursor');
  if (shipCursor) {
    let classes = div.classList;
    div.classList.remove(classes[shipCursor - 1]);
    div.children[0].style.display = 'none';
    div.style.display = 'none';
    let ship = div.dataset.ship;
    let shipCollision = document.querySelectorAll('.ShipCollision');
    shipCollision.forEach(function (shipCollision) {
      let shipInElement = shipCollision.dataset.ship;
      let rotation = shipCollision.dataset.rotation;
      let cursorRotation = div.dataset.rotation;
      if (cursorRotation === 'r' || cursorRotation === 'l') {
        cursorRotation = 'vertical';
      } else {
        cursorRotation = 'horizontal';
      }
      if (ship === shipInElement  && rotation === cursorRotation) {
        shipCollision.style.zIndex = '0';
      }
    });
    return true;
  }
  return false;
}

function checkClass(element, search) {
  let classes = element.classList;
  for (let i = 1; i <= classes.length; i++) {
    if (classes[i - 1].startsWith(search)) {
      return i;
    }
  }
  return 0;
}

let remainingTime;
let turnTimerInterval;
let turnTimerContainer;
let stopTimer = false;
let ShipTileMenuListenerActivated = false;
let moveCounter = 0;
function turnTimer(turnTime) {
  turnTimerContainer = document.getElementById('TurnTimer');
  clearInterval(turnTimerInterval);
  remainingTime = turnTime;
  turnTimerInterval = setInterval(() => {
    console.log("stopTimer : " + stopTimer)
    let payload;
    if (stopTimer) {
      clearInterval(turnTimerInterval);
    } else {
      let minutes = Math.floor(remainingTime / 60);
      let seconds = remainingTime % 60;
      turnTimerContainer.innerText = `${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
      console.log(`${minutes}:${seconds < 10 ? '0' + seconds : seconds}, `)
      if (--remainingTime < 0) {
        clearInterval(turnTimerInterval);
        // Sending times up to Server
        turnTimerContainer.innerText = "0:00";
        if (ShipTileMenuListenerActivated) {
          payload = "rt=game war timesup " + moveCounter + "=rt";
        } else {
          payload = "rt=game timeup empty-Hud=rt";
        }
        moveCounter++;
        socket.send(payload);
      }
    }
  }, 1000)
}
function resetTurnTimer() {
  turnTimerContainer = document.getElementById('TurnTimer');
  turnTimer(remainingTime);
}
function stopTurnTimer() {
  clearInterval(turnTimerInterval);
  // stopTimer = true;
  console.log("Turn Timer should be off: \n")
  turnTimerContainer = document.getElementById('TurnTimer');
  turnTimerContainer.innerText = "0:00";

  let div = document.getElementById('custom-cursor');
  div.children[0].style.display = 'none';
  div.style.display = 'none';
}

//////////////////////////////////////////////
// Action Buttons

// Apply Eventlistener on Action-Buttons
function actionButtonEventListener() {
  console.log("Function 'actionButtonEventListener' Loading!");
  let actionButtons = document.querySelectorAll('.Action-Button');
  let dataTile = document.getElementById('ControlTile');
  actionButtons.forEach(actionButton => {
    actionButton.addEventListener('click', (event) => {
      console.log("actionButton : " + actionButton.attributes);
      // If the Class is already set in this element nothing changes
      if (actionButton.classList.contains('Action-Button-Active')) {
        console.log("actionButton containing 'Action-Button-Active'");
        return;
      }
      if (actionButton.dataset.action !== "Helicopter") {
        dataTile.dataset.otherData = "";
      }
      // Iterate through all Buttons and remove the Class if Found
      actionButtons.forEach(ab => {
        if (ab.classList.contains('Action-Button-Active')) {
          console.log("actionButton removing 'Action-Button-Active'");
          ab.classList.remove('Action-Button-Active');
        }
      })
      // Add the Class to the clicked Button
      console.log("actionButton adding 'Action-Button-Active'");
      actionButton.classList.add('Action-Button-Active');

      let numberID = 'Countable-Number-' + actionButton.dataset.action;
      let numberElement = document.getElementById(numberID);
      dataTile.dataset.action = actionButton.dataset.action;
      dataTile.dataset.shipname = actionButton.dataset.otherdata;
      if (typeof numberElement !== 'undefined') {
        dataTile.dataset.amount  = numberElement.dataset.amount;
      } else {
        dataTile.dataset.amount  = "null";
      }
    });
  })
}

function enemyFieldEventListener() {
  console.log("Eventlistener Loading 'enemyFieldEventListener'");
  let friendlyFieldRep = document.getElementById('GameFieldFriendlyGame');
  friendlyFieldRep.addEventListener('click', (event) => {
    let dataTile = document.getElementById('ControlTile');
    let actionButtons = document.querySelectorAll('.Action-Button');
    let approved = false;
    actionButtons.forEach(ab => {
      if (ab.classList.contains('Action-Button-Active') && (dataTile.dataset.otherData === "Mission-Repair")) {
        console.log("Found Active 'Action-Button-Active'");
        approved = true;
      }
    })
    if (approved) {
      let boundingClientRect = friendlyFieldRep.getBoundingClientRect();

      // Maths the Position of the Click Event and Math it down to the Tile Positioning
      let cellRow = (event.clientY - boundingClientRect.top) / (friendlyFieldRep.offsetHeight / friendlyFieldRep.dataset.rows);
      let cellColumn = (event.clientX - boundingClientRect.left) / (friendlyFieldRep.offsetWidth / friendlyFieldRep.dataset.columns)
      cellRow = Math.floor(cellRow);
      cellColumn = Math.floor(cellColumn);

      console.log("Row: " + cellRow);
      console.log("Column: " + cellColumn);

      let otherData = "od=" + dataTile.dataset.otherData + "=od,";

      cellRow = "rw=" + cellRow + "=rw, ";
      cellColumn = "cl=" + cellColumn + "=cl, ";

      let routing = "rt=game war attack=rt, ";
      let action = "ac=" + dataTile.dataset.action + "=ac, ";
      let amount = "am=" + dataTile.dataset.amount + "=am, ";
      let shipname = "sp=" + dataTile.dataset.shipname + "=sp,";
      let payload = routing + shipname + action + cellRow + cellColumn + amount + otherData;
      socket.send(payload)
    }
  })

  let enemyField = document.getElementById('GameFieldEnemy');
  enemyField.addEventListener('click', (event) => {
    let actionButtons = document.querySelectorAll('.Action-Button');
    let dataTile = document.getElementById('ControlTile');
    let approved = false;
    actionButtons.forEach(ab => {
      if (ab.classList.contains('Action-Button-Active') && (dataTile.dataset.otherData !== "Mission-Repair")) {
        console.log("Found Active 'Action-Button-Active'");
        approved = true;
      }
    })
    if (approved) {
      // Get Boundings
      let boundingClientRect = enemyField.getBoundingClientRect();

      // Maths the Position of the Click Event and Math it down to the Tile Positioning
      let cellRow = (event.clientY - boundingClientRect.top) / (enemyField.offsetHeight / enemyField.dataset.rows);
      let cellColumn = (event.clientX - boundingClientRect.left) / (enemyField.offsetWidth / enemyField.dataset.columns)
      cellRow = Math.floor(cellRow);
      cellColumn = Math.floor(cellColumn);

      console.log("Row: " + cellRow);
      console.log("Column: " + cellColumn);
      console.log("ship: " + dataTile.dataset.shipname);

      let otherData = "";
      if (dataTile.dataset.otherData === "Mission-Attack" || dataTile.dataset.otherData === "Mission-Repair") {
        otherData = "od=" + dataTile.dataset.otherData + "=od,";
      }

      cellRow = "rw=" + cellRow + "=rw, ";
      cellColumn = "cl=" + cellColumn + "=cl, ";

      let routing = "rt=game war attack=rt, ";
      let action = "ac=" + dataTile.dataset.action + "=ac, ";
      let amount = "am=" + dataTile.dataset.amount + "=am, ";
      let shipname = "sp=" + dataTile.dataset.shipname + "=sp,";
      let payload = routing + shipname + action + cellRow + cellColumn + amount + otherData;
      socket.send(payload)

      dataTile.dataset.otherData = "";
    }
  })
}

// Apply Eventlistener for the Counters
function actionButtonCounterListener() {
  console.log("Eventlistener Loading 'actionButtonCounterListener'");
  let actionButtonCounterPlus = document.querySelectorAll('.Countable-Plus-Button');
  let actionButtonCounterMinus = document.querySelectorAll('.Countable-Minus-Button');

  actionButtonCounterPlus.forEach(plusButton => {
    plusButton.addEventListener('click', (event) => {
      console.log("plusButton Event");
      // Get the Number Element based of the name data
      let numberID = 'Countable-Number-' + plusButton.dataset.name;
      let numberElement = document.getElementById(numberID);
      // If amount should be over the max Count (due to cheaters or malfunction) it resets to the count
      console.log("amount: " + parseInt(numberElement.dataset.amount) + " count: " + parseInt(numberElement.dataset.count));
      if (parseInt(numberElement.dataset.amount) > parseInt(numberElement.dataset.count)) {
        numberElement.dataset.amount = numberElement.dataset.count;
        numberElement.innerText = numberElement.dataset.amount;
      }
      // If Amount is not equal the max amount to use, increase amount of 1 and change the Inner text to the new amount
      if (parseInt(numberElement.dataset.amount) < parseInt(numberElement.dataset.count)) {
        numberElement.dataset.amount++;
        numberElement.innerText = numberElement.dataset.amount;
      }
      let dataTile = document.getElementById('ControlTile');
      dataTile.dataset.amount = numberElement.dataset.amount;
    });
  })

  // Sam as Above but Decreases, minus duhhh
  actionButtonCounterMinus.forEach(minusButton => {
    minusButton.addEventListener('click', (event) => {
      console.log("minusButton Event");
      let numberID = 'Countable-Number-' + minusButton.dataset.name;
      let numberElement = document.getElementById(numberID);
      console.log("amount: " + parseInt(numberElement.dataset.amount) + " count: " + parseInt(numberElement.dataset.count));
      if (parseInt(numberElement.dataset.amount) < 0) {
        numberElement.dataset.amount = "0";
        numberElement.innerText = numberElement.dataset.amount;
      }
      if (parseInt(numberElement.dataset.amount) > 0) {
        numberElement.dataset.amount--;
        numberElement.innerText = numberElement.dataset.amount;
      }
      let dataTile = document.getElementById('ControlTile');
      dataTile.dataset.amount = numberElement.dataset.amount;
    });
  })
}

function actionButtonCounterMissionListener() {
  console.log("Eventlistener Loading 'actionButtonCounterMissionListener'");
  let missionSelector = document.querySelectorAll('.Mission-Selector-EL');
  let missionRepairDiv = document.getElementById('Mission-Repair');
  let missionAttackDiv = document.getElementById('Mission-Attack');
  let dataTile = document.getElementById('ControlTile');
  missionSelector.forEach(mission => {
    // if (mission.id === "Mission-Repair") {
    //   mission.classList.add("Mission_select");
    //   dataTile.dataset.otherData = "Mission-Repair";
    // }
    mission.addEventListener('click', (event) => {
      if (mission.classList.contains("Mission-Select")) {
        console.log("Mission-Select already Added")
        return;
      }
      console.log("mission: " + mission.id);
      let missionId = mission.id;
      if (missionId === "Mission-Repair") {
        mission.classList.add("Mission-Select");
        missionAttackDiv.classList.remove("Mission-Select");
        dataTile.dataset.otherData = "Mission-Repair";
      } else {
        mission.classList.add("Mission-Select");
        missionRepairDiv.classList.remove("Mission-Select");
        dataTile.dataset.otherData = "Mission-Attack";
      }
    })
  });
}










