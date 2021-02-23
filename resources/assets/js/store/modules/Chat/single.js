function initialState() {
    return {
        chatSessionId: '',
        activeSession: null,
        messages: [],
        newMessage: '',
        chatPaidSeconds: 0,
        chatFreeSeconds: 0,
        duration: 0,
        loading: false,
        advisor: null,
        customer: null,
        payAmount: 10,
        filePath: '',
        rating: 0,
        reviewText: null,
        reportComment: null,
        pricePerMinute: 0,
        chatStarted: false,
        chatPaused: false,
        chatDeleted: false,
        pauseReason: null,
        discount: 0,
    }
}

const getters = {
    chatSessionId: state => state.chatSessionId,
    activeSession: state => state.activeSession,
    messages: state => state.messages,
    newMessage: state => state.newMessage,
    chatPaidSeconds: state => state.chatPaidSeconds,
    chatFreeSeconds: state => state.chatFreeSeconds,
    duration: state => state.duration,
    loading: state => state.loading,
    advisor: state => state.advisor,
    customer: state => state.customer,
    filePath: state => state.filePath,
    rating: state => state.rating,
    reviewText: state => state.reviewText,
    reportComment: state => state.reportComment,
    pricePerMinute: state => state.pricePerMinute,
    chatStarted: state => state.chatStarted,
    chatPaused: state => state.chatPaused,
    chatDeleted: state => state.chatDeleted,
    pauseReason: state => state.pauseReason,
    payAmount: state => state.payAmount,
    discount: state => state.discount,
};

const actions = {
    setChatSessionId({ commit }, value) {
        commit('setChatSessionId', value)
    },
    setActiveSession({ commit }, value) {
        commit('setActiveSession', value)
    },
    setChatFreeSeconds({ commit }, value) {
        commit('setChatFreeSeconds', value)
    },
    setChatPaidSeconds({ commit }, value) {
        commit('setChatPaidSeconds', value)
    },
    fetchData({ commit, state, dispatch }, initial = 0) {
        // commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.get('/chat/' + state.chatSessionId + '/data?initial=' + initial)
                .then(response => {
                    commit('setActiveSession', response.data.activeSession);
                    commit('setChatPaidSeconds', response.data.paidSeconds);
                    commit('setChatFreeSeconds', response.data.freeSeconds);
                    commit('setDuration', response.data.duration);
                    commit('setMessages', response.data.messages);
                    commit('setAdvisor', response.data.advisor);
                    commit('setCustomer', response.data.customer);
                    commit('setPricePerMinute', response.data.price_per_minute);
                    commit('setChatStarted', response.data.chatStarted);
                    commit('setChatPaused', response.data.chatPaused);
                    commit('setChatDeleted', response.data.chatDeleted);
                    commit('setPauseReason', response.data.pauseReason);
                    commit('setDiscount', response.data.discount);
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
                    // commit('setLoading', false);
                })
        });
    },
    sendMessage({ commit, state, dispatch }, user) {
        let message = state.newMessage;

        if (state.filePath) {
            message = '<p><a href="/storage/' + state.filePath + '" download><img src="/storage/' + state.filePath + '" alt="" class="img-fluid"></a></p>';
            message += state.newMessage;
        }

        dispatch('setFilePath', '');

        commit('pushMessage', {
            chat_session_id: state.chatSessionId,
            message: message,
            user: user,
            created_at: moment.utc().format('YYYY-MM-DD HH:mm:ss')
        });

        axios.post('/chat/' + state.chatSessionId + '/messages', {message: message})
            .then(response => {
                if (response.data.status === 'error') {
                    dispatch('fetchData');
                }
            })
            .catch(error => {
                let message = error.response.data.message || error.message;
                commit('setError', message)
            })
            .finally(() => {
            });

        commit('setNewMessage', '')
    },
    pushMessage({ commit }, value) {
        commit('pushMessage', value)
    },
    setNewMessage({ commit }, value) {
        commit('setNewMessage', value)
    },
    resetState({ commit }) {
        commit('resetState')
    },
    endChat({ commit, state, dispatch }, reason) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.post('/chat/' + state.chatSessionId + '/end', {reason: reason})
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
                    commit('setLoading', false);
                })
        });
    },
    setAdvisor({ commit }, advisor) {
        commit('setAdvisor', advisor)
    },
    setCustomer({ commit }, customer) {
        commit('setCustomer', customer)
    },
    setPayAmount({ commit }, amount) {
        commit('setPayAmount', amount)
    },
    pay({ commit, state, dispatch }) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        axios.post('/payment', {
            amount: state.payAmount,
            redirect_url: 'admin/chat/' + state.chatSessionId,
            chat_session_id: state.chatSessionId
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
    setFilePath({ commit }, path) {
        commit('setFilePath', path)
    },
    setRating({ commit }, value) {
        commit('setRating', value)
    },
    setReviewText({ commit }, value) {
        commit('setReviewText', value)
    },
    submitReview({ commit, state, dispatch }) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.post('/chat/' + state.chatSessionId + '/review', {
                rating: state.rating,
                text: state.reviewText
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
                    commit('setLoading', false);
                })
        });
    },
    setReportComment({ commit }, value) {
        commit('setReportComment', value)
    },
    submitReport({ commit, state, dispatch }) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.post('/chat/' + state.chatSessionId + '/report', {
                comment: state.reportComment
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
                    commit('setLoading', false);
                })
        });
    },
    moneyIsOver({ commit, state, dispatch }) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.post('/chat/' + state.chatSessionId + '/out-of-money', {

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
                    commit('setLoading', false);
                })
        });
    },
    advisorIsInactive({ commit, state, dispatch }, wait = false) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.post('/chat/' + state.chatSessionId + '/advisor-is-inactive', {
                wait: wait
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
                    commit('setLoading', false);
                })
        });
    },
    setPricePerMinute({ commit }, value) {
        commit('setPricePerMinute', value)
    },
    setChatStarted({ commit }, value) {
        commit('setChatStarted', value)
    },
    setChatPaused({ commit }, value) {
        commit('setChatPaused', value)
    },
    setChatDeleted({ commit }, value) {
        commit('setChatDeleted', value)
    },
    setPauseReason({ commit }, value) {
        commit('setPauseReason', value)
    },
    setDiscount({ commit }, value) {
        commit('setDiscount', value)
    },
};

