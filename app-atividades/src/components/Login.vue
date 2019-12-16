<template>
  <v-app>
    <v-content>
      <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
          <v-col cols="12" sm="8" md="4">
            <v-card class="elevation-12">
              <v-toolbar color="primary" dark flat>
                <v-toolbar-title>Atividades</v-toolbar-title>
              </v-toolbar>
              <v-card-text>
                <v-form>
                  <v-text-field
                    v-model="email"
                    label="E-mail"
                    name="login"
                    type="text"
                  />

                  <v-text-field
                    v-mode="password"
                    label="Senha"
                    name="password"
                    type="password"
                  />
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-btn color="primary" @click="loginUser">Entrar</v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
import { mapActions } from "vuex";

export default {
  name: "login",
  data() {
    return {
      email: "test@nextem.com.br",
      password: "1234"
    };
  },
  methods: {
    ...mapActions("auth", {
      login: "login"
    }),
    async loginUser() {
      try {
        await this.login({
          email: this.email,
          password: this.password
        });

        this.$router.push("/");
      } catch (error) {
        console.log(error);
      }
    }
  }
};
</script>

<style scoped></style>
