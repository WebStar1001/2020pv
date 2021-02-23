import Vue from 'vue'
import VueRouter from 'vue-router'

import HomeIndex from '../components/public/home/Index.vue'
import DashboardIndex from '../components/admin/dashboard/Index.vue'
import ThankyouIndex from '../components/admin/dashboard/Thankyou.vue'
import CustomerRegister from '../components/auth/CustomerRegister.vue'
import AdvisorRegister from '../components/auth/AdvisorRegister.vue'
import Login from '../components/auth/Login.vue'
import ResetPassword from '../components/auth/ResetPassword.vue'
import SetPassword from '../components/auth/SetPassword.vue'
import AdminChangePassword from '../components/admin/users/ChangePassword.vue'
import AdminFreeMinutes from '../components/admin/users/FreeMinutes.vue'
import UsersIndex from '../components/admin/users/Index.vue'
import UsersCreate from '../components/admin/users/Create.vue'
import UsersShow from '../components/admin/users/Show.vue'
import UsersEdit from '../components/admin/users/Edit.vue'
import ChatShow from '../components/admin/chat/Show.vue'
import PaymentsIndex from '../components/admin/payments/Index.vue'
import ReadingHistoryIndex from '../components/admin/reading-history/Index.vue'
import SettingsIndex from '../components/admin/settings/Index.vue'
import SettingsGeneral from '../components/admin/settings/General.vue'
import SettingsSecurity from '../components/admin/settings/Security.vue'
import SettingsNotifications from '../components/admin/settings/Notifications.vue'
import SettingsPayment from '../components/admin/settings/Payment.vue'
import SettingsWithdrawals from '../components/admin/settings/Withdrawals.vue'
import PendingPaymentEmails from '../components/admin/pending_payment_emails'
import ChatHistoryIndex from '../components/admin/chat-history'
import MyClients from '../components/admin/my-clients'
import MyPsychics from '../components/admin/my-psychics'
import CategoryIndex from '../components/public/category'
import AdvisorsIndex from '../components/public/advisors'
import ProfileIndex from '../components/public/profile'
import PayoutsIndex from '../components/admin/payouts'
import DisputesIndex from '../components/admin/disputes'
import DisputesShow from '../components/admin/disputes/Show.vue'
import StepRegisterOne from '../components/auth/step-register/StepOne.vue'
import StepRegisterTwo from '../components/auth/step-register/StepTwo.vue'
import StepRegisterThree from '../components/auth/step-register/StepThree.vue'
import FaqPage from '../components/public/technicalPages/FaqPage.vue';
import PrivacyPolicyPage from '../components/public/technicalPages/PrivacyPolicyPage.vue';
import TermsAndConditionsPage from '../components/public/technicalPages/TermsAndConditionsPage.vue';
import AboutUsPage from '../components/public/technicalPages/AboutUsPage.vue';
import TransactionsIndex from '../components/admin/transactions'
import ReviewsEdit from '../components/admin/users/reviews/edit'
import ReportsIndex from '../components/admin/reports'
import ChatSessionsIndex from '../components/admin/chat-sessions'
import NewsletterIndex from '../components/admin/newsletter'
import SpecialOfferIndex from '../components/public/special-offer'
import LandingIndex from '../components/public/landing'
import DiscountsIndex from '../components/admin/discounts'
import DiscountsCreate from '../components/admin/discounts/Create'
import DiscountsEdit from '../components/admin/discounts/Edit'
import GlobalSettingsIndex from '../components/admin/global-settings'
import MessagesNewSubject from '../components/admin/messages/NewSubject'
import MessagesIndex from '../components/admin/messages'
import MessagesShow from '../components/admin/messages/Show'
import EmailSubjectsIndex from '../components/admin/email-subjects'

Vue.use(VueRouter);

