<template>
  <v-container class="my-5">
    <v-row>
      <v-col cols="12" md="4">
        <WeatherWidget :weather="weather" />
      </v-col>
      <v-col cols="12" md="8">
        <MapView @updateWeather="updateWeather" />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import axios from "axios";
import WeatherWidget from "@/components/WeatherWidget.vue";
import MapView from "@/components/MapView.vue";

export default {
  name: "HomeView",
  components: {
    WeatherWidget,
    MapView,
  },
  data() {
    return {
      weather: {
        temperature: null,
        humidity: null,
        description: "",
        icon: "",
        location: "",
      },
    };
  },
  methods: {
    async loadWeather(lat, lon) {
      // Zaokrąglij współrzędne do dwóch miejsc po przecinku
      const roundedLat = lat.toFixed(2);
      const roundedLon = lon.toFixed(2);

      try {
        const response = await axios.get("http://localhost/api/weather", {
          params: { lat: roundedLat, lon: roundedLon },
        });

        if (response.data && response.data.main) {
          this.weather = {
            temperature: response.data.main.temp,
            humidity: response.data.main.humidity,
            description: response.data.weather[0].description,
            icon: response.data.weather[0].icon,
            location: response.data.name,
          };
        } else {
          console.error("Invalid weather data:", response.data);
        }
      } catch (error) {
        console.error("Error fetching weather data:", error);
      }
    },
    async updateWeather(payload) {
      const { latitude, longitude } = payload;
      await this.loadWeather(latitude, longitude);
    },
  },
  async mounted() {
    // Pobierz lokalizację użytkownika i załaduj dane pogodowe przy starcie aplikacji
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        async (position) => {
          await this.loadWeather(
            position.coords.latitude,
            position.coords.longitude
          );
        },
        async (error) => {
          console.error("Geolocation error:", error);
          // Domyślna lokalizacja (np. Londyn)
          await this.loadWeather(51.505, -0.09);
        }
      );
    } else {
      console.error("Geolocation not supported.");
      // Domyślna lokalizacja (np. Londyn)
      await this.loadWeather(51.505, -0.09);
    }
  },
};
</script>

<style scoped></style>
