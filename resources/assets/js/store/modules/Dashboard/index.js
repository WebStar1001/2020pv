function initialState() {
    return {
        data: [],
        query: {},
        loading: false,
        filter: '',
        payAmount: 10,
    }
}

const getters = {
    data:          state => state.data,
    loading:       state => state.loading,
    payAmount: state => state.payAmount,
};

const actions = {
    fetchData({ commit, state }) {
        commit('setLoading', true);

        return new Promise((resolve, reject) => {
            axios.get('/dashboard?filter=' + state.filter + '&' + $.param(state.query))
                .then(response => {
                    commit('setData', response.data);
                    resolve(response);
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
                    commit('setLoading', false)
                })
        })

    },
    setQuery({ commit, dispatch }, value) {
        commit('setQuery', purify(value));
        dispatch('fetchData');
    },
    resetState({ commit }) {
        commit('resetState')
    },
    setFilter({ commit, dispatch }, value) {
        commit('setFilter', value);
    },
    setPayAmount({ commit }, value) {
        commit('setPayAmount', value);
    },
    pay({ commit, state, dispatch }) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        axios.post('/payment', {
            amount: state.payAmount,
            redirect_url: 'customer/thank-you',
            cancel_url: 'admin/dashboard'
        }).then(resp => {
            if (resp.data) {
                window.location = resp.data;
            } else {
                console.log('Something went wrong!');
                this.payLoading = false;
            }
        }).catch(error => {
            let message = error.response.data.message || error.message;
            let errors  = error.response.data.errors;

            dispatch(
                'Alert/setAlert',
                { message: message, errors: errors, color: 'danger' },
                { root: true });

            reject(error)
        }).finally(() => {

        })
    },
};

const mutations = {
    setData(state, data) {
        state.data = data
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
    setFilter(state, value) {
        state.filter = value
    },
    setPayAmount(state, value) {
        state.payAmount = value
    },
};

export default {
    namespaced: true,
    state: initialState,
    getters,
    actions,
    mutations
}
