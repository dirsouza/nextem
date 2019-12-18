<template>
  <v-app>
    <v-content>
      <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
          <v-col cols="12" sm="8" md="4">
            <ValidationObserver v-slot="{ invalid, validated, passes }">
              <v-card class="elevation-12">
                <v-toolbar color="primary" dark flat>
                  <v-toolbar-title>Atividades</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                  <v-form>
                    <ValidationProvider name="E-mail" rules="required|email" v-slot="{ errors }">
                      <v-text-field
                        v-model="email"
                        label="E-mail"
                        name="login"
                        type="text"
                        :error-messages="errors"
                      />
                    </ValidationProvider>

                    <ValidationProvider name="Senha" rules="required" v-slot="{ errors }">
                      <v-text-field
                        v-model="password"
                        label="Senha"
                        name="password"
                        type="password"
                        :error-messages="errors"
                      />
                    </ValidationProvider>
                  </v-form>
                </v-card-text>
                <v-card-actions>
                  <v-btn color="primary" @click="passes(loginUser)" :disabled="invalid || !validated">Entrar</v-btn>
                </v-card-actions>
              </v-card>
            </ValidationObserver>
          </v-col>
        </v-row>
      </v-container>
    </v-content>

    <Snackbar/>
  </v-app>
</template>

<script>
import { mapActions } from "vuex";
import Snackbar from "./Snackbar";
import { ValidationObserver, ValidationProvider } from "vee-validate";

export default {
  name: "login",
  components: {
    Snackbar,
    ValidationObserver,
    ValidationProvider
  },
  data() {
    return {
      email: "",
      password: ""
    };
  },
  methods: {
    ...mapActions("auth", {
      login: "login"
    }),
    ...mapActions("snackbar", {
      showSnackbar: "showSnackbar"
    }),
    async loginUser() {
      try {
        await this.login({
          email: this.email,
          password: this.password
        });

        this.$router.push("/");
      } catch (e) {
        const res = e.response;

        if (res.status === 422) {
          const msg = res.data.error;

          this.showSnackbar({
            show: true,
            message: msg,
            type: "error"
          });
        } else {
          const msg = res.data.message;

          this.showSnackbar({
            show: true,
            message: msg.join(", "),
            type: "error"
          });
        }
      }
    }
  }
};
</script>
