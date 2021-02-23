function initialState() {
    return {
        all: [],
        total: 0,
        relationships: {

        },
        query: {},
        loading: false,
        checkedAdvisors: [],
        payoutItemDetails: null
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
        return '0.00'
    }
}

const getters = {
    data:          state => state.all,
    total:         state => state.total,
    loading:       state => state.loading,
    relationships: state => state.relationships,
    checkedAdvisors: state => state.checkedAdvisors,
    payoutItemDetails: state => state.payoutItemDetails
};

const actions = {
    fetchData({ commit, state }) {
        commit('setLoading', true);

        axios.get('/payouts/advisors?' + $.param(state.query))
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
    setCheckedAdvisor({ commit, dispatch }, id) {
        commit('setCheckedAdvisor', id);
    },
    setUncheckedAdvisor({ commit, dispatch }, id) {
        commit('setUncheckedAdvisor', id);
    },
    makePayouts({ commit, state, dispatch }, ids) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {

            axios.post('/payouts/advisors', {ids: ids})
                .then(response => {
                    resolve()
                })
                .catch(error => {
                    let message = error.response.data.message || error.message;
                    let errors  = error.response.data.errors;

                    dispatch(
                        'Alert/setAlert',
                        { message: message, errors: errors, color: 'danger' },
                        { root: true });

                    reject(error)
                })
                .finally(() => {
                    commit('setLoading', false);
                    commit('resetCheckedAdvisors');
                    dispatch('fetchData');
                })
        })
    },
    cancelUnclaimedPayout({ commit, state, dispatch }, id) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {

            axios.post('/payouts/payout-item/' + id + '/cancel', {})
                .then(response => {
                    resolve()
                })
                .catch(error => {
                    let message = error.response.data.message || error.message;
                    let errors  = error.response.data.errors;

                    dispatch(
                        'Alert/setAlert',
                        { message: message, errors: errors, color: 'danger' },
                        { root: true });

                    reject(error)
                })
                .finally(() => {
                    commit('setLoading', false);
                    commit('resetCheckedAdvisors');
                    dispatch('fetchData');
                })
        })
    },
    retryFailedPayout({ commit, state, dispatch }, id) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {

            axios.post('/payouts/payout-item/' + id + '/retry', {})
                .then(response => {
                    resolve()
                })
                .catch(error => {
                    let message = error.response.data.message || error.message;
                    let errors  = error.response.data.errors;

                    dispatch(
                        'Alert/setAlert',
                        { message: message, errors: errors, color: 'danger' },
                        { root: true });

                    reject(error)
                })
                .finally(() => {
                    commit('setLoading', false);
                    commit('resetCheckedAdvisors');
                    dispatch('fetchData');
                })
        })
    },
    getPayoutItemDetails({ commit, state }, id) {
        commit('setLoading', true);
        commit('setPayoutItemDetails', null);

        axios.get('/payouts/payout-item/' + id)
            .then(response => {
                commit('setPayoutItemDetails', response.data);
            })
            .catch(error => {
                let message = error.response.data.message || error.message;
                commit('setError', message)
            })
            .finally(() => {
                commit('setLoading', false)
            })
    },
    resetPayoutItemDetails({ commit, state, dispatch }) {
        commit('resetPayoutItemDetails')
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
    setCheckedAdvisor(state, id) {
        state.checkedAdvisors.push(id);
    },
    setUncheckedAdvisor(state, id) {
        let index = state.checkedAdvisors.indexOf(id);
        state.checkedAdvisors.splice(index, 1);
    },
    resetCheckedAdvisors(state) {
        state.checkedAdvisors = []
    },
    setPayoutItemDetails(state, value) {
        state.payoutItemDetails = value
    },
    resetPayoutItemDetails(state) {
        state.payoutItemDetails = null
    }
};

export default {
    namespaced: true,
    state: initialState,
    getters,
    actions,
    mutations
}
