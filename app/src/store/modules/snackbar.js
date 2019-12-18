export const Snackbar = {
  namespaced: true,
  state: {
    snackbar: {
      show: false,
      message: "",
      type: ""
    }
  },
  mutations: {
    setSnackbar: (state, data) => {
      state.snackbar = {
        ...data
      };
    },
    clearSnackbar: (state) => {
      state.snackbar = {
        show: false,
        message: "",
        type: ""
      };
    }
  },
  getters: {
    getSnackbar: state => state.snackbar
  },
  actions: {
    showSnackbar: ({ commit }, data) => {
      if (
        !data.hasOwnProperty("show")
        || !data.hasOwnProperty("message")
        || !data.hasOwnProperty("type")
      ) {
        throw new Error("Propriedade inválida");
      }

      commit("setSnackbar", data);
    }
  }
};
