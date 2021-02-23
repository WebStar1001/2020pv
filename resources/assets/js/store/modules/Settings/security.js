function initialState() {
    return {
        loading: false,
        currentPassword: null,
        newPassword: null,
        deletePassword: null
    }
}

const getters = {
    loading: state => state.loading,
    currentPassword: state => state.currentPassword,
    newPassword: state => state.newPassword,
    deletePassword: state => state.deletePassword,
};

const actions = {
    resetState({ commit }) {
        commit('resetState')
    },
    updateSecurity({ commit, state, dispatch }, role_id) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.post('/settings/security', {
                current_password: state.currentPassword,
                new_password: state.newPassword
            })
                .then(response => {
                    commit('resetState');
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
    deleteAccount({ commit, state, dispatch }, role_id) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.post('/settings/security/delete-account', {
                password: state.deletePassword
            })
                .then(response => {
                    commit('resetState');
                    resolve(response)
                })
                .catch(error => {
                    console.log(error);
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
    setCurrentPassword({ commit }, value) {
        commit('setCurrentPassword', value)
    },
    setNewPassword({ commit }, value) {
        commit('setNewPassword', value)
    },
    setDeletePassword({ commit }, value) {
        commit('setDeletePassword', value)
    },
};

const mutations = {
    resetState(state) {
        state = Object.assign(state, initialState())
    },
    setLoading(state, loading) {
        state.loading = loading
    },
    setCurrentPassword(state, value) {
        state.currentPassword = value
    },
    setNewPassword(state, value) {
        state.newPassword = value
    },
    setDeletePassword(state, value) {
        state.deletePassword = value
    },
};

export default {
    namespaced: true,
    state: initialState,
    getters,
    actions,
    mutations
}
