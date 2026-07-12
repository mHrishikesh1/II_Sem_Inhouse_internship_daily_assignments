let clickCount = 0;
const counterDisplay = document.getElementById('counterDisplay');
const hitBtn = document.getElementById('hitBtn');
const clearBtn = document.getElementById('clearBtn');
hitBtn.addEventListener('click', function() {
    clickCount++;
    counterDisplay.innerText = clickCount;
});
clearBtn.addEventListener('click', function() {
    clickCount = 0;
    counterDisplay.innerText = clickCount;
});