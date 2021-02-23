function initialState() {
    return {
        advisors: [],
        customers: [],
        loading: false
    }
}

const getters = {
    advisors: state => state.advisors,
    customers: state => state.customers,
    loading: state => state.loading
};

const actions = {
    fetchAdvisors({ commit, state, dispatch }) {
        commit('setLoading', true);

        axios.get('/get-advisors')
            .then(response => {
                commit('setAdvisors', response.data);
            })
            .catch(error => {
                let message = error.response.data.message || error.message;
                commit('setError', message)
            })
            .finally(() => {
                commit('setLoading', false)
            })
    },
    fetchCustomers({ commit, state, dispatch }) {
        commit('setLoading', true);

        axios.get('/get-customers')
            .then(response => {
                commit('setCustomers', response.data);
            })
            .catch(error => {
                let message = error.response.data.message || error.message;
                commit('setError', message)
            })
            .finally(() => {
                commit('setLoading', false)
            })
    },
    resetState({ commit }) {
        commit('resetState')
    }
};

const mutations = {
    setAdvisors(state, items) {
        state.advisors = items
    },
    setCustomers(state, items) {
        state.customers = items
    },
    setLoading(state, loading) {
        state.loading = loading
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
