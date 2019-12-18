import Vue from "vue";
import Vuex from "vuex";
import createPersistedState from "vuex-persistedstate";
import { Auth, Activities, Snackbar } from "./modules";

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    auth: Auth,
    activities: Activities,
    snackbar: Snackbar
  },
  plugins: [createPersistedState()]
});
