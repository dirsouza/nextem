<template>
  <div>
    <v-card>
      <v-card-title>
        <span class="headline">{{ getTitle }}</span>
      </v-card-title>

      <v-card-text>
        <v-container>
          <v-row>
            <v-col cols="12" sm="12" md="12">
              <v-text-field
                v-model="activity"
                label="Atividade"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="12" md="12">
              <v-select
                v-model="responsible"
                :items="listResponsible"
                attach
                chips
                label="Responsável"
                item-text="name"
                item-value="id"
                multiple
              ></v-select>
            </v-col>
            <v-col cols="12" sm="12" md="6">
              <v-select
                v-model="status"
                :items="listStatus"
                label="Status"
                item-text="name"
                item-value="id"
              ></v-select>
            </v-col>
            <v-col cols="12" sm="12" md="6">
              <v-menu
                v-model="datePicker"
                :close-on-content-click="false"
                :nudge-right="40"
                lazy
                transition="scale-transition"
                offset-y
                full-width
                min-width="290px"
              >
                <template v-slot:activator="{ on }">
                  <v-text-field
                    :value="deadline | dateFormat"
                    label="Prazo"
                    readonly
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker locale="pt-br" v-model="deadline" @input="datePicker = false"></v-date-picker>
              </v-menu>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>

      <v-card-actions>
        <v-spacer/>
        <v-btn color="blue darken-1" text @click="close">Cancelar</v-btn>
        <v-btn color="blue darken-1" text @click="save">Salvar</v-btn>
      </v-card-actions>
    </v-card>
    <Snackbar
      :snackbar="snackbar.show"
      :message="snackbar.message"
      :type="snackbar.type"
    />
  </div>
</template>

<script>
import moment from "moment";
import Snackbar from "./Snackbar";

export default {
  name: "Form",
  components: {
    Snackbar
  },
  props: {
    title: {
      type: String,
      required: true
    },
    itemEdit: {
      type: Object,
      required: false
    }
  },
  data() {
    return {
      activity: null,
      responsible: [],
      status: null,
      deadline: new Date().toISOString().substr(0, 10),
      listResponsible: [],
      listStatus: [],
      datePicker: false,
      snackbar: {
        show: false,
        message: "",
        type: ""
      }
    };
  },
  created() {
    this.getResponsible();
    this.getStatus();
  },
  watch: {
    itemEdit(item) {
      if (item) {
        this.activity = item.activity;
        this.responsible = Object.assign([], item.users.map(u => u.id));
        this.status = item.status.id;
        this.deadline = item.deadline;
      }
    }
  },
  computed: {
    getTitle() {
      return this.title;
    }
  },
  filters: {
    dateFormat(date) {
      return moment(date, "YYYY-MM-DD").format("DD/MM/YYYY");
    }
  },
  methods: {
    async getResponsible() {
      try {
        this.listResponsible = await this.$axios.post("activity/responsible")
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
    async getStatus() {
      try {
        this.listStatus = await this.$axios.post("activity/status")
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
    close() {
      this.clearForm();
      this.$emit("dialog:close", false);
    },
    async save() {
      if (this.itemEdit === null || Object.keys(this.itemEdit).length <= 0) {
        await this.create();
      } else {
        await this.update(this.itemEdit.id);
      }
    },
    async create() {
      try {
        await this.$axios.post("activity/create", {
          activity: this.activity,
          responsible: this.responsible,
          status: this.status,
          deadline: this.deadline
        });

        this.close();
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
    async update(id) {
      try {
        await this.$axios.post(`activity/${id}/update`, {
          activity: this.activity,
          responsible: this.responsible,
          status: this.status,
          deadline: this.deadline
        });

        this.close();
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
    clearForm() {
      this.activity = null;
      this.responsible = [];
      this.status = null;
      this.deadline = new Date().toISOString().substr(0, 10);
      this.datePicker = false;
    }
  }
};
</script>
