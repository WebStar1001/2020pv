import Vue from 'vue'
import Vuex from 'vuex'

import UsersIndex from './modules/Users'
import UsersSingle from './modules/Users/single'
import ChatSingle from './modules/Chat/single'
import PaymentsIndex from './modules/Payments'
import Alert from './modules/alert'
import CustomerRegister from './modules/Auth/customerRegister'
import AdvisorRegister from './modules/Auth/advisorRegister'
import StepRegister from './modules/Auth/stepRegister'
import Login from './modules/Auth/login'
import ResetPassword from './modules/Auth/resetPassword'
import SetPassword from './modules/Auth/setPassword'
import ChangePassword from './modules/Users/change_password'
import FreeMinutes from './modules/Users/free_minutes'
import ReadingHistoryIndex from './modules/ReadingHistory'
import SettingsGeneral from './modules/Settings/general'
import SettingsSecurity from './modules/Settings/security'
import SettingsNotifications from './modules/Settings/notifications'
import SettingsPayment from './modules/Settings/payment'
import SettingsWithdrawals from './modules/Settings/withdrawals'
import PendingPaymentEmails from './modules/Users/pending_payment_emails'
import FeaturedAdvisors from './modules/Users/featured-advisors'
import CategoryIndex from './modules/Category'
import ProfileIndex from './modules/Users/profile'
import PayoutsIndex from './modules/Payouts'
import DisputesIndex from './modules/Disputes'
import DisputesSingle from './modules/Disputes/single'
import DashboardIndex from './modules/Dashboard'
import MyClientsIndex from './modules/MyClients'
import MyPsychicsIndex from './modules/MyPsychics'
import ChatHistoryIndex from './modules/ChatHistory'
import TransactionsIndex from './modules/Transactions'
import ReviewsIndex from './modules/Reviews'
import ReviewsSingle from './modules/Reviews/single'
import ReportsIndex from './modules/Reports'
import ChatSessionsIndex from './modules/ChatSessions'
import AdvisorsIndex from './modules/Advisors'
import MessagesNewSubject from './modules/Messages/newSubject'
import MessagesIndex from './modules/Messages'
import MessagesSingle from './modules/Messages/single'
import EmailSubjectsIndex from './modules/EmailSubjects'
import DiscountsIndex from './modules/Discount'
import DiscountsSingle from './modules/Discount/single'

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

function initialState() {
    return {
        user: null,
        activeChatSession: null,
        layout: 'public-layout',
        busyWithUserId: null,
        favoriteAdvisors: [],
        blockedAdvisors: [],
        activeCall: null,
        globalSettings: null,
        discount: 0,
        unreadMessages: 0,
        discountHistory: [],
        discountActive: false
    }
}

const getters = {
    user: state => state.user,
    activeChatSession: state => state.activeChatSession,
    layout: state => state.layout,
    busyWithUserId: state => state.busyWithUserId,
    favoriteAdvisors: state => state.favoriteAdvisors,
    blockedAdvisors: state => state.blockedAdvisors,
    activeCall: state => state.activeCall,
    globalSettings: state => state.globalSettings,
    discount: state => state.discount,
    unreadMessages: state => state.unreadMessages,
    discountHistory: state => state.discountHistory,
    discountActive: state => state.discountActive
};

const actions = {
    setUser({ commit }, value) {
        commit('setUser', value);
    },
    setUserStatus({ commit }, value) {
        axios.post('/user/status', {
            status: value
        }).then(resp => {
            commit('setUserStatus', value);

            if (value !== 'busy') {
                commit('setActiveChatSession', null);
            }
        });
    },
    setLayout({ commit }, value) {
        commit('setLayout', value);
    },
    setActiveChatSession({ commit }, value) {
        commit('setActiveChatSession', value);
    },
    setBusyWithUserId({ commit }, value) {
        commit('setBusyWithUserId', value);
    },
    setFavoriteAdvisors({ commit }, value) {
        commit('setFavoriteAdvisors', value);
    },
    setBlockedAdvisors({ commit }, value) {
        commit('setBlockedAdvisors', value);
    },
    setActiveCall({ commit }, value) {
        commit('setActiveCall', value);
    },
    setUnreadMessages({ commit }, value) {
        commit('setUnreadMessages', value);
    },
    setDiscountHistory({ commit }, value) {
        commit('setDiscountHistory', value);
    },
    setDiscountActive({ commit }, value) {
        commit('setDiscountActive', value);
    },
    setDiscount({ commit }, value) {
        commit('setDiscount', value);
    },
};

const mutations = {
    setUser(state, value) {
        state.user = value;
    },
    setUserStatus(state, value) {
        state.user.status = value;

        if (value !== 'busy') {
            state.activeChatSession = null;
        }
    },
    setUserBalance(state, value) {
        state.user.balance = value
    },
    setLayout(state, value) {
        state.layout = value
    },
    setActiveChatSession(state, value) {
        state.activeChatSession = value
    },
    setBusyWithUserId(state, value) {
        state.busyWithUserId = value
    },
    setFavoriteAdvisors(state, value) {
        if (Array.isArray(value)) {
            // set array of favorite advisors
            state.favoriteAdvisors = value;
        } else {
            const index = state.favoriteAdvisors.indexOf(value);

            if (index === -1) {
                // add advisor
                state.favoriteAdvisors.push(value);
            } else {
                // remove advisor
                state.favoriteAdvisors.splice(index, value)
            }
        }
    },
    setBlockedAdvisors(state, value) {
        if (Array.isArray(value)) {
            state.blockedAdvisors = value;
        }
    },
    setActiveCall(state, value) {
        state.activeCall = value;
    },
    setGlobalSettings(state, value) {
        state.globalSettings = value;
    },
    setDiscount(state, value) {
        state.discount = value;
    },
    setUnreadMessages(state, value) {
        state.unreadMessages = value;
    },
    setDiscountHistory(state, value) {
        if (Array.isArray(value)) {
            state.discountHistory = value;
        } else {
            state.discountHistory.push(value);
        }
    },
    setDiscountActive(state, value) {
        state.discountActive = value;
    },
};

export default new Vuex.Store({
    modules: {
        Alert,
        CustomerRegister,
        AdvisorRegister,
        StepRegister,
        Login,
        ResetPassword,
        SetPassword,
        ChangePassword,
        UsersIndex,
        UsersSingle,
        ChatSingle,
        PaymentsIndex,
        FreeMinutes,
        ReadingHistoryIndex,
        SettingsGeneral,
        SettingsSecurity,
        SettingsNotifications,
        SettingsPayment,
        SettingsWithdrawals,
        PendingPaymentEmails,
        CategoryIndex,
        ProfileIndex,
        PayoutsIndex,
        DisputesIndex,
        DisputesSingle,
        DashboardIndex,
        MyClientsIndex,
        MyPsychicsIndex,
        ChatHistoryIndex,
        FeaturedAdvisors,
        TransactionsIndex,
        ReviewsIndex,
        ReviewsSingle,
        ReportsIndex,
        ChatSessionsIndex,
        AdvisorsIndex,
        MessagesNewSubject,
        MessagesIndex,
        MessagesSingle,
        EmailSubjectsIndex,
        DiscountsIndex,
        DiscountsSingle
    },
    strict: debug,
    state: initialState,
    getters,
    actions,
    mutations
})
