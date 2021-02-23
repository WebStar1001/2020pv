<template>
    <section>
        <div class="">
            <div class="box-header">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="box-title">Dashboard</h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-20">
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
                <div class="col-md-4 mb-20">
                    <div class="dash-br bg-white p-4 d-flex">
                        <div class="dash-icon-2">
                            <img src="/images/admin/dashboard-psychics.png">
                        </div>
                        <div class="dash-text-1">
                            <h3 class="mb-0">{{ data.favorite_advisors }}</h3>
                            <span class="small">My psychics</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-20">
                    <div class="dash-br bg-white p-4 d-flex">
                        <div class="dash-icon-4">
                            <img src="/images/admin/dashboard-funds.png">
                        </div>
                        <div class="dash-text-1">
                            <h3 class="mb-0 small">${{ toFixed($store.getters.user.balance, 2) }}</h3> <span class="add-dash"><a @click.prevent="$modal.show('pay-modal')" href="#">+ Add</a></span>
                            <span class="small">Available funds</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <b-tabs>
            <b-tab title="My last readings" active>
                <div v-if="loading" class="loading-box">
                    <i class="fa fa-spin fa-refresh"></i> Loading
                </div>

                <datatable
                        v-if="!loading"
                        :columns="lastChatsColumns"
                        :data="data.last_chat_sessions"
                        :total="10"
                        :query="query"
                        :xprops="xprops"
                        :HeaderSettings="false"
                        :Pagination="false"
                />
            </b-tab>
            <b-tab title="My Psychics">
                <div v-if="loading" class="loading-box">
                    <i class="fa fa-spin fa-refresh"></i> Loading
                </div>

                <datatable
                        v-if="!loading"
                        :columns="favoriteChatsColumns"
                        :data="data.favorites_chat_sessions"
                        :total="10"
                        :query="query"
                        :xprops="xprops"
                        :HeaderSettings="false"
                        :Pagination="false"
                />
            </b-tab>
        </b-tabs>

        <modal name="pay-modal" @before-open="payModalOpen" :scrollable="true" :adaptive="true" width="500" height="auto">
            <div class="modal-header">
                <div class="title">
                    Add Funds
                </div>
                <div>
                    <button @click="$modal.hide('pay-modal')" class="btn btn-link btn-close">
                        <i class="icomoon icomoon-cross"></i>
                    </button>
                </div>
            </div>
            <ValidationObserver v-slot="{ handleSubmit }">
                <form @submit.prevent="handleSubmit(pay)">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="amount">Funds</label>
                            <select @change="updatePayAmount" :disabled="loading" id="amount" class="form-control">
                                <option value="10">$10</option>
                                <option value="25">$25</option>
                                <option value="50">$50</option>
                                <option value="100">$100</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>

                        <div v-if="showCustomField">
                            <ValidationProvider name="Amount" rules="required|min_value:10|integer" v-slot="{ errors }">
                                <div class="form-group">
                                    <label for="custom-amount">Amount</label>
                                    <input
                                            :value="payAmount"
                                            @input="updateCustomPayAmount"
                                            type="number"
                                            :disabled="loading"
                                            id="custom-amount"
                                            class="form-control"
                                            :class="{'is-invalid': errors[0] }"
                                    >
                                    <div class="invalid-feedback">{{ errors[0] }}</div>
                                </div>
                            </ValidationProvider>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <vue-button-spinner
                                class="btn btn-success"
                                :isLoading="loading"
                                :disabled="loading"
                        >
                            Add Funds
                        </vue-button-spinner>

                        <button @click="$modal.hide('pay-modal')" type="button" class="btn btn-secondary">
                            Cancel
                        </button>
                    </div>
                </form>
            </ValidationObserver>
        </modal>

        <chat-details-modal></chat-details-modal>

    </section>
</template>


<script>
    import { mapGetters, mapActions } from 'vuex'
    import MyMixins from '../../../mixins'
    import DatatableSection from '../../dtmodules/reading-history/DatatableSection'
    import DatatableAvatar from '../../dtmodules/users/DatatableAvatar'
    import DatatableAmount from '../../dtmodules/DatatableAmount'
    import DatatableRating from '../../dtmodules/reading-history/DatatableRating'

    export default {
        mixins: [MyMixins],
        data() {
            return {
                lastChatsColumns: [
                    // { title: '#', field: 'id', sortable: true, colStyle: 'width: 50px;' },
                    { title: 'Name', field: 'display_name', tdComp: DatatableAvatar, sortable: true },
                    { title: 'Date & Time', field: 'ended_at', sortable: true },
                    { title: 'Section', tdComp: DatatableSection, sortable: false },
                    { title: 'Amount Spent', field: 'amount', tdComp: DatatableAmount, sortable: false },
                    { title: 'Rating', field: 'review', tdComp: DatatableRating, sortable: false },
                ],
                favoriteChatsColumns: [
                    // { title: '#', field: 'id', sortable: true, colStyle: 'width: 50px;' },
                    { title: 'Name', field: 'display_name', tdComp: DatatableAvatar, sortable: true },
                    { title: 'Date & Time', field: 'ended_at', sortable: true },
                    { title: 'Section', tdComp: DatatableSection, sortable: false },
                    { title: 'Amount Spent', field: 'amount', tdComp: DatatableAmount, sortable: false },
                    { title: 'Rating', field: 'review', tdComp: DatatableRating, sortable: false },
                ],
                query: { sort: 'id', order: 'desc', fromDate: '', toDate: '' },
                xprops: {
                    module: 'DashboardIndex',
                    route: 'dashboard',
                    gate_name: 'dashboard',
                },
                showCustomField: false
            }
        },
        created() {
            this.fetchData();

            if (this.$route.query.amount) {
                this.$eventHub.$emit('funds-added', this.$route.query.amount)
            }
        },
        destroyed() {
            this.resetState()
        },
        computed: {
            ...mapGetters('DashboardIndex', ['' +
            'data',
                'total',
                'loading',
                'payAmount'
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
                    this.setFilter(value)
                },
                deep: true
            }
        },
        methods: {
            ...mapActions('DashboardIndex', [
                'fetchData',
                'setQuery',
                'resetState',
                'setFilter',
                'setPayAmount',
                'pay'
            ]),
            updatePayAmount(e) {
                if (e.target.value === 'custom') {
                    this.showCustomField = true;
                    this.setPayAmount(null)
                } else {
                    this.showCustomField = false;
                    this.setPayAmount(e.target.value)
                }
            },
            updateCustomPayAmount(e) {
                this.setPayAmount(e.target.value);
            },
            payModalOpen() {
                this.setPayAmount(10);
                this.showCustomField = false;
            }
        }
    }
</script>


<style lang="scss" scoped>

</style>
