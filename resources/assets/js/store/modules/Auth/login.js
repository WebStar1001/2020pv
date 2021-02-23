function initialState() {
    return {
        email: null,
        password: null,
        remember: false,
        loading: false
    }
}

const getters = {
    email: state => state.email,
    password: state => state.password,
    remember: state => state.remember,
    loading: state => state.loading
};

const actions = {
    login({ commit, state, dispatch } ) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.post('/login', {
                email: state.email,
                password: state.password
            })
                .then(response => {
                    // commit('resetState');

                    if (response.data.user && response.data.user.id) {
                        resolve(response.data)
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
    setPassword({ commit }, value) {
        commit('setPassword', value)
    },
    setRemember({ commit }, value) {
        commit('setRemember', value)
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
    setRemember(state, value) {
        state.remember = value
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
