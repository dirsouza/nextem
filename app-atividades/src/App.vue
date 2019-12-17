<template>
  <div id="app">
    <router-view />
    <Snackbar />
  </div>
</template>

<script>
import Snackbar from "./components/Snackbar";
import { mapActions } from "vuex";
export default {
  name: "App",
  components: {
    Snackbar
  },
  created() {
    this.$axios.interceptors.response.use(undefined, err => {
      if (err.status === 401) {
        this.$store.dispach("auth/logout");
        this.$router.push("/login");
      }

      throw err;
    });
  },
  methods: {
    ...mapActions("snackbar", {
      showSnackbar: "showSnackbar"
    })
  }
};
</script>
