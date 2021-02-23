function initialState() {
    return {
        loading: false,
        pending: null,
        countries: [],
        country: null,
        newPaymentEmail: null,
        paypalPayment: null,
        bankDetails: {
            full_name: null,
            address: null,
            zipcode: null,
            iban: null,
            swift: null,
            bank_name: null
        }
    }
}

const getters = {
    loading: state => state.loading,
    pending: state => state.pending,
    newPaymentEmail: state => state.newPaymentEmail,
    countries: state => state.countries,
    country: state => state.country,
    paypalPayment: state => state.paypalPayment,
    bankDetails: state => state.bankDetails,
};

const actions = {
    resetState({ commit }) {
        commit('resetState')
    },
    fetchData({ commit, dispatch }) {
        commit('setLoading', true);

        return new Promise((resolve, reject) => {
            axios.get('/settings/payment')
                .then(response => {
                    commit('setPending', response.data.pending);
                    commit('setCountries', response.data.countries);
                    commit('setCountry', response.data.country);
                    commit('setPaypalPayment', response.data.paypal_payment);

                    if (response.data.bank_details) {
                        commit('setFullName', response.data.bank_details.full_name);
                        commit('setAddress', response.data.bank_details.address);
                        commit('setZipcode', response.data.bank_details.zipcode);
                        commit('setIban', response.data.bank_details.iban);
                        commit('setSwift', response.data.bank_details.swift);
                        commit('setBankName', response.data.bank_details.bank_name);
                    }

                    resolve();
                })
                .finally(() => {
                    commit('setLoading', false)
                })
        });

    },
    updatePayment({ commit, state, dispatch }) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.post('/settings/payment', {
                email: state.newPaymentEmail,
                country: state.country,
                paypal_payment: state.paypalPayment,
                bank_details: state.bankDetails
            })
                .then(response => {
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
    setPending({ commit }, value) {
        commit('setPending', value)
    },
    setNewPaymentEmail({ commit }, value) {
        commit('setNewPaymentEmail', value)
    },
    setCountries({ commit }, value) {
        commit('setCountries', value)
    },
    setCountry({ commit }, value) {
        commit('setCountry', value)
    },
    setPaypalPayment({ commit }, value) {
        commit('setPaypalPayment', value)
    },
    setFullName({ commit }, value) {
        commit('setFullName', value)
    },
    setAddress({ commit }, value) {
        commit('setAddress', value)
    },
    setZipcode({ commit }, value) {
        commit('setZipcode', value)
    },
    setIban({ commit }, value) {
        commit('setIban', value)
    },
    setSwift({ commit }, value) {
        commit('setSwift', value)
    },
    setBankName({ commit }, value) {
        commit('setBankName', value)
    },
};

const mutations = {
    resetState(state) {
        state = Object.assign(state, initialState())
    },
    setLoading(state, loading) {
        state.loading = loading
    },
    setPending(state, value) {
        state.pending = value
    },
    setNewPaymentEmail(state, value) {
        state.newPaymentEmail = value
    },
    setCountries(state, value) {
        state.countries = value
    },
    setCountry(state, value) {
        state.country = value
    },
    setPaypalPayment(state, value) {
        state.paypalPayment = value
    },
    setFullName(state, value) {
        state.bankDetails.full_name = value
    },
    setAddress(state, value) {
        state.bankDetails.address = value
    },
    setZipcode(state, value) {
        state.bankDetails.zipcode = value
    },
    setIban(state, value) {
        state.bankDetails.iban = value
    },
    setSwift(state, value) {
        state.bankDetails.swift = value
    },
    setBankName(state, value) {
        state.bankDetails.bank_name = value
    },
};

export default {
    namespaced: true,
    state: initialState,
    getters,
    actions,
    mutations
}
