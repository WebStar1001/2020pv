require ('./bootstrap');

window.Vue = require('vue');
Vue.prototype.$eventHub = new Vue();

import router from './routes/index'
import store from './store'
import Datatable from 'vue2-datatable-component'
import VueAWN from 'vue-awesome-notifications'
import vSelect from 'vue-select'
import datePicker from 'vue-bootstrap-datetimepicker'
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css'
import VueSweetalert2 from 'vue-sweetalert2'
import PrettyCheckbox from 'pretty-checkbox-vue'
import VModal from 'vue-js-modal'
import Paginate from 'vuejs-paginate'
import VueMoment from 'vue-moment'
import moment from 'moment-timezone'
import ToggleButton from 'vue-js-toggle-button'
import { VueStars } from "vue-stars"
import { BTabs, BTab, BButton, BCollapse, VBToggle, BFormSelect, BAlert, BDropdown } from 'bootstrap-vue';
// import CircularCountDownTimer from "vue-circular-count-down-timer";


import * as VeeValidate from 'vee-validate';
import { ValidationProvider, ValidationObserver, setInteractionMode, extend, localize } from 'vee-validate';
import en from 'vee-validate/dist/locale/en.json';
import * as rules from 'vee-validate/dist/rules';
// install rules and localization
Object.keys(rules).forEach(rule => {
    extend(rule, rules[rule]);
});
localize('en', en);
setInteractionMode('eager');


import Gate from './gate/Gate';
if (window.user) {
    window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + window.user.api_token;
}
Vue.prototype.$gate = window.user ? new Gate(window.user) : null;
Vue.prototype.$roles = window.roles;
window.router = router;

Vue.use(Datatable);
Vue.use(VueAWN, {
    position: 'top-right'
});
Vue.use(datePicker);
Vue.use(VueSweetalert2);
Vue.use(PrettyCheckbox);
Vue.use(VModal);
Vue.use(VueMoment, {moment});
Vue.use(VeeValidate);
Vue.use(ToggleButton);
// Vue.use(CircularCountDownTimer);

Vue.component('homepage-banner', require('./components/public/modules/HomepageBanner.vue'));
Vue.component('flower-section', require('./components/public/modules/Flower.vue'));
Vue.component('how-it-works-section', require('./components/public/modules/HowItWorks.vue'));
Vue.component('categories-section', require('./components/public/modules/Categories.vue'));
Vue.component('why-section', require('./components/public/modules/Why.vue'));
Vue.component('testimonials-section', require('./components/public/modules/Testimonials.vue'));
Vue.component('profile-card', require('./components/common/ProfileCard.vue'));
Vue.component('featured-advisors', require('./components/public/modules/FeaturedAdvisors.vue'));
Vue.component('become-user-section', require('./components/public/modules/BecomeUser.vue'));
Vue.component('faq-section', require('./components/public/modules/Faq.vue'));
Vue.component('back-buttton', require('./components/BackButton.vue'));
Vue.component('bootstrap-alert', require('./components/Alert.vue'));
Vue.component('event-hub', require('./components/EventHub.vue'));
Vue.component('vue-button-spinner', require('./components/VueButtonSpinner.vue'));
Vue.component('stopwatch', require('./components/Stopwatch.vue'));
Vue.component('countdown', require('./components/Countdown.vue'));
Vue.component('circular-count-down-timer', require('./components/CircularCountdown.vue'));
Vue.component('v-select', vSelect);
Vue.component('paginate', Paginate);
Vue.component('shared-layout', require('./components/layouts/SharedLayout'));
Vue.component('app-layout', require('./components/layouts/AppLayout'));
Vue.component('auth-layout', require('./components/layouts/AuthLayout'));
Vue.component('home-layout', require('./components/layouts/HomeLayout'));
Vue.component('public-layout', require('./components/layouts/PublicLayout'));
Vue.component('profile-layout', require('./components/layouts/ProfileLayout'));
Vue.component('step-register-layout', require('./components/layouts/StepRegisterLayout'));
Vue.component('special-offer-layout', require('./components/layouts/SpecialOfferLayout'));
Vue.component('chat-layout', require('./components/layouts/ChatLayout'));
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);
Vue.component("vue-stars", VueStars);
Vue.component('superadmin-dashboard', require('./components/admin/dashboard/SuperadminDashboard'));
Vue.component('advisor-dashboard', require('./components/admin/dashboard/AdvisorDashboard'));
Vue.component('customer-dashboard', require('./components/admin/dashboard/CustomerDashboard'));
Vue.component('chat-details-modal', require('./components/admin/modules/ChatDetails.vue'));
Vue.component('sidebar', require('./components/admin/modules/Sidebar.vue'));
Vue.component('site-header', require('./components/common/Header'));
Vue.component('site-home-header', require('./components/common/HomeHeader'));
Vue.component('site-footer', require('./components/common/Footer'));

