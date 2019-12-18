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
          <v-spacer />
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
      @dialog:close="close()"
    />

    <Snackbar />
  </div>
</template>

<script>
import moment from "moment";
import Dialog from "./Dialog";
import Snackbar from "./Snackbar";
import { mapActions, mapMutations } from "vuex";

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
      listActivities: []
    };
  },
  created() {
    this.getActivities();
  },
  filters: {
    userFormat(users) {
      const listNameUser = Object.assign([], users.map(u => u.name));

      return listNameUser.join(", ");
    },
    dateFormat(date) {
      return moment(date, "YYYY-MM-DD").format("DD/MM/YYYY");
    }
  },
  methods: {
    ...mapActions("snackbar", {
      showSnackbar: "showSnackbar"
    }),
    ...mapMutations("activities", {
      setActivity: "setActivity",
      clearActivity: "clearActivity"
    }),
    async getActivities() {
      try {
        this.listActivities = await this.$axios.post("activity/activities")
          .then(res => res.data.data);
      } catch (e) {
        this.showSnackbar({
          show: true,
          message: "Não foi possível carregar a lista de tarefas!",
          type: "error"
        });
      }
    },
    editItem(activity) {
      this.setActivity(activity);
      this.dialog = true;
    },
    deleteItem(id) {
      this.$swal.fire({
        title: "Excluir Tarefa",
        text: "Confirme para excluir!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Confirmar",
        cancelButtonAriaLabel: "Cancelar"
      })
      .then(result => {
        if (result.value) {
          this.$axios.post(`activity/${id}/delete`)
            .then(() => {
              this.getActivities();

              this.showSnackbar({
                show: true,
                message: "Tarefa excluída com sucesso!",
                type: "success"
              });
            })
            .catch(() => {
              this.showSnackbar({
                show: true,
                message: "Não foi possível excluir a tarefa!",
                type: "error"
              });
            });
        }
      });
    },
    close(event) {
      this.getActivities();
      this.clearActivity();
      this.dialog = event;
    }
  }
};
</script>
