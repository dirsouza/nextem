import axios from "../../plugins/axios";

export const Auth = {
  namespaced: true,
  state: {
    status: false,
    token: localStorage.getItem("token"),
    user: {}
  },
  mutations: {
    authSuccess(state, data) {
      state.status = true;
      state.token = data.access_token;
      state.user = data.user;
    },
    authError(state) {
      state.status = false;
    },
    logout(state) {
      state.status = false;
      state.token = "";
      state.user = {};
    }
  },
  getters: {
    authStatus: state => state.status
  },
  actions: {
    async login({ commit }, login) {
      try {
        const res = await axios.post("login", login);
        const { access_token, user } = res.data.data;
        localStorage.setItem("token", access_token);
        axios.defaults.headers["Authorization"] = `Bearer ${access_token}`;
        commit("authSuccess", { access_token, user });

        return res;
      } catch (e) {
        commit("authError");
        localStorage.removeItem("token");

        throw e;
      }
    },
    async logout({ commit }) {
      try {
        commit("logout");
        await axios.post("auth/logout");
        localStorage.removeItem("token");
        delete axios.defaults.headers["Authorization"];
      } catch (error) {
        console.log(error);
      }
    }
  }
};
