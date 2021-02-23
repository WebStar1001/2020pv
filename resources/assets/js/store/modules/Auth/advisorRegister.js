function initialState() {
    return {
        email: null,
        fullName: null,
        displayName: null,
        country: null,
        avatar: null,
        resume: null,
        loading: false,
        subscribed: true,
        termsAndConditions: false,
        adult: false,
        countries: [],
    }
}

const getters = {
    loading: state => state.loading,
    fullName: state => state.fullName,
    displayName: state => state.displayName,
    email: state => state.email,
    country: state => state.country,
    subscribed: state => state.subscribed,
    termsAndConditions: state => state.termsAndConditions,
    adult: state => state.adult,
    countries: state => state.countries
};

const actions = {
    resetState({ commit }) {
        commit('resetState')
    },
    fetchData({ commit, state }) {
        commit('setLoading', true);

        axios.get('/countries')
            .then(response => {
                commit('setCountries', response.data);
            })
            .catch(error => {
                let message = error.response.data.message || error.message;
                commit('setError', message)
            })
            .finally(() => {
                commit('setLoading', false)
            })
    },
    register({ commit, state, dispatch }, role_id) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            const config = {
                headers: {'content-type': 'multipart/form-data'}
            };
            let formData = new FormData();

            formData.append('role_id', role_id);
            formData.append('full_name', state.fullName);
            formData.append('display_name', state.displayName);
            formData.append('email', state.email);
            formData.append('country', state.country);
            formData.append('avatar', state.avatar);
            formData.append('resume', state.resume);
            formData.append('subscribed', state.subscribed);

            axios.post('/psychic-register', formData, config)
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
    setFullName({ commit }, value) {
        commit('setFullName', value)
    },
    setDisplayName({ commit }, value) {
        commit('setDisplayName', value)
    },
    setEmail({ commit }, value) {
        commit('setEmail', value)
    },
    setCountry({ commit }, value) {
        commit('setCountry', value)
    },
    setAvatar({ commit }, value) {
        commit('setAvatar', value)
    },
    setResume({ commit }, value) {
        commit('setResume', value)
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
    setCountries({ commit }, items) {
        commit('setCountries', items)
    },
    setCountry({ commit }, value) {
        commit('setCountry', value)
    },
};

const mutations = {
    resetState(state) {
        state = Object.assign(state, initialState())
    },
    setLoading(state, loading) {
        state.loading = loading
    },
    setFullName(state, value) {
        state.fullName = value
    },
    setDisplayName(state, value) {
        state.displayName = value
    },
    setEmail(state, value) {
        state.email = value
    },
    setCountry(state, value) {
        state.country = value
    },
    setAvatar(state, value) {
        state.avatar = value;
    },
    setResume(state, value) {
        state.resume = value;
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
    setCountries(state, items) {
        state.countries = items
    },
    setCountry(state, value) {
        state.country = value
    },
};

export default {
    namespaced: true,
    state: initialState,
    getters,
    actions,
    mutations
}
