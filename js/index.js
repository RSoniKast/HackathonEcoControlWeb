window.onload = function() {
    fetch('http://192.168.1.50')
        .then(response => response.json())
        .then(data => {
            var volt = data.volt;
            var ampere = data.ampere;
            var kwh = calculateKwh(volt, ampere);
            var kwhElement = document.getElementById('kwh');
            kwhElement.innerText = kwh.toFixed(2) + ' kWh';
            var color;
            if (kwh < 50) {
                color = 'green';
            } else if (kwh < 75) {
                color = 'orange';
            } else {
                color = 'red';
            }
            kwhElement.style.setProperty('--color', color);
        });
}

function calculateKwh(volt, ampere) {
    var watt = volt * ampere;
    var kwh = watt / 1000;
    return kwh;
}
