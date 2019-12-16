<template>
  <div id="app">
    <router-view />
  </div>
</template>

<script>
export default {
  name: "App",
  created() {
    this.$axios.interceptors.response.use(undefined, err => {
      if (err.status === 401) {
        this.$store.dispach("auth/logout");
      }

      throw err;
    });
  }
};
</script>