const routes = [
    {path: '/', component: HomeIndex, name: 'home.home'},
    {path: '/category/:slug', component: CategoryIndex, name: 'public.category.index'},
    {path: '/advisors', component: AdvisorsIndex, name: 'public.advisors.index'},

    {path: '/register', component: CustomerRegister, name: 'auth.customer_register'},
    {path: '/psychic-register', component: AdvisorRegister, name: 'auth.advisor_register'},
    {path: '/login', component: Login, name: 'auth.login'},
    {path: '/reset-password', component: ResetPassword, name: 'auth.reset_password'},
    {path: '/set-password/:token', component: SetPassword, name: 'auth.set_password'},

    {path: '/step-register/:slug', component: StepRegisterOne, name: 'step_register.one'},
    {path: '/step-register/:slug/payment', component: StepRegisterTwo, name: 'step_register.two'},
    {path: '/step-register/:slug/chat/:amount/:transactionID', component: StepRegisterThree, name: 'step_register.three'},

    {path: '/faq', component: FaqPage, name: 'public.faq'},
    {path: '/about-us', component: AboutUsPage, name: 'public.about_us'},
    {path: '/privacy-policy', component: PrivacyPolicyPage, name: 'public.privacy_policy'},
    {path: '/terms-and-conditions', component: TermsAndConditionsPage, name: 'public.terms_and_conditions'},

    {path: '/specialoffer', component: SpecialOfferIndex, name: 'special_offer.index'},
    {path: '/landingpage', component: LandingIndex, name: 'home.landing'},

    {path: '/admin/dashboard', component: DashboardIndex, name: 'admin.dashboard.index'},

    {path: '/customer/thank-you/:amount/:transactionID', component: ThankyouIndex, name: 'customer.thank-you.index'},

    {path: '/admin/reading-history', component: ReadingHistoryIndex, name: 'admin.reading_history.index'},

    {path: '/admin/users', component: UsersIndex, name: 'admin.users.index'},
    {path: '/admin/users/create', component: UsersCreate, name: 'admin.users.create'},
    {path: '/admin/users/:id', component: UsersShow, name: 'admin.users.show'},
    {path: '/admin/users/:id/edit', component: UsersEdit, name: 'admin.users.edit'},
    {path: '/admin/users/:id/change-password', component: AdminChangePassword, name: 'admin.users.change_password'},
    {path: '/admin/users/:id/free-minutes', component: AdminFreeMinutes, name: 'admin.users.free_minutes'},
    {path: '/admin/users/:id/chat-history', component: ChatHistoryIndex, name: 'admin.chat_history.index'},
    {path: '/admin/reviews/:id/edit', component: ReviewsEdit, name: 'admin.reviews.edit'},

    {
        path: '/admin/pending-payment-emails',
        component: PendingPaymentEmails,
        name: 'admin.pending_payment_emails.index'
    },

    {path: '/admin/chat/:chatSessionId', component: ChatShow, name: 'chat.chat.show'},

    {path: '/admin/payments', component: PaymentsIndex, name: 'admin.payments.index'},

    {path: '/admin/payouts', component: PayoutsIndex, name: 'admin.payouts.index'},
    {path: '/admin/bank-transfer', component: PayoutsIndex, name: 'admin.bank_transfer.index'},

    {path: '/admin/disputes', component: DisputesIndex, name: 'admin.disputes.index'},
    {path: '/admin/disputes/:id', component: DisputesShow, name: 'admin.disputes.show'},

    {path: '/admin/reports', component: ReportsIndex, name: 'admin.reports.index'},

    {path: '/admin/my-clients', component: MyClients, name: 'admin.my_clients.index'},
    {path: '/admin/my-psychics', component: MyPsychics, name: 'admin.my_psychics.index'},

    {
        path: '/admin/settings',
        component: SettingsIndex,
        name: 'admin.settings.index',
        redirect: '/admin/settings/general',
        children: [
            {path: '/admin/settings/general', component: SettingsGeneral, name: 'admin.settings.general'},
            {path: '/admin/settings/security', component: SettingsSecurity, name: 'admin.settings.security'},
            {
                path: '/admin/settings/notifications',
                component: SettingsNotifications,
                name: 'admin.settings.notifications'
            },
            {path: '/admin/settings/payment', component: SettingsPayment, name: 'admin.settings.payment'},
            {path: '/admin/settings/withdrawals', component: SettingsWithdrawals, name: 'admin.settings.withdrawals'}
        ]
    },

    {path: '/admin/transactions', component: TransactionsIndex, name: 'admin.transactions.index'},

    {path: '/admin/chat-sessions', component: ChatSessionsIndex, name: 'admin.chat_sessions.index'},

    {path: '/admin/email-subjects', component: EmailSubjectsIndex, name: 'admin.email_subjects.index'},

    {path: '/admin/newsletter', component: NewsletterIndex, name: 'admin.newsletter.index'},

    {path: '/admin/discounts', component: DiscountsIndex, name: 'admin.discounts.index'},
    {path: '/admin/discounts/create', component: DiscountsCreate, name: 'admin.discounts.create'},
    {path: '/admin/discounts/:id/edit', component: DiscountsEdit, name: 'admin.discounts.edit'},

    {path: '/admin/global-settings', component: GlobalSettingsIndex, name: 'admin.global_settings.index'},

    {path: '/admin/messages/:slug/new-subject', component: MessagesNewSubject, name: 'admin.messages.new_subject'},

    {
        path: '/admin/messages', component: MessagesIndex, name: 'admin.messages.index', children: [
            {path: '/admin/messages/:subjectId', component: MessagesShow, name: 'admin.messages.show'},
        ]
    },

    // should be at the bottom to not overide other routes
    {path: '/:masterService/:slug', component: ProfileIndex, name: 'profile.profile.index'},
];

export default new VueRouter({
    mode: 'history',
    // base: '/admin',
    routes
})