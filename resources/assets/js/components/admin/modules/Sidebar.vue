<template>
    <div class="sidebar" :class="$gate.user.role_id === $roles.SUPERADMIN || $gate.user.role_id === $roles.ADMIN ? 'compact-sidebar' : ''">
        <div class="status-control" :class="$store.getters.user.status">
            <v-select
                    :options="['online', 'offline']"
                    :value="$store.getters.user.status"
                    @input="changeUserStatus"
                    :disabled="$store.getters.user.status === 'busy'"
                    :clearable="false"
                    :searchable="false"
                    label="label"
                    style="width: 64px"
                    inputId="status"
            >
            </v-select>
        </div>

        <div class="text-center">
            <div class="sidebar-avatar" :style="'background-image: url(' + $gate.user.avatar + ')'">
            </div>

            <div class="display-name">
                {{ $gate.user.display_name }}
            </div>
        </div>

        <nav>
            <ul class="main-menu">
                <li v-if="$gate.allow('dashboard', 'menu')">
                    <router-link :to="{ name: 'admin.dashboard.index' }">
                        <i class="icomoon icomoon-dashboard"></i>
                        Dashboard
                    </router-link>
                </li>

                <li v-if="$gate.allow('reading_history', 'menu')">
                    <router-link :to="{ name: 'admin.reading_history.index' }">
                        <i class="icomoon icomoon-history"></i>
                        Reading History
                    </router-link>
                </li>

                <li v-if="$gate.allow('my_clients', 'menu')">
                    <router-link :to="{ name: 'admin.my_clients.index' }">
                        <i class="icomoon icomoon-clients"></i>
                        My Clients
                    </router-link>
                </li>

                <li v-if="$gate.allow('my_psychics', 'menu')">
                    <router-link :to="{ name: 'admin.my_psychics.index' }">
                        <i class="icomoon icomoon-clients"></i>
                        My Psychics
                    </router-link>
                </li>

                <li v-if="$gate.allow('messages', 'menu')">
                    <router-link :to="{ name: 'admin.messages.index' }">
                        <i class="icomoon icomoon-messages"></i>
                        Messages
                    </router-link>
                </li>

                <li v-if="$gate.allow('payments', 'menu')">
                    <router-link :to="{ name: 'admin.payments.index' }">
                        <i class="icomoon icomoon-payment"></i>
                        Payments
                    </router-link>
                </li>

                <li v-if="$gate.allow('users', 'menu')">
                    <router-link :to="{ name: 'admin.users.index' }">
                        <i class="fa fa-user"></i>
                        Users
                    </router-link>
                </li>

                <li v-if="$gate.allow('chat_sessions', 'menu')">
                    <router-link :to="{ name: 'admin.chat_sessions.index' }">
                        <i class="icomoon icomoon-history"></i>
                        Chat Sessions
                    </router-link>
                </li>

                <li v-if="$gate.allow('email_subjects', 'menu')">
                    <router-link :to="{ name: 'admin.email_subjects.index' }">
                        <i class="icomoon icomoon-messages"></i>
                        Email Chats
                    </router-link>
                </li>

                <li v-if="$gate.allow('payouts', 'menu')">
                    <router-link :to="{ name: 'admin.payouts.index' }">
                        <i class="fa fa-dollar"></i>
                        Payouts
                    </router-link>
                </li>

                <li v-if="$gate.allow('payouts', 'menu')">
                    <router-link :to="{ name: 'admin.bank_transfer.index', query: {paypal_payment: 0} }">
                        <i class="fa fa-dollar"></i>
                        Bank Transfer
                    </router-link>
                </li>

                <li v-if="$gate.allow('transactions', 'menu')">
                    <router-link :to="{ name: 'admin.transactions.index' }">
                        <i class="fa fa-dollar"></i>
                        Transactions
                    </router-link>
                </li>

                <li v-if="$gate.allow('pending_payment_emails', 'menu')">
                    <router-link :to="{ name: 'admin.pending_payment_emails.index' }">
                        <i class="fa fa-envelope"></i>
                        Pending Emails
                    </router-link>
                </li>

                <li v-if="$gate.allow('disputes', 'menu')">
                    <router-link :to="{ name: 'admin.disputes.index' }">
                        <i class="fa fa-comments"></i>
                        Disputes
                    </router-link>
                </li>

                <li v-if="$gate.allow('reports', 'menu')">
                    <router-link :to="{ name: 'admin.reports.index' }">
                        <i class="fa fa-flag"></i>
                        Reports
                    </router-link>
                </li>

                <li v-if="$gate.allow('newsletter', 'menu')">
                    <router-link :to="{ name: 'admin.newsletter.index' }">
                        <i class="fa fa-envelope"></i>
                        Newsletter
                    </router-link>
                </li>

                <li v-if="$gate.allow('discounts', 'menu')">
                    <router-link :to="{ name: 'admin.discounts.index' }">
                        <i class="fa fa-percent"></i>
                        Discounts
                    </router-link>
                </li>

                <!--<li v-if="$gate.allow('global_settings', 'menu')">-->
                <!--<router-link :to="{ name: 'admin.global_settings.index' }">-->
                <!--<i class="icomoon icomoon-setting"></i>-->
                <!--Global Settings-->
                <!--</router-link>-->
                <!--</li>-->

                <li v-if="$gate.allow('settings', 'menu')">
                    <router-link :to="{ name: 'admin.settings.index' }">
                        <i class="icomoon icomoon-settings"></i>
                        Settings
                    </router-link>
                </li>

                <li v-if="loggedAsUser">
                    <a @click.prevent="backToSuperadmin()" href="#">
                        <i class="fa fa-arrow-left"></i>
                        <span class="text-danger">Back to Super Admin</span>
                    </a>
                </li>
            </ul>

        </nav>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'

    export default {
        data() {
            return {
                loggedAsUser: false
            }
        },
        created() {
            this.loggedAsUser = this.$gate.user.role_id === this.$roles.SUPERADMIN ? false : sessionStorage.getItem('loggedAsUser')
        },
        methods: {
            ...mapActions('UsersSingle', [
                'loginToSuperadmin'
            ]),
            changeUserStatus(e) {
                if (e !== 'busy') {
                    this.$store.dispatch('setUserStatus', e);
                }

                // this.$store.dispatch('setUserStatus', e.target.value);
            },
            logout() {
                axios.post('/logout').then(resp => {
                    // Vue.prototype.$gate = null;
                    // this.$router.push({ name: 'public.home' });

                    window.location.href = '/';
                });
            },
            backToSuperadmin() {
                this.loginToSuperadmin(this.$gate.user.id).then(resp => {
                    sessionStorage.removeItem('loggedAsUser');
                    window.location.href = '/admin/dashboard';
                })
            },
            openNav() {
                document.getElementById("sidenav").style.width = "240px";
                document.getElementById("main-container").style.left = "240px";
                document.getElementById("sidebar-overflow").style.display = "block";
                document.getElementById('layout-container').classList.add('no-overflow-scroll');
            },
            closeNav() {
                document.getElementById("sidenav").style.width = "0";
                document.getElementById("main-container").style.left = "0";
                document.getElementById("sidebar-overflow").style.display = "none";
                document.getElementById('layout-container').classList.remove('no-overflow-scroll');

            }
        }
    }
