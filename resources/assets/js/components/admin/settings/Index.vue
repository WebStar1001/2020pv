<template>
    <section class="content-box">
        <div class="">
            <div class="box-header">
                <h3 class="box-title mb-3">Settings</h3>
            </div>

            <div class="d-flex settings-container">
                <div class="settings-menu">
                    <ul>
                        <li>
                            <router-link :to="{ name: 'admin.settings.general' }">
                                <div class="d-flex">
                                    <div class="icon-badge">
                                        <i class="icomoon icomoon-profile"></i>
                                    </div>
                                    <div>
                                        <div class="title">
                                            General Information
                                        </div>
                                        <div class="caption">
                                            Profile Photo, Name & Language
                                        </div>
                                    </div>
                                </div>
                            </router-link>
                        </li>
                        <li>
                            <router-link :to="{ name: 'admin.settings.security' }">
                                <div class="d-flex">
                                    <div class="icon-badge">
                                        <i class="icomoon icomoon-shield"></i>
                                    </div>
                                    <div>
                                        <div class="title">
                                            Security
                                        </div>
                                        <div class="caption">
                                            Password & Security Question
                                        </div>
                                    </div>
                                </div>
                            </router-link>
                        </li>
                        <li v-if="$gate.allow('settings_payment', 'menu')">
                            <router-link :to="{ name: 'admin.settings.payment' }">
                                <div class="d-flex">
                                    <div class="icon-badge">
                                        <i class="icomoon icomoon-bank"></i>
                                    </div>
                                    <div>
                                        <div class="title">
                                            Payment Settings
                                        </div>
                                        <div class="caption">
                                            Update Payment ID
                                        </div>
                                    </div>
                                </div>
                            </router-link>
                        </li>
                        <li v-if="$gate.allow('settings_withdrawals', 'menu')">
                            <router-link :to="{ name: 'admin.settings.withdrawals' }">
                                <div class="d-flex">
                                    <div class="icon-badge">
                                        <i class="icomoon icomoon-withdrawal"></i>
                                    </div>
                                    <div>
                                        <div class="title">
                                            Withdrawals
                                        </div>
                                        <div class="caption">
                                            Check Your Withdraws
                                        </div>
                                    </div>
                                </div>
                            </router-link>
                        </li>
                        <li>
                            <router-link :to="{ name: 'admin.settings.notifications' }">
                                <div class="d-flex">
                                    <div class="icon-badge">
                                        <i class="icomoon icomoon-bell"></i>
                                    </div>
                                    <div>
                                        <div class="title">
                                            Notifications
                                        </div>
                                        <div class="caption">
                                            Set Your Email Notification
                                        </div>
                                    </div>
                                </div>
                            </router-link>
                        </li>
                    </ul>
                </div>
                <div class="settings-content w-100">
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </section>
</template>


<script>
    export default {
        data() {
            return {
            }
        },
        created() {

        },
        destroyed() {
        },
        computed: {
        },
        methods: {
        }
    }
</script>


<style lang="scss" scoped>
    .settings-menu {
        width: 390px;
        min-width: 390px;
        border-right: 1px solid #dee2e6;
        ul {
            margin: 0;
            padding: 0;
            li {
                list-style: none;
                a {
                    display: block;
                    color: #212B36;;
                    border-bottom: 1px solid #e6e6e6;
                    padding: 13px 20px;
                    &:hover {
                        background: #f7f7f7;
                    }
                    &.router-link-active {
                        border-color: transparent;
                        background: #f7f7f7;
                        border-right: 2px solid #00bff0;
                        .title {
                            color: #00bff0;
                        }
                        &:hover {
                            color: #212B36;
                        }
                    }
                    .title {
                        font-weight: 600;
                    }
                    .caption {
                        font-family: 'Open Sans', sans-serif;
                        font-weight: normal;
                        font-size: 12px;
                        line-height: 16px;
                        color: gray;
                    }
                    .icon-badge {
                        margin-right: 15px;
                        font-size: 25px;
                        color: #404f54;
                    }
                }
            }
        }
    }
    .settings-container {
        padding: .5rem;
        background: #ffffff;
        @media (max-width: 1199.98px) {
            flex-direction: column;
            .settings-menu {
                width: 100%;
                margin-bottom: 20px;
            }
        }
    }
    .settings-content {
        padding: 0 30px;
        @media (max-width: 767.98px) {
            padding: 0 15px;
        }
    }
</style>
