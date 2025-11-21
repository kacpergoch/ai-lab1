class WeatherApp {
  constructor() {
    this.apiKey = "7ded80d91f2b280ec979100cc8bbba94";
    this.currentWeatherApiUrl = "https://api.openweathermap.org/data/2.5/weather";
    this.forecastWeatherApiUrl = "https://api.openweathermap.org/data/2.5/forecast";
    this.cityInput = document.getElementById("city-input");
    this.currentWeatherTitle = document.getElementById("current-weather-title");
    this.forecastWeatherTitle = document.getElementById("forecast-weather-title");
    this.fetchButton = document.getElementById("fetch-weather-button");
    this.currWeatherTable = document.getElementById("curr-weather-table");
    this.forecastTable = document.getElementById("forecast-table");
    this.currWeatherGrid = null;
    this.forecastGrid = null;
    this.fetchButton.addEventListener("click", () => this.fetchWeather());
  }

  async fetchWeather() {
    const city_name = this.cityInput.value;
    if (!city_name) {
      alert("Enter the city name!");
      return;
    }

    try {
        const request = new XMLHttpRequest();
        request.open('GET', `${this.currentWeatherApiUrl}?q=${encodeURIComponent(city_name)}&appid=${this.apiKey}&units=metric`, true);
        request.onload = async function() {
            if (request.status === 200) {
                const currentWeatherData = JSON.parse(request.responseText);
                const lat = currentWeatherData.coord.lat;
                const lon = currentWeatherData.coord.lon;

                const currentWeather = {
                    temperature: currentWeatherData.main.temp,
                    condition: currentWeatherData.weather[0].description,
                    windSpeed: currentWeatherData.wind.speed,
                    humidity: currentWeatherData.main.humidity
                };
                console.log("Current Weather Data:", currentWeather);

                // Update the current weather table
                const currWeatherTableDiv = document.getElementById("curr-weather-table");
                if (this.currWeatherGrid) {
                    this.currWeatherGrid.destroy();
                }
                currWeatherTableDiv.innerHTML = "";
                this.currWeatherGrid = new gridjs.Grid({
                    columns: ["Temperature", "Condition", "Wind Speed", "Humidity"],
                    data: [
                        [`${currentWeather.temperature}°C`,
                          currentWeather.condition,
                          `${currentWeather.windSpeed} km/h`,
                          `${currentWeather.humidity}%`],
                    ]
                });
                this.currWeatherGrid.render(document.getElementById("curr-weather-table"));

                try {
                    const response = await fetch(`${this.forecastWeatherApiUrl}?lat=${lat}&lon=${lon}&appid=${this.apiKey}&units=metric`);
                    if (response.ok) {
                        const forecastWeatherData = await response.json();

                        const data = forecastWeatherData.list.map(entry => ({
                            date: entry.dt_txt,
                            temperature: entry.main.temp,
                            condition: entry.weather[0].description,
                            windSpeed: entry.wind.speed,
                            humidity: entry.main.humidity
                        }));
                        console.log("Forecast Data:", data);

                        // Update the forecast table
                        const forecastTableDiv = document.getElementById("forecast-table");
                        if (this.forecastGrid) {
                            this.forecastGrid.destroy();
                        }
                        forecastTableDiv.innerHTML = "";
                        this.forecastGrid = new gridjs.Grid({
                            columns: ["Date", "Temperature", "Condition", "Wind Speed", "Humidity"],
                            data: data.map(entry => [
                                entry.date,
                                `${entry.temperature}°C`,
                                entry.condition,
                                `${entry.windSpeed} km/h`,
                                `${entry.humidity}%`
                            ])
                        });
                        this.forecastGrid.render(document.getElementById("forecast-table"));
                    } else {
                        alert("Failed to fetch forecast data.");
                    }
                } catch (error) {
                    console.error("Forecast fetch error:", error);
                    alert("Failed to fetch forecast data.");
                }
            } else {
                alert("Failed to fetch current weather data.");
            }
        }.bind(this);
        request.send();
        this.currentWeatherTitle.textContent = `Current Weather in ${city_name}`;
        this.forecastWeatherTitle.textContent = `5-Day Forecast for ${city_name}`;
    } catch (error) {
      console.error("Current Weather fetch error:", error);
      alert("Failed to fetch current weather data.");
    }
  }
}

document.addEventListener("DOMContentLoaded", () => {
  new WeatherApp();
});
