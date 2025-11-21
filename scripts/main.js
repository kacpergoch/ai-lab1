class WatherApp {
  constructor() {
    this.apiKey = "7ded80d91f2b280ec979100cc8bbba94";
    this.currentWeatherApiUrl = "https://api.openweathermap.org/data/2.5/weather";
    this.forecastWeatherApiUrl = "https://api.openweathermap.org/data/2.5/forecast5";
    this.addressInput = document.getElementById("address-input");
    this.fetchButton = document.getElementById("fetch-weather-button");
    this.addressDisplay = document.getElementById("address-display");
    this.weatherTable = document.getElementById("weather-table");

    this.fetchButton.addEventListener("click", () => this.fetchWeather());
  }

  async fetchWeather() {
    const address = this.addressInput.value;
    if (!address) {
      alert("Please enter an address.");
      return;
    }

    this.addressDisplay.textContent = `Fetching weather for: ${address}`;

    try {
        const request = new XMLHttpRequest();
        request.open('GET', `${this.currentWeatherApiUrl}?q=${encodeURIComponent(address)}&appid=${this.apiKey}`, true);
        request.onload = function() {
            if (request.status === 200) {
                var currentWeatherData = JSON.parse(request.responseText);
                var lat = currentWeatherData.coord.lat;
                var lon = currentWeatherData.coord.lon;
                
                
                request.open('GET', `${this.forecastWeatherApiUrl}?lat=${lat}&lon=${lon}&appid=${this.apiKey}`, true);
                request.onload = function() {
                    if (request.status === 200) {
                        var forecastData = JSON.parse(request.responseText);

                        const data = forecastData.list.map(entry => ({
                            date: entry.dt_txt,
                            temperature: entry.main.temp,
                            condition: entry.weather[0].description
                        }));

                        this.displayWeather(data);
                    } else {
                        alert("Failed to fetch forecast data.");
                    }
                }.bind(this);
                request.send();
            } else {
                alert("Failed to fetch current weather data.");
            }
        }.bind(this);
        request.send();

        const data = forecastData.list.map(entry => ({
            date: entry.dt_txt,
            temperature: entry.main.temp,
            condition: entry.weather[0].description
        }));


        this.displayWeather(data);
    } catch (error) {
      console.error("Fetch error:", error);
      alert("Failed to fetch weather data.");
    }
  }

  displayWeather(data) {
    // Clear previous table
    this.weatherTable.innerHTML = "";

    // Create table headers
    const table = document.createElement("table");
    const headerRow = document.createElement("tr");
    ["Date", "Temperature", "Condition"].forEach(headerText => {
      const th = document.createElement("th");
      th.textContent = headerText;
      headerRow.appendChild(th);
    });
    table.appendChild(headerRow);

    // Populate table with data
    data.forEach(entry => {
      const row = document.createElement("tr");
      const dateCell = document.createElement("td");
      dateCell.textContent = entry.date;
      const tempCell = document.createElement("td");
      tempCell.textContent = entry.temperature;
      const conditionCell = document.createElement("td");
      conditionCell.textContent = entry.condition;

      row.appendChild(dateCell);
      row.appendChild(tempCell);
      row.appendChild(conditionCell);
      table.appendChild(row);
    });

    this.weatherTable.appendChild(table);
  }
}

document.addEventListener("DOMContentLoaded", () => {
  new WatherApp();
});

// Sample Grid.js table initialization      
new gridjs.Grid({
  columns: ["Name", "Email", "Phone Number"],
  data: [
    ["John", "john@example.com", "(353) 01 222 3333"],
    ["Mark", "mark@gmail.com", "(01) 22 888 4444"],
    ["Eoin", "eoin@gmail.com", "0097 22 654 00033"],
    ["Sarah", "sarahcdd@gmail.com", "+322 876 1233"],
    ["Afshin", "afshin@mail.com", "(353) 22 87 8356"]
  ]
}).render(document.getElementById("weather-table"));
