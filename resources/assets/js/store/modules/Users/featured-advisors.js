function initialState() {
    return {
        featuredAdvisors: [],
        topRatedAdvisors: [],
        topPaidAdvisors: [],
        newAdvisors: [],
        total: 0,
        query: {},
        loading: false,
    }
}

const getters = {
    featuredAdvisors: state => state.featuredAdvisors,
    topRatedAdvisors: state => state.topRatedAdvisors,
    topPaidAdvisors: state => state.topPaidAdvisors,
    newAdvisors: state => state.newAdvisors,
    total: state => state.total,
    loading: state => state.loading,
};

const actions = {
    fetchData({ commit, state, dispatch }) {
        commit('setLoading', true);

        axios.get('/featured-advisors?' + $.param(state.query))
            .then(response => {
                commit('setFeaturedAdvisors', response.data.featured_advisors);
                // commit('setTopRatedAdvisors', response.data.top_rated_advisors);
                // commit('setTopPaidAdvisors', response.data.top_paid_advisors);
                // commit('setNewAdvisors', response.data.new_advisors);
                commit('setTotal', response.data.total);
            })
            .catch(error => {
                let message = error.response.data.message || error.message;
                commit('setError', message)
            })
            .finally(() => {
                // commit('setLoading', false)
            })
    },
    setQuery({ commit, dispatch }, value) {
        commit('setQuery', purify(value.query));
    },
    resetState({ commit }) {
        commit('resetState')
    },
};

const mutations = {
    setFeaturedAdvisors(state, items) {
        state.featuredAdvisors = items
    },
    setTopRatedAdvisors(state, items) {
        state.topRatedAdvisors = items
    },
    setTopPaidAdvisors(state, items) {
        state.topPaidAdvisors = items
    },
    setNewAdvisors(state, items) {
        state.newAdvisors = items
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
    },
};

export default {
    namespaced: true,
    state: initialState,
    getters,
    actions,
    mutations
}
