function initialState() {
    return {
        loading: false,
        review: {
            rating: null,
            text: null
        }
    }
}

const getters = {
    loading: state => state.loading,
    review: state => state.review,
};

const actions = {
    resetState({ commit }) {
        commit('resetState')
    },
    fetchData({ commit, state, dispatch }, id) {
        return new Promise((resolve, reject) => {
            axios.get('/reviews/' + id)
                .then(response => {
                    commit('setRating', response.data.data.rating);
                    commit('setText', response.data.data.text);
                    resolve();
                })
        });
    },
    updateData({ commit, state, dispatch }, id) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            axios.post('/reviews/' + id, {
                rating: state.review.rating,
                text: state.review.text
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
    setRating({ commit }, value) {
        commit('setRating', value)
    },
    setText({ commit }, value) {
        commit('setText', value)
    },
};

const mutations = {
    resetState(state) {
        state = Object.assign(state, initialState())
    },
    setLoading(state, loading) {
        state.loading = loading
    },
    setRating(state, value) {
        state.review.rating = value
    },
    setText(state, value) {
        state.review.text = value
    },
};

export default {
    namespaced: true,
    state: initialState,
    getters,
    actions,
    mutations
}
