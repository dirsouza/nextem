<template>
  <v-dialog v-model="dialog" max-width="500px">
    <template v-slot:activator="{ on }">
      <v-btn color="primary" dark class="mb-2" v-on="on">Nova Tarefa</v-btn>
    </template>
    <Form />
  </v-dialog>
</template>

<script>
import Form from "./Form";

export default {
  name: "Dialog",
  components: {
    Form
  },
  props: {
    dialog: {
      type: Boolean,
      default: false
    }
  }
};
</script>
