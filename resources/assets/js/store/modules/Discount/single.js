function initialState() {
    return {
        item: {
            id: null,
            discount: null,
            active: true,
            for_new_users: true
        },
        loading: false
    }
}

const getters = {
    item: state => state.item,
    loading: state => state.loading
};

const actions = {
    resetState({ commit }) {
        commit('resetState')
    },
    storeData({ commit, state, dispatch }) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.post('/discounts', {
                discount: state.item.discount,
                active: state.item.active,
                for_new_users: state.item.for_new_users
            })
                .then(response => {
                    commit('resetState');
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
                    commit('setLoading', false)
                })
        })
    },
    updateData({ commit, state, dispatch }, id) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.post('/discounts/' + id, {
                discount: state.item.discount,
                active: state.item.active,
                for_new_users: state.item.for_new_users
            })
                .then(response => {
                    commit('setItem', response.data.data);
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
                    commit('setLoading', false)
                })
        })
    },
    fetchData({ commit, dispatch }, id) {
        return new Promise((resolve, reject) => {
            axios.get('/discounts/' + id)
                .then(response => {
                    commit('setItem', response.data.data);
                    resolve();
                })
        });

    },
    setDiscount({ commit }, value) {
        commit('setDiscount', value)
    },
    setActive({ commit }, value) {
        commit('setActive', value)
    },
    setForNewUsers({ commit }, value) {
        commit('setForNewUsers', value)
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
    },
    setDiscount(state, value) {
        state.item.discount = value
    },
    setActive(state, value) {
        state.item.active = value
    },
    setForNewUsers(state, value) {
        state.item.for_new_users = value
    }
};

export default {
    namespaced: true,
    state: initialState,
    getters,
    actions,
    mutations
}
