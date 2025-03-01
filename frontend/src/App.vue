<template>
  <v-app>
    <v-app-bar app color="indigo darken-3" dark>
      <v-toolbar-title>Weatherly</v-toolbar-title>
      <v-spacer></v-spacer>
      <DarkModeToggle />
    </v-app-bar>

    <v-main>
      <router-view></router-view>
    </v-main>
  </v-app>
</template>

<script>
import DarkModeToggle from "@/components/DarkModeToggle.vue";

export default {
  name: "App",
  components: {
    DarkModeToggle,
  },
};
</script>

<style></style>
