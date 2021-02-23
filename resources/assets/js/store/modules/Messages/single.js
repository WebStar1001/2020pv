function initialState() {
    return {
        loading: false,
        acceptLoading: false,
        rejectLoading: false,
        subjectId: null,
        subject: null,
        messages: [],
        newMessage: '',
        advisor: null,
        customer: null,
        payAmount: 10,
        invoiceAmount: 10,
        filePath: '',
        reportComment: null,
        rating: 0,
        reviewText: null,
    }
}

const getters = {
    loading: state => state.loading,
    acceptLoading: state => state.acceptLoading,
    rejectLoading: state => state.rejectLoading,
    subject: state => state.subject,
    messages: state => state.messages,
    newMessage: state => state.newMessage,
    advisor: state => state.advisor,
    customer: state => state.customer,
    payAmount: state => state.payAmount,
    invoiceAmount: state => state.invoiceAmount,
    filePath: state => state.filePath,
    reportComment: state => state.reportComment,
    rating: state => state.rating,
    reviewText: state => state.reviewText,
};

const actions = {
    fetchData({ commit, state, dispatch }) {
        // commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.get('/subjects/' + state.subjectId + '/messages')
                .then(response => {
                    commit('setSubject', response.data.subject);
                    commit('setMessages', response.data.messages);
                    commit('setCustomer', response.data.customer);
                    commit('setAdvisor', response.data.advisor);
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
            email_subject_id: state.subjectId,
            message: message,
            sender_id: user.id,
            sender: user,
            created_at: moment.utc().format('YYYY-MM-DD HH:mm:ss')
        });

        axios.post('/subjects/' + state.subjectId + '/messages', {message: message})
            .then(response => {

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
    setAdvisor({ commit }, advisor) {
        commit('setAdvisor', advisor)
    },
    setCustomer({ commit }, customer) {
        commit('setCustomer', customer)
    },
    setPayAmount({ commit }, amount) {
        commit('setPayAmount', amount)
    },
    setInvoiceAmount({ commit }, amount) {
        commit('setInvoiceAmount', amount)
    },
    pay({ commit, state, dispatch }) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        axios.post('/payment', {
            amount: state.payAmount,
            redirect_url: 'admin/messages/' + state.subjectId,
            chat_session_id: state.subjectId
        }).then(resp => {
                if (resp.data) {
                    window.location = resp.data;
                } else {
                    console.log('Something went wrong!');
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
    sendInvoice({ commit, state, dispatch }) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        commit('pushMessage', {
            email_subject_id: state.subjectId,
            message: null,
            sender_id: state.advisor.id,
            sender: state.advisor,
            created_at: moment.utc().format('YYYY-MM-DD HH:mm:ss'),
            invoice_amount: state.invoiceAmount
        });

        axios.post('/subjects/' + state.subjectId + '/invoice', {
            amount: state.invoiceAmount
        }).then(resp => {

        }).catch(error => {
            let message = error.response.data.message || error.message;
            let errors  = error.response.data.errors;

            dispatch(
                'Alert/setAlert',
                { message: message, errors: errors, color: 'danger' },
                { root: true });

            reject(error)
        }).finally(() => {
            commit('setLoading', false);
        })
    },
    cancelInvoice({ commit, state, dispatch }, invoice_id) {
        commit('setLoading', true);
        commit('setRejectLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        axios.post('/subjects/' + state.subjectId + '/invoice/' + invoice_id + '/cancel', {

        }).then(resp => {

        }).catch(error => {
            let message = error.response.data.message || error.message;
            let errors  = error.response.data.errors;

            dispatch(
                'Alert/setAlert',
                { message: message, errors: errors, color: 'danger' },
                { root: true });

            reject(error)
        }).finally(() => {
            commit('setLoading', false);
            commit('setRejectLoading', false);
        })
    },
    rejectInvoice({ commit, state, dispatch }, invoice_id) {
        commit('setLoading', true);
        commit('setRejectLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        axios.post('/subjects/' + state.subjectId + '/invoice/' + invoice_id + '/reject', {

        }).then(resp => {

        }).catch(error => {
            let message = error.response.data.message || error.message;
            let errors  = error.response.data.errors;

            dispatch(
                'Alert/setAlert',
                { message: message, errors: errors, color: 'danger' },
                { root: true });

            reject(error)
        }).finally(() => {
            commit('setLoading', false);
            commit('setRejectLoading', false);
        })
    },
    acceptInvoice({ commit, state, dispatch }, invoice_id) {
        commit('setLoading', true);
        commit('setAcceptLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.post('/subjects/' + state.subjectId + '/invoice/' + invoice_id + '/accept', {}).then(resp => {
                resolve(resp);
            }).catch(error => {
                let message = error.response.data.message || error.message;
                let errors  = error.response.data.errors;

                dispatch(
                    'Alert/setAlert',
                    { message: message, errors: errors, color: 'danger' },
                    { root: true });

                reject(error)
            }).finally(() => {
                commit('setLoading', false);
                commit('setAcceptLoading', false);
            });
        });
    },
    updateReadStatus({ commit, state, dispatch }) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.post('/subjects/' + state.subjectId + '/read', {}).then(resp => {
                resolve(resp);
            }).catch(error => {
                let message = error.response.data.message || error.message;
                let errors  = error.response.data.errors;

                dispatch(
                    'Alert/setAlert',
                    { message: message, errors: errors, color: 'danger' },
                    { root: true });

                reject(error)
            }).finally(() => {
                commit('setLoading', false);
            });
        });
    },
    setFilePath({ commit }, path) {
        commit('setFilePath', path)
    },
    setReportComment({ commit }, value) {
        commit('setReportComment', value)
    },
    setSubjectId({ commit }, value) {
        commit('setSubjectId', value)
    },
    submitReport({ commit, state, dispatch }) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.post('/subjects/' + state.subjectId + '/report', {
                comment: state.reportComment
            })
                .then(response => {
                    resolve();
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
    setSubject({ commit }, value) {
        commit('setSubject', value)
    },
    setMessages({ commit }, value) {
        commit('setMessages', value)
    },
    setRating({ commit }, value) {
        commit('setRating', value)
    },
    setReviewText({ commit }, value) {
        commit('setReviewText', value)
    },
    submitReview({ commit, state, dispatch }, message_id) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.post('/subjects/' + state.subjectId + '/review', {
                message_id: message_id,
                rating: state.rating,
                text: state.reviewText
            })
                .then(response => {
                    resolve();
                    commit('setRating', 0);
                    commit('setReviewText', null);
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
};

const mutations = {
    setLoading(state, loading) {
        state.loading = loading
    },
    setAcceptLoading(state, loading) {
        state.acceptLoading = loading
    },
    setRejectLoading(state, loading) {
        state.rejectLoading = loading
    },
    resetState(state) {
        state = Object.assign(state, initialState())
    },
    setSubject(state, data) {
        state.subject = data
    },
    setMessages(state, items) {
        state.messages = items
    },
    pushMessage(state, data) {
        state.messages.push({
            id: data.id,
            email_subject_id: data.email_subject_id,
            sender_id: data.sender_id,
            sender: data.sender,
            message: data.message,
            created_at: data.created_at,
            invoice_amount: data.invoice_amount
        });
    },
    setNewMessage(state, value) {
        state.newMessage = value
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
    setInvoiceAmount(state, amount) {
        state.invoiceAmount = amount
    },
    setFilePath(state, path) {
        state.filePath = path
    },
    setReportComment(state, value) {
        state.reportComment = value
    },
    setSubjectId(state, value) {
        state.subjectId = value
    },
    setRating(state, value) {
        state.rating = value
    },
    setReviewText(state, value) {
        state.reviewText = value
    },
};

export default {
    namespaced: true,
    state: initialState,
    getters,
    actions,
    mutations
}
