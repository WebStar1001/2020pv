function initialState() {
    return {
        email: null,
        loading: false
    }
}

const getters = {
    email: state => state.email,
    loading: state => state.loading
};

const actions = {
    resetPassword({ commit, state, dispatch } ) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.post('/reset-password', {
                email: state.email
            })
                .then(response => {
                    commit('resetState');
                    resolve();

                    if (response.data === true) {
                        dispatch(
                            'Alert/setAlert',
                            { message: 'Reset Password Link has been successfully sent', color: 'success' },
                            { root: true });
                    } else {
                        dispatch(
                            'Alert/setAlert',
                            { message: response.data, color: 'danger' },
                            { root: true });
                    }
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
    setEmail({ commit }, value) {
        commit('setEmail', value)
    },
    resetState({ commit }) {
        commit('resetState')
    }
};

const mutations = {
    setEmail(state, value) {
        state.email = value
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
