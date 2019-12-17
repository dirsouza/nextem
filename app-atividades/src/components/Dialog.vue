<template>
  <v-dialog
    v-model="showDialog"
    persistent
    max-width="500px"
  >
    <Form
      :title="title"
      @dialog:close="close"
    />
  </v-dialog>
</template>

<script>
import Form from "./Form";
import { mapGetters } from "vuex";

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
  },
  data() {
    return {
      showDialog: false,
      title: "Nova Tarefa"
    };
  },
  computed: {
    ...mapGetters("activities", {
      getActivity: "getActivity"
    })
  },
  watch: {
    dialog(val) {
      this.showDialog = val;
    },
    getActivity(activity) {
      if (activity !== null) {
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
