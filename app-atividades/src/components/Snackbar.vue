<template>
  <v-snackbar
    v-model="showSnackbar"
    :color="showType"
    :timeout="5000"
    multi-line
    top
    right
  >
    {{ showMessage }}
  </v-snackbar>
</template>

<script>
export default {
  name: "Snackbar",
  props: {
    snackbar: {
      type: Boolean,
      default: false
    },
    message: {
      type: String,
      required: true
    },
    type: {
      type: String,
      required: true
    }
  },
  computed: {
    showSnackbar: {
      get() {
        return this.snackbar;
      },
      set() {}
    },
    showMessage() {
      return this.message;
    },
    showType() {
      return this.type;
    }
  }
};
</script>