Vue.component('b-tabs', BTabs);
Vue.component('b-tab', BTab);
Vue.component('b-tab', BTab);
Vue.component('b-button', BButton);
Vue.component('b-collapse', BCollapse);
Vue.directive('b-toggle', VBToggle);
Vue.component('b-form-select', BFormSelect);
Vue.component('b-alert', BAlert);
Vue.component('b-dropdown', BDropdown);

axios.interceptors.response.use(response => {
    return response;
}, error => {
    if (error.response.status === 401 || error.response.status === 419) {
        window.location.href = '/login'
    }

    window.scrollTo(0,0);

    return Promise.reject(error);
});

router.beforeEach((to, from, next) => {
    // reset errors
    store.dispatch(
        'Alert/setAlert',
        { message: '', errors: '', color: 'danger' },
        { root: true });

    window.scrollTo(0,0);

    if (to.name) {
        if (store.getters.layout !== 'app-layout' && to.name.split('.')[0].includes('admin')) {
            store.commit('setLayout', 'app-layout');
        }

        if (store.getters.layout !== 'auth-layout' && to.name.split('.')[0].includes('auth')) {
            store.commit('setLayout', 'auth-layout');
        }

        if (store.getters.layout !== 'home-layout' && to.name.split('.')[0].includes('home')) {
            store.commit('setLayout', 'home-layout');
        }

        if (store.getters.layout !== 'public-layout' && to.name.split('.')[0].includes('public')) {
            store.commit('setLayout', 'public-layout');
        }

        if (store.getters.layout !== 'profile-layout' && to.name.split('.')[0].includes('profile')) {
            store.commit('setLayout', 'profile-layout');
        }

        if (store.getters.layout !== 'step-register-layout' && to.name.split('.')[0].includes('step_register')) {
            store.commit('setLayout', 'step-register-layout');
        }

        if (store.getters.layout !== 'special-offer-layout' && to.name.split('.')[0].includes('special_offer')) {
            store.commit('setLayout', 'special-offer-layout');
        }

        if (store.getters.layout !== 'chat-layout' && to.name.split('.')[0].includes('chat')) {
            store.commit('setLayout', 'chat-layout');
        }
    }

    if (document.getElementById("sidenav")) {
        document.getElementById("sidenav").style.width = "0";
        document.getElementById("main-container").style.left = "0";
        document.getElementById("sidebar-overflow").style.display = "none";
    }

    // if (to.name.split('.')[0].includes('admin')) {
    //     if (!router.app.$gate) {
    //         next({ name: 'public.home' })
    //     }
    // }

    next()
});

const app = new Vue({
    data: {
    },
    created() {
        if (window.user) {
            this.$store.commit('setUser', window.user);
        }

        if (window.activeChatSession) {
            this.$store.commit('setActiveChatSession', window.activeChatSession);
        }

        if (window.busyWithUserId) {
            this.$store.commit('setBusyWithUserId', window.busyWithUserId);
        }

        if (window.favoriteAdvisors) {
            this.$store.commit('setFavoriteAdvisors', window.favoriteAdvisors);
        }

        if (window.blockedAdvisors) {
            this.$store.commit('setBlockedAdvisors', window.blockedAdvisors);
        }

        if (window.activeCall) {
            this.$store.commit('setActiveCall', window.activeCall);
        }

        if (window.settings) {
            this.$store.commit('setGlobalSettings', window.settings);
        }

        if (window.discount) {
            this.$store.commit('setDiscount', window.discount);
        }

        if (window.unreadMessages) {
            this.$store.commit('setUnreadMessages', window.unreadMessages);
        }

        if (window.discountHistory) {
            this.$store.commit('setDiscountHistory', window.discountHistory);
        }

        if (window.discountActive) {
            this.$store.commit('setDiscountActive', window.discountActive);
        }
    },
    methods: {},
    router,
    store
}).$mount('#app');
