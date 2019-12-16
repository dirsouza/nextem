<template>
  <v-data-table
    :headers="headers"
    :items="desserts"
    sort-by="calories"
    class="elevation-1"
  >
    <template v-slot:top>
      <v-toolbar flat color="white">
        <v-toolbar-title>Lista de Tarafas</v-toolbar-title>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <Dialog :dialog="dialog" />
      </v-toolbar>
    </template>
    <template v-slot:item.action="{ item }">
      <v-icon small class="mr-2" @click="editItem(item)">
        edit
      </v-icon>
      <v-icon small @click="deleteItem(item)">
        delete
      </v-icon>
    </template>
  </v-data-table>
</template>

<script>
import Dialog from "./Dialog";

export default {
  name: "Activities",
  components: {
    Dialog
  },
  data() {
    return {
      dialog: false,
      headers: [
        { text: "Atividade", value: "activity" },
        { text: "Responsável", value: "users" },
        { text: "Status", value: "status" },
        { text: "Prazo", value: "deadline" },
        { text: null, value: "action", sortable: false }
      ]
    };
  }
};
</script>
