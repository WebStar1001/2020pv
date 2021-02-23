function initialState() {
    return {
        all: [],
        total: 0,
        query: {},
        loading: false,
        slug: null,
        maxPricePerMinute: 1,
        priceValues: null,
        freeSeconds: 0,
    }
}

const getters = {
    advisors:      state => state.all,
    total:         state => state.total,
    loading:       state => state.loading,
    relationships: state => state.relationships,
    maxPricePerMinute:  state => state.maxPricePerMinute,
    priceValues:  state => state.priceValues,
    freeSeconds:  state => state.freeSeconds,
};

const actions = {
    fetchData({ commit, state, dispatch }) {
        // commit('setLoading', true);

        axios.get('/all-advisors?' + $.param(state.query))
            .then(response => {
                commit('setAll', response.data.advisors);
                commit('setTotal', response.data.total);

                if (!state.priceValues) {
                    commit('setMaxPricePerMinute', response.data.max_price_per_minute / 100);
                    commit('setPriceValues', [1, response.data.max_price_per_minute / 100]);
                }
            })
            .catch(error => {
                let message = error.response.data.message || error.message;
                commit('setError', message)
            })
            .finally(() => {
                // commit('setLoading', false)
            })
    },
    getFreeMinutes({ commit, state, dispatch }, advisor_id) {
        commit('setLoading', true);
        commit('setFreeSeconds', null);

        return new Promise((resolve, reject) => {
            axios.get('/users/' + advisor_id + '/free-minutes')
                .then(response => {
                    commit('setFreeSeconds', response.data);
                    resolve();
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
    addOrRemoveFavoriteAdvisor({ commit, state, dispatch }, advisor_id) {
        return new Promise((resolve, reject) => {
            axios.post('/users/' + advisor_id + '/favorite')
                .then(response => {
                    resolve();
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
        commit('setSlug', value.slug);
    },
    resetState({ commit }) {
        commit('resetState')
    },
    setMaxPricePerMinute({ commit }, value) {
        commit('setMaxPricePerMinute', value);
    },
    setPriceValues({ commit }, value) {
        commit('setPriceValues', value);
    },
    setFreeSeconds({ commit }, value) {
        commit('setFreeSeconds', value);
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
    },
    setSlug(state, slug) {
        state.slug = slug
    },
    setMaxPricePerMinute(state, value) {
        state.maxPricePerMinute = value
    },
    setPriceValues(state, value) {
        state.priceValues = value
    },
    setFreeSeconds(state, value) {
        state.freeSeconds = value
    },
};

export default {
    namespaced: true,
    state: initialState,
    getters,
    actions,
    mutations
}
