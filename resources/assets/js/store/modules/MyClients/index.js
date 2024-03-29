function initialState() {
    return {
        all: [],
        total: 0,
        relationships: {

        },
        query: {},
        loading: false
    }
}

const getters = {
    data:          state => state.all,
    total:         state => state.total,
    loading:       state => state.loading,
    relationships: state => state.relationships
};

const actions = {
    fetchData({ commit, state }) {
        // commit('setLoading', true);

        axios.get('/my-clients?' + $.param(state.query))
            .then(response => {
                commit('setAll', response.data.data);
                commit('setTotal', response.data.total);
            })
            .catch(error => {
                let message = error.response.data.message || error.message;
                commit('setError', message)
            })
            .finally(() => {
                commit('setLoading', false)
            })
    },
    blockCustomer({ commit, dispatch, state }, customer_id) {
        commit('setLoading', true);

        axios.post('/users/' + customer_id + '/block')
            .then(response => {
                dispatch('fetchData');
            })
            .catch(error => {
                let message = error.response.data.message || error.message;
                commit('setError', message)
            })
            .finally(() => {
                commit('setLoading', false)
            })
    },
    unblockCustomer({ commit, dispatch, state }, customer_id) {
        commit('setLoading', true);

        axios.post('/users/' + customer_id + '/unblock')
            .then(response => {
                dispatch('fetchData');
            })
            .catch(error => {
                let message = error.response.data.message || error.message;
                commit('setError', message)
            })
            .finally(() => {
                commit('setLoading', false)
            })
    },
    setQuery({ commit, dispatch }, value) {
        commit('setQuery', purify(value.query));
        dispatch('fetchData');
    },
    setLoading({ commit }, value) {
        commit('setLoading', value)
    },
    resetState({ commit }) {
        commit('resetState')
    },
};

const mutations = {
    setAll(state, items) {
        state.all = items
    },
    setTotal(state, total) {
        state.total = total
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
