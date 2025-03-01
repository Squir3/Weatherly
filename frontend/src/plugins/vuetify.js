// frontend/src/plugins/vuetify.js
import { createVuetify } from "vuetify";
import "vuetify/styles";
import { aliases, mdi } from "vuetify/iconsets/mdi";

export default createVuetify({
  theme: {
    defaultTheme: "light",
    themes: {
      light: {
        colors: {
          primary: "#3F51B5",
          secondary: "#E91E63",
          background: "#f0f2f5",
        },
      },
      dark: {
        colors: {
          primary: "#1976D2",
          secondary: "#424242",
        },
      },
    },
  },
  icons: {
    defaultSet: "mdi",
    aliases,
    sets: {
      mdi,
    },
  },
});
