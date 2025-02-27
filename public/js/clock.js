// Javascript is used to set the clock to your computer time.

var currentSec = getSecondsToday();

var seconds = (currentSec / 60) % 1;
var minutes = (currentSec / 3600) % 1;
var hours = (currentSec / 43200) % 1;

setTime(60 * seconds, "second");
setTime(3600 * minutes, "minute");
setTime(43200 * hours, "hour");

function setTime(left, hand) {
  $(".clock__" + hand).css("animation-delay", "" + left * -1 + "s");
}

function getSecondsToday() {
  let now = new Date();

  let today = new Date(now.getFullYear(), now.getMonth(), now.getDate());

  let diff = now - today; 
  return Math.round(diff / 1000);
}


function getPersianDate() {
    const persianFormatter = new Intl.DateTimeFormat('fa-IR', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

    const date = new Date();
    return persianFormatter.format(date);
}

// Insert the Persian formatted date into the div
document.getElementById("persian-date").innerText = getPersianDate();
