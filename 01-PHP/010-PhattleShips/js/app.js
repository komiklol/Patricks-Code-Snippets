function adjustGridSize(rowsEnemyField, columnsEnemyField, rowsFriendlyField, columnsFriendlyField) {
  const containerEnemyField = document.getElementById("Enemy-Field");
  const containerWidthEnemyField = Math.floor(containerEnemyField.offsetWidth / columnsEnemyField) * columnsEnemyField;

  const containerFriendlyField = document.getElementById("Friendly-Field");
  const containerWidthFriendlyField = Math.floor(containerFriendlyField.offsetWidth / columnsFriendlyField) * columnsFriendlyField;

  containerFriendlyField.style.width = `${containerWidthFriendlyField}px`;
  containerEnemyField.style.width = `${containerWidthEnemyField}px`;

  console.log("Size Adjusted!");
}

// Set the Paypal Button Functionalitys in Credits
function setupPayPalButton() {
  PayPal.Donation.Button({
    env: 'sandbox',
    hosted_button_id:'HPQAEZN9X45DN',
    image: {
      src:'https://pics-v2.sandbox.paypal.com/00/s/ZjZiZWUyMjQtMjFmNy00MjEyLTk0ZjEtNzllMTE4Yjk1NzIw/file.JPG',
      alt:'Donate with PayPal button',
      title:'PayPal - The safer, easier way to pay online!',
    }
  }).render('#donate-button-container');
}

// Set Ping in Menu
async function measurePing() {
  const serverUrl = 'http://localhost/sockets/';
  const startTime = performance.now();
  await fetch(serverUrl, {method: 'HEAD'});
  const endTime = performance.now();
  const pingTime = endTime - startTime;
  document.getElementById('Ping').innerText = `Ping: ${pingTime.toFixed(2)} ms`;
}

let interval = null;
function startInterval() {
  if (interval === null) {
    measurePing();
    interval = setInterval(measurePing, 15000);
  }
}

function stopInterval() {
  if (interval !== null) {
    clearInterval(interval);
    interval = null;
  }

}





