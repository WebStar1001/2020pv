function initialState() {
    return {
        loading: false,
        item: {
            avatar: null,
            full_name: null,
            display_name: null,
            email: null,
            country: null,
            languages: null,
            horoscope_id: null,
            master_service_id: null,
            price_per_minute: null,
            experience_years: null,
            experience: null,
            highlight: null,
            description: null,
            about_services: null,
            qualification: null
        },
        avatar: '',
        countries: [],
        languages: [],
        horoscopes: [],
        masterServices: [],
        services: []
    }
}

function toFixed(num, fixed) {
    if (num) {
        if (Math.round(num) === 0) {
            return '0.00';
        }

        const re = new RegExp('^-?\\d+(?:\.\\d{0,' + (fixed || -1) + '})?');
        return Math.abs(num).toString().match(re)[0];
    } else {
        return '0.00'
    }
}

const getters = {
    loading: state => state.loading,
    item: state => state.item,
    countries: state => state.countries,
    languages: state => state.languages,
    horoscopes: state => state.horoscopes,
    masterServices: state => state.masterServices,
    services: state => state.services,
};

const actions = {
    resetState({ commit }) {
        commit('resetState')
    },
    fetchData({ commit, dispatch }) {
        return new Promise((resolve, reject) => {
            axios.get('/settings/general')
                .then(response => {
                    commit('setItem', response.data.user);
                    commit('setPricePerMinute', toFixed(response.data.user.price_per_minute / 100, 2));
                    commit('setCountries', response.data.countries);
                    commit('setLanguages', response.data.languages);
                    commit('setHoroscopes', response.data.horoscopes);
                    commit('setMasterServices', response.data.master_services);
                    commit('setServices', response.data.services);
                    resolve();
                })
        });

    },
    updateGeneralInformation({ commit, state, dispatch }, role_id) {
        commit('setLoading', true);
        dispatch('Alert/resetState', null, { root: true });

        return new Promise((resolve, reject) => {
            const config = {
                headers: {'content-type': 'multipart/form-data'}
            };
            let formData = new FormData();

            formData.append('full_name', state.item.full_name);
            formData.append('display_name', state.item.display_name);
            formData.append('country', state.item.country);
            formData.append('avatar', state.avatar);
            formData.append('languages', JSON.stringify(state.item.languages));
            formData.append('horoscope_id', state.item.horoscope_id);
            formData.append('master_service_id', state.item.master_service_id);
            formData.append('services', JSON.stringify(state.item.services));
            formData.append('price_per_minute', state.item.price_per_minute * 100);
            formData.append('experience_years', state.item.experience_years);
            formData.append('experience', state.item.experience);
            formData.append('highlight', state.item.highlight);
            formData.append('description', state.item.description);
            formData.append('about_services', state.item.about_services);
            formData.append('qualification', state.item.qualification);

            axios.post('/settings/general', formData, config)
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
    setFullName({ commit }, value) {
        commit('setFullName', value)
    },
    setDisplayName({ commit }, value) {
        commit('setDisplayName', value)
    },
    setEmail({ commit }, value) {
        commit('setEmail', value)
    },
    setAvatar({ commit }, value) {
        commit('setAvatar', value)
    },
    setCountries({ commit }, items) {
        commit('setCountries', items)
    },
    setCountry({ commit }, value) {
        commit('setCountry', value)
    },
    setLanguages({ commit }, items) {
        commit('setLanguages', items)
    },
    setSelectedLanguages({ commit }, items) {
        commit('setSelectedLanguages', items)
    },
    setHoroscopes({ commit }, items) {
        commit('setHoroscopes', items)
    },
    setHoroscope({ commit }, value) {
        commit('setHoroscope', value)
    },
    setMasterServices({ commit }, items) {
        commit('setMasterServices', items)
    },
    setMasterService({ commit }, value) {
        commit('setMasterService', value)
    },
    setServices({ commit }, items) {
        commit('setServices', items)
    },
    setSecondaryServices({ commit }, items) {
        commit('setSecondaryServices', items)
    },
    setPricePerMinute({ commit }, value) {
        commit('setPricePerMinute', value)
    },
    setExperienceYears({ commit }, value) {
        commit('setExperienceYears', value)
    },
    setExperience({ commit }, value) {
        commit('setExperience', value)
    },
    setHighlight({ commit }, value) {
        commit('setHighlight', value)
    },
    setDescription({ commit }, value) {
        commit('setDescription', value)
    },
    setAboutServices({ commit }, value) {
        commit('setAboutServices', value)
    },
    setQualification({ commit }, value) {
        commit('setQualification', value)
    },
};

const mutations = {
    resetState(state) {
        state = Object.assign(state, initialState())
    },
    setLoading(state, loading) {
        state.loading = loading
    },
    setItem(state, item) {
        state.item = item
    },
    setFullName(state, value) {
        state.item.full_name = value
    },
    setDisplayName(state, value) {
        state.item.display_name = value
    },
    setEmail(state, value) {
        state.item.email = value
    },
    setAvatar(state, value) {
        state.item.avatar = null;
        state.avatar = value;
    },
    setCountries(state, items) {
        state.countries = items
    },
    setCountry(state, value) {
        state.item.country = value
    },
    setLanguages(state, items) {
        state.languages = items
    },
    setSelectedLanguages(state, items) {
        state.item.languages = items
    },
    setHoroscopes(state, items) {
        state.horoscopes = items
    },
    setHoroscope(state, id) {
        state.item.horoscope_id = id
    },
    setMasterServices(state, items) {
        state.masterServices = items
    },
    setMasterService(state, id) {
        state.item.master_service_id = id
    },
    setServices(state, items) {
        state.services = items
    },
    setSecondaryServices(state, items) {
        state.item.services = items
    },
    setPricePerMinute(state, value) {
        state.item.price_per_minute = value
    },
    setExperienceYears(state, value) {
        state.item.experience_years = value
    },
    setExperience(state, value) {
        state.item.experience = value
    },
    setHighlight(state, value) {
        state.item.highlight = value
    },
    setDescription(state, value) {
        state.item.description = value
    },
    setAboutServices(state, value) {
        state.item.about_services = value
    },
    setQualification(state, value) {
        state.item.qualification = value
    }
};

export default {
    namespaced: true,
    state: initialState,
    getters,
    actions,
    mutations
}
