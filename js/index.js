window.onload = function() {
    var humidityElement = document.getElementById('humidity');
    humidityElement.querySelector('.circle-text').innerText = data['Humidite'] + ' %';

    var temperatureElement = document.getElementById('temperature');
    temperatureElement.querySelector('.circle-text').innerText = data['Temperature'] + ' °C';

    var volt = parseFloat(data['Volt']); // Convertir en nombre
    var ampere = parseFloat(data['Ampere']); // Convertir en nombre

    var kwh = calculateKwh(volt, ampere);
    var kwhElement = document.getElementById('kwh');
    var color;
    if (kwh < 50) {
        color = 'green';
    } else if (kwh < 75) {
        color = 'orange';
    } else {
        color = 'red';
    }
    kwhElement.style.setProperty('--color', color);
    kwhElement.querySelector('.circle-text').innerText = kwh.toFixed(2) + ' kWh';
}

function calculateKwh(volt, ampere) {
    var watt = volt * ampere;
    var kwh = watt / 1000;
    return kwh;
}