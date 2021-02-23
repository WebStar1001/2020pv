function initialState() {
    return {
        advisor: null,
        query: {},
        loading: false,
    }
}

const getters = {
    advisor: state => state.advisor,
    loading: state => state.loading,
};

const actions = {
    // resetState({ commit }) {
    //     commit('resetState')
    // },
    fetchData({ commit, state, dispatch }, slug) {
        return new Promise((resolve, reject) => {
            axios.get('/get-profile/' + slug + '?' + $.param(state.query))
                .then(response => {
                    commit('setData', response.data);
                    resolve();
                })
        });
    },
    setQuery({ commit, dispatch }, value) {
        commit('setQuery', purify(value.query));
    },
    resetState({ commit }) {
        commit('resetState')
    }
};

const mutations = {
    // resetState(state) {
    //     state = Object.assign(state, initialState())
    // },
    setData(state, data) {
        state.advisor = data
    },
    setLoading(state, loading) {
        state.loading = loading
    },
    setQuery(state, query) {
        state.query = query
    },
    resetState(state) {
        state = Object.assign(state, initialState())
    }
};

export default {
    namespaced: true,
    state: initialState,
    getters,
    actions,
    mutations
}
