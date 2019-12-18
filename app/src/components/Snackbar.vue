<template>
  <v-snackbar
    v-model="snackbar.show"
    :color="snackbar.type"
    :timeout="5000"
    @input="clear"
    multi-line
    top
    right
  >
    {{ snackbar.message }}

    <v-btn
      text
      @click="snackbar.show = false"
    >
      Fechar
    </v-btn>
  </v-snackbar>
</template>

<script>
import { mapMutations, mapGetters, mapActions } from "vuex";

export default {
  name: "Snackbar",
  computed: {
    ...mapGetters("snackbar", {
      snackbar: "getSnackbar"
    })
  },
  methods: {
    ...mapActions("snackbar", {
      showSnackbar: "showSnackbar"
    }),
    ...mapMutations("snackbar", {
      clearSnackbar: "clearSnackbar"
    }),
    clear() {
      this.clearSnackbar();
    }
  }
};
</script>