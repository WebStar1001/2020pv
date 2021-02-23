function initialState() {
    return {
        loading: false,
        subscribed: null
    }
}

const getters = {
    loading: state => state.loading,
    subscribed: state => state.subscribed,
};

const actions = {
    resetState({ commit }) {
        commit('resetState')
    },
    updateNotifications({ commit, state, dispatch }) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.post('/settings/notifications', {
                subscribed: state.subscribed
            })
                .then(response => {
                    // commit('resetState');
                    resolve(response)
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
    setSubscribed({ commit }, value) {
        commit('setSubscribed', value)
    },
};

const mutations = {
    resetState(state) {
        state = Object.assign(state, initialState())
    },
    setLoading(state, loading) {
        state.loading = loading
    },
    setSubscribed(state, value) {
        state.subscribed = value
    },
};

export default {
    namespaced: true,
    state: initialState,
    getters,
    actions,
    mutations
}
