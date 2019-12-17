export const Activities = {
  namespaced: true,
  state: {
    activity: {}
  },
  mutations: {
    setActivity: (state, data) => {
      state.activity = {
        ...data
      };
    },
    clearActivity: (state) => {
      state.activity = {};
    }
  },
  getters: {
    getActivity: state => {
      if (Object.entries(state.activity).length) {
        return state.activity;
      }

      return null;
    }
  }
};
