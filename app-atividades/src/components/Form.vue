<template>
  <div>
    <ValidationObserver ref="form" v-slot="{ invalid, passes }">
      <v-form>
        <v-card>
          <v-card-title>
            <span class="headline">{{ getTitle }}</span>
          </v-card-title>

          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12" sm="12" md="12">
                  <ValidationProvider  name="Atividade" rules="required|min:5" v-slot="{ errors }">
                    <v-text-field
                      v-model="activity"
                      label="Atividade"
                      clearable
                      :error-messages="errors"
                    ></v-text-field>
                  </ValidationProvider>
                </v-col>
                <v-col cols="12" sm="12" md="12">
                  <ValidationProvider name="Responsável" rules="required" v-slot="{ errors }">
                    <v-select
                      v-model="responsible"
                      :items="listResponsible"
                      attach
                      chips
                      label="Responsável"
                      item-text="name"
                      item-value="id"
                      multiple
                      clearable
                      :error-messages="errors"
                    ></v-select>
                  </ValidationProvider>
                </v-col>
                <v-col cols="12" sm="12" md="6">
                  <ValidationProvider name="Status" rules="required" v-slot="{ errors }">
                    <v-select
                      v-model="status"
                      :items="listStatus"
                      label="Status"
                      item-text="name"
                      item-value="id"
                      clearable
                      :error-messages="errors"
                    ></v-select>
                  </ValidationProvider>
                </v-col>
                <v-col cols="12" sm="12" md="6">
                  <ValidationProvider name="Prazo" rules="required" v-slot="{ errors }">
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
                  </ValidationProvider>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>

          <v-card-actions>
            <v-spacer/>
            <v-btn color="blue darken-1" text @click="close">Cancelar</v-btn>
            <v-btn color="blue darken-1" text @click="passes(save)" :disabled="invalid">Salvar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </ValidationObserver>
    <Snackbar/>
  </div>
</template>

<script>
import moment from "moment";
import Snackbar from "./Snackbar";
import { ValidationObserver, ValidationProvider } from "vee-validate";
import { mapActions, mapGetters, mapMutations } from "vuex";

export default {
  name: "Form",
  components: {
    Snackbar,
    ValidationObserver,
    ValidationProvider
  },
  props: {
    title: {
      type: String,
      required: true
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
      datePicker: false
    };
  },
  created() {
    this.getResponsible();
    this.getStatus();
  },
  computed: {
    ...mapGetters("activities", {
      getActivity: "getActivity"
    }),
    getTitle() {
      return this.title;
    }
  },
  watch: {
    getActivity(activity) {
      if (activity !== null) {
        this.activity = activity.activity;
        this.responsible = Object.assign([], activity.users.map(u => u.id));
        this.status = activity.status.id;
        this.deadline = activity.deadline;
      }
    }
  },
  filters: {
    dateFormat(date) {
      return moment(date, "YYYY-MM-DD").format("DD/MM/YYYY");
    }
  },
  methods: {
    ...mapActions("snackbar", {
      showSnackbar: "showSnackbar"
    }),
    ...mapMutations("activities", {
      clearActivity: "clearActivity"
    }),
    async getResponsible() {
      try {
        this.listResponsible = await this.$axios.post("activity/responsible")
          .then(res => res.data.data);
      } catch (e) {
        this.showSnackbar({
          show: true,
          message: "Não foi possível carregar a lista de Responsáveis!",
          type: "error"
        });
      }
    },
    async getStatus() {
      try {
        this.listStatus = await this.$axios.post("activity/status")
          .then(res => res.data.data);
      } catch (e) {
        this.showSnackbar({
          show: true,
          message: "Não foi possível carregar a lista de Status!",
          type: "error"
        });
      }
    },
    async save() {
      if (this.getActivity === null) {
        await this.create();
      } else {
        await this.update(this.getActivity.id);
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

        this.showSnackbar({
          show: true,
          message: "Tarefa criada com sucesso!",
          type: "success"
        });
      } catch (e) {
        this.showSnackbar({
          show: true,
          message: "Não foi possível criar a tarefa!",
          type: "error"
        });
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

        this.showSnackbar({
          show: true,
          message: "Tarefa atualizada com sucesso!",
          type: "success"
        });
      } catch (e) {
        this.showSnackbar({
          show: true,
          message: "Não foi possível atualizar a tarefa!",
          type: "error"
        });
      }
    },
    close() {
      this.$refs.form.reset();
      this.clearActivity();
      this.clearForm();
      this.$emit("dialog:close", false);
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
