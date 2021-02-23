<template>
    <section>
        <div>
            <div class="box-header">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="box-title">Dashboard</h3>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group pull-right row">
                            <date-picker v-model="date" :config="options" @dp-change="dateFilter" use-utc="true" class="form-control col"></date-picker>
                            <select @change="updateFilter" class="form-control col ml-2">
                                <option value="">All</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                                <option value="today">Today</option>
                                <option value="yesterday">Yesterday</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 mb-20">
                    <div class="dashboard-box">
                        <div class="d-flex h-100 justify-content-between align-items-center">
                            <div>
                                <div class="title">
                                    Volume of transactions
                                </div>
                                <div class="value">
                                    ${{ data.volume_of_transactions }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-20">
                    <div class="dashboard-box">
                        <div class="d-flex h-100 justify-content-between align-items-center">
                            <div>
                                <div class="title">
                                    Commission earned
                                </div>
                                <div class="value">
                                    ${{ toFixed(data.commission_earned, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-20">
                    <div class="dashboard-box">
                        <div class="d-flex h-100 justify-content-between align-items-center">
                            <div>
                                <div class="title">
                                    Number of advisors
                                </div>
                                <div class="value">
                                    {{ data.number_of_advisors }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-20">
                    <div class="dashboard-box">
                        <div class="d-flex h-100 justify-content-between align-items-center">
                            <div>
                                <div class="title">
                                    Number of customers
                                </div>
                                <div class="value">
                                    {{ data.number_of_customers }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 mb-20">
                    <div class="dashboard-box">
                        <div class="d-flex h-100 justify-content-between align-items-center">
                            <div>
                                <div class="title">
                                    Customers e-wallet (total)
                                </div>
                                <div class="value">
                                    ${{ toFixed(data.customers_balance, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-20">
                    <div class="dashboard-box">
                        <div class="d-flex h-100 justify-content-between align-items-center">
                            <div>
                                <div class="title">
                                    Advisors e-wallet (total)
                                </div>
                                <div class="value">
                                    ${{ toFixed(data.advisors_balance, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-20">
                    <div class="dashboard-box">
                        <div class="d-flex h-100 justify-content-between align-items-center">
                            <div>
                                <div class="title">
                                    Self added funds
                                </div>
                                <div class="value">
                                    ${{ toFixed(data.self_added_funds, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 mb-20">
                    <div class="dashboard-box">
                        <div class="d-flex h-100 justify-content-between align-items-center">
                            <div>
                                <div class="title">
                                    Paypal "refunded" (Total)
                                </div>
                                <div class="value">
                                    ${{ toFixed(data.paypal_onhold, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-20">
                    <div class="dashboard-box">
                        <div class="d-flex h-100 justify-content-between align-items-center">
                            <div>
                                <div class="title">
                                    Paypal "on hold" (Total)
                                </div>
                                <div class="value">
                                    ${{ toFixed(data.paypal_onhold, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-20">
                    <div class="dashboard-box">
                        <div class="d-flex h-100 justify-content-between align-items-center">
                            <div>
                                <div class="title">
                                    Paypal Payouts Total
                                </div>
                                <div class="value">
                                    ${{ toFixed(data.paypal_payouts_total, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-20">
                    <div class="dashboard-box">
                        <div class="d-flex h-100 justify-content-between align-items-center">
                            <div>
                                <div class="title">
                                    Bank transfer (Total)
                                </div>
                                <div class="value">
                                    COMING SOON
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 mb-20">
                    <div class="dashboard-box">
                        <div class="d-flex h-100 justify-content-between align-items-center">
                            <div>
                                <div class="title">
                                    Currently active chat sessions
                                </div>
                                <div class="value">
                                    {{ data.active_chat_sessions }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-20">
                    <div class="dashboard-box">
                        <div class="d-flex h-100 justify-content-between align-items-center">
                            <div>
                                <div class="title">
                                    Total Chat Sessions
                                </div>
                                <div class="value">
                                    {{ data.total_chat_sessions }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-20">
                    <div class="dashboard-box">
                        <div class="d-flex h-100 justify-content-between align-items-center">
                            <div>
                                <div class="title">
                                    Messages exchanged
                                </div>
                                <div class="value">
                                    {{ data.messages }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-20">
                    <div class="dashboard-box">
                        <div class="d-flex h-100 justify-content-between align-items-center">
                            <div>
                                <div class="title">
                                    Missed chat requests
                                </div>
                                <div class="value">
                                    {{ data.missed_chat_requests }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-20">
                    <div class="dashboard-box">
                        <div class="d-flex h-100 justify-content-between align-items-center">
                            <div>
                                <div class="title">
                                    Newsletter subscriptions
                                </div>
                                <div class="value">
                                    {{ data.subscribers }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </section>
</template>


<script>
    import {mapGetters, mapActions} from 'vuex'
    import MyMixins from '../../../mixins'

    export default {
        mixins: [MyMixins],
        data() {
            return {
                date: new Date().toISOString(),
                options: {
                    format: 'YYYY-MM-DD',
                    useCurrent: false,
                }
            }
        },
        created() {
            this.fetchData();
        },
        destroyed() {
            this.resetState()
        },
        computed: {
            ...mapGetters('DashboardIndex', ['' +
            'data',
                'total',
                'loading'
            ]),
        },
        watch: {},
        methods: {
            ...mapActions('DashboardIndex', [
                'fetchData',
                'setQuery',
                'resetState',
                'setFilter'
            ]),
            updateFilter(e) {
                this.setFilter(e.target.value);
                this.fetchData();
            },
            dateFilter(e) {
                console.log(e.target.value);
                this.setFilter(e.target.value);
                this.fetchData();
            },
        }
    }
</script>


<style lang="scss" scoped>
    .dashboard-box {
        border-radius: 6px;
        background: #ffffff;
        padding: 1.5rem;

        .title {
            color: gray;
        }

        .value {
            font-size: 1.60rem;
            font-weight: 500;
        }
    }
</style>
