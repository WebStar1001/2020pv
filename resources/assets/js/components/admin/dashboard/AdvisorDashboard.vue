<template>
    <section>
        <div class="">
            <div class="box-header">
                <div class="row">
                    <div class="col-md-4">
                        <div class="d-flex">
                            <h3 class="box-title mr-3">Dashboard</h3>
                            <div>
                                <img src="/images/admin/diamond.png" title="Rank" width="32" height="22">
                                {{ $gate.user.rank }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="d-flex pull-right mb-20">
                            <div class="mr-1">
                                <button @click="$modal.show('sound-test-modal')" class="btn btn-dash-gray">
                                    <img src="/images/admin/dashboard-sound.png">
                                    <span>Sound Test</span>
                                </button>
                            </div>
                            <div>
                                <router-link :to="{name: 'profile.profile.index', params: { masterService: $gate.user.master_service.slug, slug: $gate.user.slug }}" class="btn btn-dash-gray">
                                    <img src="/images/admin/dashboard-link.png">
                                    Profile URL
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6">
                    <div class="row">
                        <div class="col-lg-6 mb-20">
                            <div class="dash-br bg-white p-4 d-flex">
                                <div class="dash-icon-1">
                                    <img src="/images/admin/dashboard-readings.png">
                                </div>
                                <div class="dash-text-1">
                                    <h3 class="mb-0">{{ data.readings }}</h3>
                                    <span class="small">Readings</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-20">
                            <div class="dash-br bg-white p-4 d-flex">
                                <div class="dash-icon-2">
                                    <img src="/images/admin/dashboard-psychics.png">
                                </div>
                                <div class="dash-text-1">
                                    <h3 class="mb-0">{{ data.clients }}</h3>
                                    <span class="small">My clients</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 mb-20">
                            <div class="dash-br bg-white p-4 d-flex">
                                <div class="dash-icon-3">
                                    <img src="/images/admin/dashboard-funds-red.png">
                                </div>
                                <div class="dash-text-1">
                                    <h3 class="mb-0">${{ toFixed(data.lifetime_earnings, 2) }}</h3>
                                    <span class="small">Lifetime Earnings</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-20">
                            <div class="dash-br bg-white p-4 d-flex">
                                <div class="dash-icon-4">
                                    <img src="/images/admin/dashboard-funds.png">
                                </div>
                                <div class="dash-text-1">
                                    <h3 class="mb-0 small">${{ toFixed($store.getters.user.balance, 2) }}</h3>
                                    <span class="add-dash text-right" style="margin-top: -22px">
                                        <a @click.prevent="$modal.show('payouts-modal')" href="#">
                                             Withdraw
                                        </a><br>
                                        <span v-if="$gate.user.payouts_enabled">(On)</span>
                                        <span v-else>(Off)</span>
                                    </span>
                                    <span class="small">Available funds</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="earnings-overview">
                        <div class="row">
                            <div class="col-6">
                                <div class="title">
                                    Earnings overview
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group pull-right">
                                    <select @change="updateFilter" class="form-control">
                                        <option value="">All</option>
                                        <option value="week">This Week</option>
                                        <option value="month">This Month</option>
                                        <option value="today">Today</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-20 mb-md-0">
                                <div class="overview-side-tab px-2 py-1">
                                    <h3>${{ toFixed(data.sales, 2) }}</h3>
                                    <span class="small">Sales</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-20 mb-md-0">
                                <div class="overview-side-tab px-2 py-1">
                                    <h3>${{ toFixed(data.daily_sales, 2) }}</h3>
                                    <span class="small">Net Sales</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-20 mb-md-0">
                                <div class="overview-side-tab px-2 py-1">
                                    <h3>${{ toFixed(data.withdrawn, 2) }}</h3>
                                    <span class="small pl-0">Withdrawn</span>
                                </div>
                            </div>
                        </div>
                        <div id="chartdiv" style="height: 90px"></div>
                    </div>
                </div>
            </div>
        </div>

        <b-tabs>
            <b-tab title="Missed latest chat request" active>
                <div v-if="loading" class="loading-box">
                    <i class="fa fa-spin fa-refresh"></i> Loading
                </div>

                <datatable
                        v-if="!loading"
                        :columns="missedChatsColumns"
                        :data="data.missed_chats"
                        :total="10"
                        :query="query"
                        :xprops="xprops"
                        :HeaderSettings="false"
                        :Pagination="false"
                />
            </b-tab>
            <b-tab title="Latest Feebacks">
                <div v-if="loading" class="loading-box">
                    <i class="fa fa-spin fa-refresh"></i> Loading
                </div>

                <datatable
                        v-if="!loading"
                        :columns="reviewsColumns"
                        :data="data.reviews"
                        :total="10"
                        :query="query"
                        :xprops="xprops"
                        :HeaderSettings="false"
                        :Pagination="false"
                />
            </b-tab>
        </b-tabs>

        <modal name="sound-test-modal" :scrollable="true" :adaptive="true" height="auto">
            <div class="modal-header">
                <div class="title">
                    Sound Test
                </div>
                <div>
                    <button @click="$modal.hide('sound-test-modal')" class="btn btn-link btn-close">
                        <i class="icomoon icomoon-cross"></i>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="sound">Select</label>
                    <select v-model="soundRef" id="sound" class="form-control">
                        <option value="testSoundRinging">Incoming chat</option>
                        <option value="testSoundReject">Missed Chat</option>
                        <option value="testSoundPing">New Message</option>
                    </select>
                </div>

                <audio ref="testSoundRinging" preload="auto" style="display: none">
                    <source src="/sounds/ringing.ogg" type="audio/ogg">
                    <source src="/sounds/ringing.webm" type="audio/webm">
                    <source src="/sounds/ringing.mp3" type="audio/mp3">
                </audio>

                <audio ref="testSoundReject" preload="auto" style="display: none">
                    <source src="/sounds/reject.ogg" type="audio/ogg">
                    <source src="/sounds/reject.webm" type="audio/webm">
                    <source src="/sounds/reject.mp3" type="audio/mp3">
                </audio>

                <audio ref="testSoundPing" preload="auto" style="display: none">
                    <source src="/sounds/ping.ogg" type="audio/ogg">
                    <source src="/sounds/ping.webm" type="audio/webm">
                    <source src="/sounds/ping.mp3" type="audio/mp3">
                </audio>
            </div>
            <div class="modal-footer">
                <button @click="playSound()" class="btn btn-success">Play</button>
                <button @click="$modal.hide('sound-test-modal')" class="btn btn-secondary">Cancel</button>
            </div>
        </modal>

        <modal name="payouts-modal" :scrollable="true" :adaptive="true" width="500" height="auto">
            <div class="modal-header">
                <div class="title">
                    Withdrawal
                </div>
                <div>
                    <button @click="$modal.hide('payouts-modal')" class="btn btn-link btn-close">
                        <i class="icomoon icomoon-cross"></i>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="payouts_enabled">Select</label>
                    <v-select
                            :options="payoutsOptions"
                            v-model="payoutsEnabled"
                            :clearable="false"
                            :searchable="false"
                            label="label"
                            inputId="payouts_enabled"
                    >
                    </v-select>
                </div>
            </div>
            <div class="modal-footer">
                <button @click="updatePayoutsEnabled()" class="btn btn-success">Update</button>

                <button @click="$modal.hide('payouts-modal')" class="btn btn-secondary">Cancel</button>
            </div>
        </modal>

        <modal name="chat-details-modal" @before-open="beforeModalOpen" :scrollable="true" :adaptive="true" width="350" height="auto">
            <div class="modal-header">
                <div class="title">
                    Chat Details
                </div>
                <div>
                    <button @click="$modal.hide('chat-details-modal')" class="btn btn-link btn-close">
                        <i class="icomoon icomoon-cross"></i>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div class="mb-20">
                    <strong>Start time:</strong> {{ chatSession.created_at }}
                </div>

                <div class="mb-20">
                    <strong>End time:</strong> {{ chatSession.ended_at }}
                </div>

                <div class="mb-20">
                    <strong>Session length:</strong> {{ getChatDuration(chatSession.created_at, chatSession.ended_at)}}
                </div>

                <div class="mb-20">
                    <strong>Free time:</strong> {{ getDurationTime(chatSession.free_chat_duration) }}
                </div>

                <div class="mb-20">
                    <strong>Paid time:</strong> {{ getDurationTime(chatSession.duration) }}
                </div>

                <div v-if="$gate.user.role_id === $roles.ADVISOR">
                    <div class="mb-2">
                        <strong>Earnings:</strong>
                    </div>

                    <div class="row mb-2">
                        <div class="col-8">
                            Total revenue:
                        </div>
                        <div class="col-4 text-right">
                            ${{ toFixed(getTotalRevenue(), 2) }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-8">
                            Commission ({{ this.chatSession.commission_percentage }}%)
                        </div>
                        <div class="col-4 text-right">
                            -${{ toFixed(getCommission(), 2) }}
                        </div>
                    </div>
                    <div v-if="chatSession.moneyback_amount !== 0" class="row mb-2">
                        <div class="col-8">
                            Inactive chat:
                        </div>
                        <div class="col-4 text-right">
                            -${{ toFixed(Math.abs(chatSession.moneyback_amount), 2) }}
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row mb-2">
                        <div class="col-8">
                            <strong>Your Earnings:</strong>
                        </div>
                        <div class="col-4 text-right">
                            <strong>${{ toFixed(getAdvisorRevenueWithMoneyback(), 2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <router-link
                        :to="{ name: 'chat.chat.show', params: {chatSessionId: chatSession.id} }"
                        target="_blank"
                        class="btn btn-success"
                >
                    Chat History
                </router-link>

                <button @click="$modal.hide('chat-details-modal')" type="button" class="btn btn-secondary">
                    Cancel
                </button>
            </div>
        </modal>

    </section>
</template>


<script>
    import { mapGetters, mapActions } from 'vuex'
    import MyMixins from '../../../mixins'
    import DatatableDisplayName from '../../dtmodules/dashboard/DatatableDisplayName'
    import DatatableAmount from '../../dtmodules/dashboard/DatatableAmount'
    import DatatableRating from '../../dtmodules/dashboard/DatatableRating'
    import DatatableStatus from '../../dtmodules/dashboard/DatatableStatus'
    import DatatableActions from '../../dtmodules/dashboard/DatatableActions'
    import DatatableSection from '../../dtmodules/dashboard/DatatableSection'
    import * as am4core from "@amcharts/amcharts4/core";
    import * as am4charts from "@amcharts/amcharts4/charts";
    import am4themes_animated from "@amcharts/amcharts4/themes/animated";

    export default {
        mixins: [MyMixins],
        data() {
            return {
                soundRef: 'testSoundRinging',
                payoutsEnabled: null,
                payoutsOptions: [
                    {key: 0, label: 'Auto withdraw off'},
                    {key: 1, label: 'Auto withdraw monthly'}
                ],
                missedChatsColumns: [
                    { title: 'Name', tdComp: DatatableDisplayName, sortable: false },
                    { title: 'Date & Time', field: 'created_at', sortable: false },
                    { title: 'Status', tdComp: DatatableStatus, sortable: false },
                    { title: 'Actions', tdComp: DatatableActions, sortable: false, colStyle: 'width: 150px;'}
                ],
                reviewsColumns: [
                    { title: 'Name', tdComp: DatatableDisplayName, sortable: false },
                    { title: 'Date & Time', field: 'created_at', sortable: false },
                    { title: 'Section', tdComp: DatatableSection, sortable: false },
                    { title: 'Amount Earned', tdComp: DatatableAmount, sortable: false },
                    { title: 'Rating', tdComp: DatatableRating, sortable: false },
                ],
                query: { sort: 'id', order: 'desc', fromDate: '', toDate: '' },
                xprops: {
                    module: 'DashboardIndex',
                    route: 'dashboard',
                    gate_name: 'dashboard',
                },
                chatSession: {},
            }
        },
        created() {
            this.fetchData().then(() => {
                this.initChart();
            });

            this.payoutsEnabled = this.payoutsOptions[this.$gate.user.payouts_enabled];
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
        watch: {
            query: {
                handler(query) {
                    this.setQuery(query)
                },
                deep: true
            },
            filter: {
                handler(value) {
                    this.setFilter(value);
                },
                deep: true
            }
        },
        methods: {
            ...mapActions('DashboardIndex', [
                'fetchData',
                'setQuery',
                'resetState',
                'setFilter'
            ]),
            playSound() {
                this.$refs[this.soundRef].play();
            },
            updatePayoutsEnabled() {
                axios.post('/user/payouts-enabled', {
                    payouts_enabled: this.payoutsEnabled.key
                })
                    .then(response => {
                        // TODO: update $gate.user.payouts_enabled
                        this.$modal.hide('payouts-modal');
                        this.$eventHub.$emit('update-success')
                    });
            },
            updateFilter(e) {
                this.setFilter(e.target.value);

                this.fetchData().then(() => {
                    this.initChart();
                });
            },
            beforeModalOpen(event) {
                this.chatSession = event.params.chatSession.chat_session;
            },
            getChatDuration(startDate, endDate) {
                return this.getDurationTime( (moment(endDate)).diff(moment(startDate), 'seconds') );
            },
            getTotalRevenue() {
                const customerSpent = this.chatSession.customer_balance_before - this.chatSession.customer_balance_after;

                return this.chatSession.discount ? (customerSpent * 100) / (100 - this.chatSession.discount) : customerSpent;
            },
            getCommission() {
                return (this.getTotalRevenue() - this.getAdvisorRevenue());
            },
            getAdvisorRevenue() {
                return this.getTotalRevenue() * (100 - this.chatSession.commission_percentage) / 100;
            },
            getAdvisorRevenueWithMoneyback() {
                return this.getTotalRevenue() * (100 - this.chatSession.commission_percentage) / 100 + +this.chatSession.moneyback_amount;
            },
            getDurationTime(duration) {
                let sec_num = parseInt(duration, 10);
                let hours   = Math.floor(sec_num / 3600);
                let minutes = Math.floor((sec_num - (hours * 3600)) / 60);
                let seconds = sec_num - (hours * 3600) - (minutes * 60);

                if (hours   < 10) {hours   = "0" + hours;}
                if (minutes < 10) {minutes = "0" + minutes;}
                if (seconds < 10) {seconds = "0" + seconds;}

                return hours + ':' + minutes + ':' + seconds;
            },
            initChart() {
                am4core.options.commercialLicense = true;
                am4core.useTheme(am4themes_animated);

                const chart = am4core.create("chartdiv", am4charts.XYChart);

                chart.data = this.data.chart_data;
                chart.padding(0, 0, 15, 0);

                const dateAxis = new am4charts.DateAxis();
                const valueAxis = new am4charts.ValueAxis();

                setTimeout(function () {
                    dateAxis.renderer.labels.template.disabled = true;
                    dateAxis.renderer.grid.template.disabled = true;

                    valueAxis.renderer.labels.template.disabled = true;
                    valueAxis.renderer.grid.template.disabled = true;
                }, 100);

                chart.xAxes.push(dateAxis);
                chart.yAxes.push(valueAxis);

                const series = chart.series.push(new am4charts.LineSeries());
                series.dataFields.valueY = "amount";
                series.dataFields.dateX = "created_at";
                series.strokeWidth = 1;
                series.tooltipText = "{valueY.value}";
                series.fillOpacity = 0.1;
                series.stroke = am4core.color("#6dd230");
                series.tensionX = 0.5;
                series.tensionY = 0.5;
            }
        }
    }
</script>


<style lang="scss" scoped>
    .btn-dash-gray {
        padding: 4px 12px;
        color: gray;
        font-size: 12px;
        background: #f2f4f6;
    }
    
    .earnings-overview {
        background: #ffffff;
        padding: 15px;
        height: calc(100% - 20px);
        .form-control {
            padding-top: 0;
            padding-bottom: 0;
            height: auto;
            min-height: auto;
        }
        .overview-side-tab {
            border: 1px solid #ececec;
            border-radius: 6px;
            h3 {
                font-size: 1.60rem;
                font-weight: 400;
            }
        }
    }
</style>
