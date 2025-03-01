<template>
  <v-card
    class="weather-card mx-auto my-5 pa-3"
    max-width="400"
    elevation="3"
    rounded="lg"
  >
    <v-card-title class="d-flex align-center">
      <v-icon large class="mr-2">mdi-map-marker</v-icon>
      <span>{{ weather.location }}</span>
    </v-card-title>
    <v-card-subtitle class="d-flex justify-center">
      <img
        :src="`http://openweathermap.org/img/wn/${weather.icon}@2x.png`"
        alt="Weather Icon"
      />
    </v-card-subtitle>
    <v-card-text>
      <div class="weather-description">
        {{ capitalize(weather.description) }}
      </div>
      <div class="weather-info">
        <v-icon color="orange darken-2" class="mr-2">mdi-thermometer</v-icon>
        <span class="weather-label">Temperature</span>
        <strong class="weather-value">{{ weather.temperature }} °C</strong>
      </div>
      <div class="weather-info">
        <v-icon color="blue darken-2" class="mr-2">mdi-water-percent</v-icon>
        <span class="weather-label">Humidity</span>
        <strong class="weather-value">{{ weather.humidity }}%</strong>
      </div>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  name: "WeatherWidget",
  props: {
    weather: {
      type: Object,
      required: true,
    },
  },
  methods: {
    capitalize(value) {
      if (!value) return "";
      return value.charAt(0).toUpperCase() + value.slice(1);
    },
  },
};
</script>

<style scoped>
.weather-card {
  background: linear-gradient(135deg, #e0f7fa 0%, #80deea 100%);
  border-radius: 15px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
.v-card-title {
  font-weight: bold;
  color: #004d40;
}
.v-icon {
  vertical-align: middle;
  margin-right: 5px;
}
img {
  width: 100px;
  height: 100px;
}
.weather-description {
  font-size: 1.2em;
  margin-bottom: 10px;
  color: #004d40;
  text-align: left;
  font-weight: bold;
}
.weather-info {
  display: flex;
  align-items: center;
  margin-bottom: 5px;
  color: #004d40;
  text-align: left;
}
.weather-label {
  margin-right: 5px;
  font-size: 1em;
  color: #004d40;
  font-weight: bold;
}
.weather-value {
  font-size: 1em;
  font-weight: bold;
  color: #004d40;
}
</style>
