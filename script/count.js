let valueDisplays = document.querySelectorAll(".num");
let interval = 500;

valueDisplays.forEach((valueDisplays) => {
        let startValue = 0;
        let endvalue = parseInt(valueDisplays.getAttribute("data-val"));
        let duration = Math.floor(interval / endvalue);
        let counter = setInterval(function() {
            startValue +=1;
            valueDisplays.textContent = startValue;
            if (startValue == endvalue) {
                clearInterval(counter);
            }
        }, duration);
});