</script>

<style lang="scss" scoped>
    .sidebar {
        position: relative;
        width: 240px;
        min-width: 240px;
        max-width: 240px;
        padding-top: 40px;
        color: #ffffff;
        height: 100%;
        overflow-y: auto;
        &.compact-sidebar {
            .sidebar-avatar {
                width: 50px;
                height: 50px;
            }
            .display-name {
                margin-bottom: 15px;
            }
            .main-menu {
                li {
                    a {
                        padding: 8px 20px 8px 30px;
                    }
                }
            }
        }
        .status-control {
            position: absolute;
            padding-left: 12px;
            height: 27px;
            right: 10px;
            top: 10px;
            font-size: 10px;
            border: 1px solid gray;
            border-radius: .25rem;
        }
        .display-name {
            margin-bottom: 3rem;
            @media (max-width: 767.98px) {
                margin-bottom: 15px;
            }
        }
        @media (max-width: 991.98px) {
            width: 100%;
            max-width: 100%;
            min-height: 100%;
        }
    }

    .sidebar-avatar {
        display: table;
        margin: 0 auto 20px auto;
        width: 100px;
        height: 100px;
        background-size: cover;
        border-radius: 50%;
        @media (max-width: 991.98px) {
            width: 50px;
            height: 50px;
        }
    }

    .display-name {
        font-weight: 500;
        font-size: 1rem;
    }

    .main-menu {
        margin: 0;
        padding: 0;
        list-style: none;
        li {
            border-top: 1px solid #3e3d3d;
            width: 100%;
            display: block;
            color: #9aa2a9;
            transition: 1s;
            &:hover {
                color: #00bff0;
                background: #1f222f;
            }
            a {
                display: block;
                padding: 16px 20px 16px 30px;
                color: inherit;
                &.router-link-active {
                    border-right: 4px solid #00bff0;
                    color: #00bff0;
                    background: #1f222f;
                    .fa, .icomoon {
                        color: #00bff0;
                    }
                }
            }
            .fa, .icomoon {
                color: #b3bdbc;
                width: 18px;
                font-size: 18px;
                margin-right: 13px;
                text-align: center;
                vertical-align: middle;
            }
        }
    }
</style>
