function initialState() {
    return {
        loading: false,
        subject: '',
        message: '',
    }
}

const getters = {
    loading: state => state.loading,
    subject: state => state.subject,
    message: state => state.message
};

const actions = {
    resetState({ commit }) {
        commit('resetState')
    },
    createSubject({ commit, state, dispatch }, slug) {
        commit('setLoading', true);

        return new Promise((resolve, reject) => {
            axios.post('/messages/' + slug + '/new-subject', {
                subject: state.subject,
                message: state.message
            })
                .then(response => {
                    resolve(response)
                })
                .catch(error => {
                    let message = error.response.data.message || error.message;
                    commit('setError', message)
                })
                .finally(() => {
                    commit('setLoading', false)
                });
         });
    },
    setSubject({ commit }, value) {
        commit('setSubject', value)
    },
    setMessage({ commit }, value) {
        commit('setMessage', value)
    },
};

const mutations = {
    resetState(state) {
        state = Object.assign(state, initialState())
    },
    setLoading(state, loading) {
        state.loading = loading
    },
    setSubject(state, value) {
        state.subject = value
    },
    setMessage(state, value) {
        state.message = value
    },
};

export default {
    namespaced: true,
    state: initialState,
    getters,
    actions,
    mutations
}
