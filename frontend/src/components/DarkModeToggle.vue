<template>
  <v-btn icon @click="toggleDarkMode">
    <v-icon>{{
      darkMode ? "mdi-white-balance-sunny" : "mdi-weather-night"
    }}</v-icon>
  </v-btn>
</template>

<script>
import { ref } from "vue";
import { useTheme } from "vuetify";

export default {
  name: "DarkModeToggle",
  setup() {
    const theme = useTheme();
    const darkMode = ref(false);

    const toggleDarkMode = () => {
      darkMode.value = !darkMode.value;
      theme.global.name.value = darkMode.value ? "dark" : "light";
    };

    return {
      darkMode,
      toggleDarkMode,
    };
  },
};
</script>

<style scoped></style>
