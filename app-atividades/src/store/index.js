import Vue from "vue";
import Vuex from "vuex";
import createPersistedState from "vuex-persistedstate";
import auth from "./modules/auth";
import activities from "./modules/activities";

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    auth,
    activities
  },
  plugins: [createPersistedState()]
});