const mutations = {
    setChatSessionId(state, value) {
        state.chatSessionId = value
    },
    setActiveSession(state, value) {
        state.activeSession = value
    },
    setMessages(state, items) {
        state.messages = items
    },
    pushMessage(state, data) {
        state.messages.push({
            chat_session_id: data.chat_session_id,
            user: data.user,
            message: data.message,
            created_at: data.created_at
        });
    },
    setNewMessage(state, value) {
        state.newMessage = value
    },
    setChatFreeSeconds(state, minutes) {
        state.chatFreeSeconds = minutes;
    },
    setChatPaidSeconds(state, minutes) {
        state.chatPaidSeconds = minutes;
    },
    setDuration(state, seconds) {
        state.duration = seconds;
    },
    setLoading(state, loading) {
        state.loading = loading
    },
    resetState(state) {
        state = Object.assign(state, initialState())
    },
    setAdvisor(state, advisor) {
        state.advisor = advisor
    },
    setCustomer(state, customer) {
        state.customer = customer
    },
    setPayAmount(state, amount) {
        state.payAmount = amount
    },
    setFilePath(state, path) {
        state.filePath = path
    },
    setRating(state, value) {
        state.rating = value
    },
    setReviewText(state, value) {
        state.reviewText = value
    },
    setReportComment(state, value) {
        state.reportComment = value
    },
    setPricePerMinute(state, value) {
        state.pricePerMinute = value
    },
    setChatStarted(state, value) {
        state.chatStarted = value
    },
    setChatPaused(state, value) {
        state.chatPaused = value
    },
    setChatDeleted(state, value) {
        state.chatDeleted = value
    },
    setPauseReason(state, value) {
        state.pauseReason = value
    },
    setDiscount(state, value) {
        state.discount = value
    },
};

export default {
    namespaced: true,
    state: initialState,
    getters,
    actions,
    mutations
}
