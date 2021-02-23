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

function toFixed(num, fixed) {
    if (num) {
        if (Math.round(num) === 0) {
            return '0.00';
        }

        const re = new RegExp('^-?\\d+(?:\.\\d{0,' + (fixed || -1) + '})?');
        return Math.abs(num).toString().match(re)[0];
    } else {
        return '0.00';
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
        commit('setLoading', true);

        axios.get('/disputes?' + $.param(state.query))
            .then(response => {
                response.data.data.map(row => {
                    row.balance = '$' + toFixed(parseFloat(row.balance), 2);
                });

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
    setQuery({ commit, dispatch }, value) {
        commit('setQuery', purify(value));
        dispatch('fetchData');
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
