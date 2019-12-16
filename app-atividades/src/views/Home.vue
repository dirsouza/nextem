<template>
  <v-app>
    <v-app-bar app color="indigo" dark>
      <v-toolbar-title>Atividades</v-toolbar-title>
      <v-spacer />
      <v-btn dark text @click="logoutUser">
        <v-icon>mdi-logout</v-icon>
      </v-btn>
    </v-app-bar>

    <v-content>
      <v-container class="fill">
        <v-row align="center" justify="center">
          <v-col class="text-center">
            <activities />
          </v-col>
        </v-row>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
import { mapActions } from "vuex";
import Activities from "../components/Activities";

export default {
  name: "home",
  components: {
    Activities
  },
  methods: {
    ...mapActions("auth", {
      logout: "logout"
    }),
    async logoutUser() {
      try {
        await this.logout();
        this.$router.push("/login");
      } catch (error) {
        console.log(error);
      }
    }
  }
};
</script>
