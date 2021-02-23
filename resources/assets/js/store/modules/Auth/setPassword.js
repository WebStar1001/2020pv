function initialState() {
    return {
        email: null,
        password: null,
        confirmPassword: null,
        loading: false
    }
}

const getters = {
    email: state => state.email,
    password: state => state.password,
    confirmPassword: state => state.confirmPassword,
    loading: state => state.loading
};

const actions = {
    resetPassword({ commit, state, dispatch }, token ) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.post('/set-password', {
                token: token,
                email: state.email,
                password: state.password,
                password_confirmation: state.confirmPassword
            })
                .then(response => {
                    commit('resetState');

                    let error = '';

                    switch (response.data) {
                        case 'passwords.token':
                            error = 'The password reset link is no longer valid';
                            break;
                        case 'passwords.user':
                            error = 'User in not found';
                            break;
                        case 'passwords.password':
                            error = 'Invalid Password';
                    }

                    if (error) {
                        dispatch(
                            'Alert/setAlert',
                            {message: error, color: 'danger'},
                            {root: true});
                    }

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
    setEmail({ commit }, value) {
        commit('setEmail', value)
    },
    setPassword({ commit }, value) {
        commit('setPassword', value)
    },
    setConfirmPassword({ commit }, value) {
        commit('setConfirmPassword', value)
    },
    resetState({ commit }) {
        commit('resetState')
    }
};

const mutations = {
    setEmail(state, value) {
        state.email = value
    },
    setPassword(state, value) {
        state.password = value
    },
    setConfirmPassword(state, value) {
        state.confirmPassword = value
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
