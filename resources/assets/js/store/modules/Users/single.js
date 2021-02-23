function initialState() {
    return {
        item: {
            id: null,
            role_id: null,
            full_name: null,
            display_name: null,
            slug: null,
            email: null,
            payment_email: null,
            password: null,
            description: null,
            price_per_minute: null,
            commission_percentage: null,
            avatar: null,
            free_minutes_enabled: null,
            approved: null,
            decline_reason: null,
            subscribed: null,
            blocked: null,
            top_advisor: null,
            rank: null,
            readings: null
        },
        loading: false,
        avatar: '',
        changeBalanceOperator: '+',
        changeBalanceAmount: null,
        changeBalanceNote: null
    }
}

const getters = {
    item: state => state.item,
    loading: state => state.loading,
    rolesAll: state => state.rolesAll,
    changeBalanceOperator: state => state.changeBalanceOperator,
    changeBalanceAmount: state => state.changeBalanceAmount,
    changeBalanceNote: state => state.changeBalanceNote
};

const actions = {
    resetState({ commit }) {
        commit('resetState')
    },
    storeData({ commit, state, dispatch }) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            const config = {
                headers: {'content-type': 'multipart/form-data'}
            };
            let formData = new FormData();

            let params = _.cloneDeep(state.item);

            for (let key in params) {
                if (params.hasOwnProperty(key)) {
                    if (params[key]) {
                        formData.append(key, params[key]);
                    }
                }
            }

            formData.append('avatar', state.avatar);

            axios.post('/users', formData, config)
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
    updateData({ commit, state, dispatch }) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            const config = {
                headers: {'content-type': 'multipart/form-data'}
            };
            let formData = new FormData();

            let params = _.cloneDeep(state.item);

            for (let key in params) {
                if (params.hasOwnProperty(key)) {
                    if (params[key] !== null) {
                        if (key !== 'avatar') {
                            formData.append(key, params[key]);
                        }
                    }
                }
            }

            formData.append('_method', 'PUT');
            if (state.avatar) {
                formData.append('avatar', state.avatar);
            } else {
                if (params['avatar'] === '' || params['avatar'] === null) {
                    formData.append('avatar', '');
                }
            }

            axios.post('/users/' + params.id, formData, config)
                .then(response => {
                    commit('setItem', response.data.data);
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
    fetchData({ commit, dispatch }, id) {
        return new Promise((resolve, reject) => {
            axios.get('/users/' + id)
                .then(response => {
                    commit('setItem', response.data.data);
                    resolve();
                })
        });

    },
    approveAccount({ commit, state, dispatch }) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.post('/users/' + state.item.id + '/approve', {})
                .then(response => {
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
    declineAccount({ commit, state, dispatch }) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.post('/users/' + state.item.id + '/decline', {
                decline_reason: state.item.decline_reason
            })
                .then(response => {
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
    changeBalance({ commit, state, dispatch }) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.post('/users/' + state.item.id + '/change-balance', {
                operator: state.changeBalanceOperator,
                amount: state.changeBalanceAmount,
                note: state.changeBalanceNote
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
                    commit('setLoading', false);
                })
        })
    },
    loginAsUser({ commit, state, dispatch }) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.post('/users/' + state.item.id + '/login-as-user', {})
                .then(response => {
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
    loginToSuperadmin({ commit, state, dispatch }, user_id) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.post('/users/' + sessionStorage.getItem('loggedAsUser') + '/login-as-superadmin', {
                user_id: user_id
            })
                .then(response => {
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
    setDisplayName({ commit }, value) {
        commit('setDisplayName', value)
    },
    setSlug({ commit }, value) {
        commit('setSlug', value)
    },
    setEmail({ commit }, value) {
        commit('setEmail', value)
    },
    setPaymentEmail({ commit }, value) {
        commit('setPaymentEmail', value)
    },
    setRole({ commit }, value) {
        commit('setRole', value)
    },
    setPassword({ commit }, value) {
        commit('setPassword', value)
    },
    setDescription({ commit }, value) {
        commit('setDescription', value)
    },
    setPricePerMinute({ commit }, value) {
        commit('setPricePerMinute', value)
    },
    setCommissionPercentage({ commit }, value) {
        commit('setCommissionPercentage', value)
    },
    setAvatar({ commit }, value) {
        commit('setAvatar', value)
    },
    setFreeMinutesEnabled({ commit }, value) {
        commit('setFreeMinutesEnabled', value)
    },
    setApproved({ commit }, value) {
        commit('setApproved', value)
    },
    setDeclineReason({ commit }, value) {
        commit('setDeclineReason', value)
    },
    setSubscribed({ commit }, value) {
        commit('setSubscribed', value)
    },
    setBlocked({ commit }, value) {
        commit('setBlocked', value)
    },
    setFullName({ commit }, value) {
        commit('setFullName', value)
    },
    setTopAdvisor({ commit }, value) {
        commit('setTopAdvisor', value)
    },
    setRank({ commit }, value) {
        commit('setRank', value)
    },
    setChangeBalanceOperator({ commit }, value) {
        commit('setChangeBalanceOperator', value)
    },
    setChangeBalanceAmount({ commit }, value) {
        commit('setChangeBalanceAmount', value)
    },
    setChangeBalanceNote({ commit }, value) {
        commit('setChangeBalanceNote', value)
    },
    setDeleted({ commit }, value) {
        commit('setDeleted', value)
    },
    setReadings({ commit }, value) {
        commit('setReadings', value)
    },
};

const mutations = {
    resetState(state) {
        state = Object.assign(state, initialState())
    },
    setItem(state, item) {
        state.item = item
    },
    setDisplayName(state, value) {
        state.item.display_name = value
    },
    setSlug(state, value) {
        state.item.slug = value
    },
    setEmail(state, value) {
        state.item.email = value
    },
    setPaymentEmail(state, value) {
        state.item.payment_email = value
    },
    setPassword(state, value) {
        state.item.password = value
    },
    setLoading(state, loading) {
        state.loading = loading
    },
    setRole(state, value) {
        state.item.role_id = value
    },
    setDescription(state, value) {
        state.item.description = value
    },
    setPricePerMinute(state, value) {
        state.item.price_per_minute = value
    },
    setCommissionPercentage(state, value) {
        state.item.commission_percentage = value
    },
    setAvatar(state, value) {
        state.item.avatar = null;
        state.avatar = value;
    },
    setApproved(state, value) {
        state.item.approved = value
    },
    setDeclineReason(state, value) {
        state.item.decline_reason = value
    },
    setFreeMinutesEnabled(state, value) {
        state.item.free_minutes_enabled = value
    },
    setSubscribed(state, value) {
        state.item.subscribed = value
    },
    setBlocked(state, value) {
        state.item.blocked = value
    },
    setFullName(state, value) {
        state.item.full_name = value
    },
    setTopAdvisor(state, value) {
        state.item.top_advisor = value
    },
    setRank(state, value) {
        state.item.rank = value
    },
    setChangeBalanceOperator(state, value) {
        state.changeBalanceOperator = value
    },
    setChangeBalanceAmount(state, value) {
        state.changeBalanceAmount = value
    },
    setChangeBalanceNote(state, value) {
        state.changeBalanceNote = value
    },
    setDeleted(state, value) {
        state.item.deleted = value
    },
    setReadings(state, value) {
        state.item.readings = value
    },
};

export default {
    namespaced: true,
    state: initialState,
    getters,
    actions,
    mutations
}
