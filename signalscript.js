const cryptoSignals = document.querySelectorAll('.crypto-signal');

function getRandomStatus() {
  const statuses = ['Buy', 'Sell', 'Hold'];
  return statuses[Math.floor(Math.random() * statuses.length)];
}

function setSignalStatus(signalElement) {
  const status = getRandomStatus();
  signalElement.textContent = status;
  signalElement.classList.add(status.toLowerCase());
}

cryptoSignals.forEach(signal => {
  setSignalStatus(signal);
  signal.addEventListener('click', () => setSignalStatus(signal));
});
