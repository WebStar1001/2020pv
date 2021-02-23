function initialState() {
    return {
        reviews: [],
        loading: false,
        total: 0,
        query: {},
    }
}

const getters = {
    reviews: state => state.reviews,
    total: state => state.total,
    loading: state => state.loading
};

const actions = {
    setQuery({ commit, dispatch }, value) {
        commit('setQuery', purify(value));
    },
    resetState({ commit }) {
        commit('resetState')
    },
    fetchReviews({ commit, state, dispatch }, id) {
        if (state.query) {
            return new Promise((resolve, reject) => {
                axios.get('/users/' + id + '/reviews?' + $.param(state.query))
                    .then(response => {
                        commit('setReviews', response.data.data);
                        commit('setTotal', response.data.total);
                        resolve();
                    })
            });
        }

    },
    deleteReview({ commit, state }, id) {
        axios.delete('/reviews/' + id)
            .then(response => {
                commit('setReviews', state.reviews.filter((item) => {
                    return item.id != id
                }))
            })
            .catch(error => {
                let message = error.response.data.message || error.message;
                commit('setError', message)
            })
    },
};

const mutations = {
    setTotal(state, total) {
        state.total = total
    },
    setQuery(state, query) {
        state.query = query
    },
    resetState(state) {
        state = Object.assign(state, initialState())
    },
    setReviews(state, items) {
        state.reviews = items
    },
};

export default {
    namespaced: true,
    state: initialState,
    getters,
    actions,
    mutations
}
