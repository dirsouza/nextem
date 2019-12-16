<template>
  <v-dialog
    v-model="showDialog"
    persistent
    max-width="500px"
  >
    <Form
      :title="title"
      :item-edit="itemEdit"
      @dialog:close="close"
    />
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
    },
    itemEdit: {
      type: Object,
      required: false
    }
  },
  data() {
    return {
      showDialog: false,
      title: "Nova Tarefa"
    };
  },
  watch: {
    dialog(val) {
      this.showDialog = val;
    },
    itemEdit(item) {
      if (item) {
        this.title = "Editar Tarefa";
      } else {
        this.title = "Nova Tarefa";
      }
    }
  },
  methods: {
    close(event) {
      this.$emit("dialog:close", event);
    }
  }
};
</script>
