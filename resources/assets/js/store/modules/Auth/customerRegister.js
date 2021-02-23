function initialState() {
    return {
        email: null,
        displayName: null,
        password: null,
        loading: false
    }
}

const getters = {
    loading: state => state.loading,
    email: state => state.email,
    displayName: state => state.displayName,
    password: state => state.password
};

const actions = {
    register({ commit, state, dispatch }, role_id) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.post('/register', {
                role_id: role_id,
                email: state.email,
                display_name: state.displayName,
                password: state.password,
                lead: !!sessionStorage.getItem('specialoffer')
            })
                .then(response => {
                    // commit('resetState');
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
    setEmail({ commit }, value) {
        commit('setEmail', value)
    },
    setDisplayName({ commit }, value) {
        commit('setDisplayName', value)
    },
    setPassword({ commit }, value) {
        commit('setPassword', value)
    },
    resetState({ commit }) {
        commit('resetState')
    }
};

const mutations = {
    setEmail(state, value) {
        state.email = value
    },
    setDisplayName(state, value) {
        state.displayName = value
    },
    setPassword(state, value) {
        state.password = value
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
