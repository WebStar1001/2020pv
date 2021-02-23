function initialState() {
    return {
        email: null,
        displayName: null,
        password: null,
        loading: false,
        advisor: null,
        payAmount: 10,
        subscribed: true,
        termsAndConditions: false,
        adult: false,
    }
}

const getters = {
    loading: state => state.loading,
    email: state => state.email,
    displayName: state => state.displayName,
    password: state => state.password,
    advisor: state => state.advisor,
    subscribed: state => state.subscribed,
    termsAndConditions: state => state.termsAndConditions,
    adult: state => state.adult,
    payAmount: state => state.payAmount,
};

const actions = {
    getAdvisor({ commit, state }, slug) {
        // commit('setLoading', true);

        return new Promise((resolve, reject) => {
            axios.get('/get-profile/' + slug + '?' + $.param(state.query) + '&limit=0&offset=0&sort=id&order=desc')
                .then(response => {
                    commit('setAdvisor', response.data);
                    resolve();
                })
                .finally(() => {
                    commit('setLoading', false)
                })
        });
    },
    register({ commit, state, dispatch }, role_id) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.post('/register', {
                role_id: role_id,
                email: state.email,
                display_name: state.displayName,
                password: state.password,
                subscribed: state.subscribed,
                lead: !!sessionStorage.getItem('specialoffer')
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
    pay({ commit, state, dispatch }, advisor_id) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        axios.post('/payment', {
            amount: state.payAmount,
            redirect_url: '/step-register/' + advisor_id + '/chat'
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
    },
    setAdvisor({ commit }, value) {
        commit('setAdvisor', value)
    },
    setPayAmount({ commit }, value) {
        commit('setPayAmount', value)
    },
    setSubscribed({ commit }, value) {
        commit('setSubscribed', value)
    },
    setTermsAndConditions({ commit }, value) {
        commit('setTermsAndConditions', value)
    },
    setAdult({ commit }, value) {
        commit('setAdult', value)
    },
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
    },
    setAdvisor(state, value) {
        state.advisor = value
    },
    setPayAmount(state, value) {
        state.payAmount = value
    },
    setSubscribed(state, value) {
        state.subscribed = value
    },
    setTermsAndConditions(state, value) {
        state.termsAndConditions = value
    },
    setAdult(state, value) {
        state.adult = value
    },
};

export default {
    namespaced: true,
    state: initialState,
    getters,
    actions,
    mutations
}
