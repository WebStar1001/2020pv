function initialState() {
    return {
        subjects: null,
        advisors: [],
        total: 0,
        query: {},
        loading: false
    }
}

const getters = {
    subjects:      state => state.subjects,
    advisors:      state => state.advisors,
    total:         state => state.total,
    loading:       state => state.loading,
};

const actions = {
    fetchData({ commit, state }) {
        commit('setLoading', true);

        axios.get('/subjects?' + $.param(state.query))
            .then(response => {
                commit('setSubjects', response.data.subjects);
                // commit('setTotal', response.data.total);
            })
            .catch(error => {
                let message = error.response.data.message || error.message;
                commit('setError', message)
            })
            .finally(() => {
                commit('setLoading', false)
            })
    },
    fetchAdvisors({ commit, state }) {
        commit('setLoading', true);

        return new Promise((resolve, reject) => {
            axios.get('/subjects/advisors')
                .then(response => {
                    commit('setAdvisors', response.data.advisors);
                    resolve()
                })
                .catch(error => {
                    let message = error.response.data.message || error.message;
                    commit('setError', message)
                })
                .finally(() => {
                    commit('setLoading', false)
                })
        });

    },
    setQuery({ commit, dispatch }, value) {
        commit('setQuery', purify(value.query));
        dispatch('fetchData');
    },
    resetState({ commit }) {
        commit('resetState')
    },
};

const mutations = {
    setSubjects(state, items) {
        state.subjects = items
    },
    setAdvisors(state, items) {
        state.advisors = items
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
