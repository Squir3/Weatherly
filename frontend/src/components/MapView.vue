<template>
  <v-card class="mx-auto my-5" elevation="2" rounded="lg">
    <v-card-title>
      <v-icon large class="mr-2">mdi-map</v-icon>
      Map
    </v-card-title>
    <v-card-text>
      <div id="map"></div>
    </v-card-text>
  </v-card>
</template>

<script>
import { onMounted, ref } from "vue";
import L from "leaflet";
import "leaflet/dist/leaflet.css";

// Importowanie ikon markerów
import markerIcon2x from "leaflet/dist/images/marker-icon-2x.png";
import markerIcon from "leaflet/dist/images/marker-icon.png";
import markerShadow from "leaflet/dist/images/marker-shadow.png";

// Konfiguracja domyślnych ikon Leaflet
delete L.Icon.Default.prototype._getIconUrl;

L.Icon.Default.mergeOptions({
  iconRetinaUrl: markerIcon2x,
  iconUrl: markerIcon,
  shadowUrl: markerShadow,
});

export default {
  name: "MapView",
  emits: ["updateWeather"],
  setup(props, { emit }) {
    const map = ref(null);
    const marker = ref(null);

    const fetchUserLocation = () => {
      return new Promise((resolve) => {
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
              // Użyj geolokalizacji opartej na IP lub domyślnej lokalizacji
              await this.loadWeatherByIP();
            },
            { timeout: 5000 } // Timeout ustawiony na 5 sekund
          );
        } else {
          console.error("Geolocation not supported.");
          resolve({ latitude: 51.505, longitude: -0.09 });
        }
      });
    };

    const initMap = async () => {
      const { latitude, longitude } = await fetchUserLocation();

      map.value = L.map("map").setView([latitude, longitude], 13);

      L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution:
          '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      }).addTo(map.value);

      marker.value = L.marker([latitude, longitude])
        .addTo(map.value)
        .bindPopup("You are here.")
        .openPopup();

      emit("updateWeather", { latitude, longitude });

      map.value.on("click", (e) => {
        const lat = parseFloat(e.latlng.lat.toFixed(2));
        const lng = parseFloat(e.latlng.lng.toFixed(2));

        marker.value.setLatLng([lat, lng]);
        marker.value.bindPopup("You are here").openPopup();

        emit("updateWeather", { latitude: lat, longitude: lng });
      });
    };

    onMounted(() => {
      initMap();
    });

    return {
      map,
      marker,
    };
  },
};
</script>

<style scoped>
#map {
  height: 700px;
  width: 100%;
}
</style>
