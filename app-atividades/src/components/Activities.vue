<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="listActivities"
      sort-by="activity"
      class="elevation-2"
    >
      <template v-slot:top>
        <v-toolbar flat color="white">
          <v-toolbar-title>Lista de Tarafas</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer/>
          <v-btn color="primary" dark class="mb-2" @click="dialog = true">Nova Tarefa</v-btn>
        </v-toolbar>
      </template>

      <template v-slot:item="{ item }">
        <tr>
          <td class="text-left">{{ item.activity }}</td>
          <td class="text-left">{{ item.users | userFormat }}</td>
          <td>{{ item.status.name }}</td>
          <td>{{ item.deadline | dateFormat }}</td>
          <td>
            <v-icon small class="mr-2" @click="editItem(item)">
              mdi-pencil
            </v-icon>
            <v-icon small @click="deleteItem(item.id)">
              mdi-delete
            </v-icon>
          </td>
        </tr>
      </template>
    </v-data-table>

    <Dialog
      :dialog="dialog"
      :item-edit="itemEdit"
      @dialog:close="close()"
    />

    <Snackbar
      :snackbar="snackbar.show"
      :message="snackbar.message"
      :type="snackbar.type"
    />
  </div>
</template>

<script>
import moment from "moment";
import Dialog from "./Dialog";
import Snackbar from "./Snackbar";

export default {
  name: "Activities",
  components: {
    Dialog,
    Snackbar
  },
  data() {
    return {
      dialog: false,
      headers: [
        { text: "Atividade", value: "activity" },
        { text: "Responsável", value: "users" },
        { text: "Status", value: "status", align: "center" },
        { text: "Prazo", value: "deadline", align: "center" },
        { text: "Ações", value: "action", align: "center", sortable: false, filterable: false }
      ],
      listActivities: [],
      itemEdit: {},
      snackbar: {
        show: false,
        message: "",
        type: ""
      }
    };
  },
  created() {
    this.getActivities();
  },
  filters: {
    userFormat(users) {
      const listNameUser = Object.assign([], users.map(u => u.name));

      return listNameUser.join(', ');
    },
    dateFormat(date) {
      return moment(date, "YYYY-MM-DD").format("DD/MM/YYYY");
    }
  },
  methods: {
    async getActivities() {
      try {
        this.listActivities = await this.$axios.post("activity/activities")
          .then(res => res.data.data);
      } catch (e) {
        const res = e.response;

        if (res.status === 401 && res.data.message === "Token expirado!") {
          this.snackbar = {
            show: true,
            message: res.data.message || "Não autorizado",
            type: 'error'
          };

          this.$store.dispatch("auth/logout");
          this.$router.push("/login");
        }
      }
    },
    editItem(activity) {
      this.itemEdit = activity;
      this.dialog = true;
    },
    close(event) {
      this.getActivities();
      this.itemEdit = null;
      this.dialog = event;
    }
  }
};
</script>
