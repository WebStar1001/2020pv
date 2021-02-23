function initialState() {
    return {
        item: null,
        loading: false,
    }
}

const getters = {
    item: state => state.item,
    loading: state => state.loading,
    rolesAll: state => state.rolesAll,
};

const actions = {
    resetState({ commit }) {
        commit('resetState')
    },
    fetchData({ commit, state }, id) {
        commit('setLoading', true);

        axios.get('/disputes/' + id)
            .then(response => {
                commit('setItem', response.data);
            })
            .catch(error => {
                let message = error.response.data.message || error.message;
                commit('setError', message)
            })
            .finally(() => {
                commit('setLoading', false)
            })
    },
};

const mutations = {
    resetState(state) {
        state = Object.assign(state, initialState())
    },
    setLoading(state, loading) {
        state.loading = loading
    },
    setItem(state, item) {
        state.item = item
    }
};

export default {
    namespaced: true,
    state: initialState,
    getters,
    actions,
    mutations
}
