<template>
    <section class="content-box">
        <div>
            <div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="box-header">
                            <h3 class="box-title">Payouts</h3>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2 d-flex justify-content-end align-items-center">
                            <span class="mr-2">Total Payout Amount:</span>
                            <h5 class="text-secondary">${{ toFixed(getPayoutAmount(), 2) }}</h5>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <div class="mr-3">
                        <button type="button" class="btn btn-default" @click="fetchData">
                            <i class="fa fa-refresh mr-1" :class="{'fa-spin': loading}"></i> Refresh
                        </button>
                    </div>
                    <div class="d-flex">
                        <div class="mr-3">
                            <input
                                    type="search"
                                    :value="query.search"
                                    @input="search"
                                    id="search"
                                    class="form-control"
                                    placeholder="Search..."
                            >
                        </div>

                        <div>
                            <button @click="massPayouts()"
                                    :disabled="!checkedAdvisors.length"
                                    type="button"
                                    class="btn btn-success"
                            >
                                Make Payouts for selected advisors <span>({{ checkedAdvisors.length }})</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div v-if="loading" class="loading-box">
                    <i class="fa fa-spin fa-refresh"></i> Loading
                </div>

                <datatable
                        v-if="!loading"
                        :columns="columns"
                        :data="data"
                        :total="total"
                        :query="query"
                        :xprops="xprops"
                        :HeaderSettings="false"
                />

                <modal name="payouts-modal" @before-open="beforeModalOpen" :scrollable="true" :adaptive="true" width="1000" height="auto">
                    <div class="modal-header">
                        <div class="title">
                            {{ modalRow.display_name }} - Payouts
                        </div>
                        <div>
                            <button @click="$modal.hide('payouts-modal')" class="btn btn-link btn-close">
                                <i class="icomoon icomoon-cross"></i>
                            </button>
                        </div>
                    </div>
                    <div v-if="modalRow" class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Payout ID</th>
                                <th>Transaction ID</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="payoutItem of modalRow.payout_items">
                                <td>
                                    {{ payoutItem.payout_item_id }}
                                </td>
                                <td>
                                    {{ payoutItem.transaction_id }}
                                </td>
                                <td>
                                    ${{ payoutItem.amount }}
                                </td>
                                <td :class="getClass(payoutItem.transaction_status)">
                                    {{ payoutItem.transaction_status }}

                                    <div v-if="payoutItem.id === modalRow.payout_items[modalRow.payout_items.length - 1].id">
                                        <div v-if="payoutItem.transaction_status === 'UNCLAIMED'" class="mt-2">
                                            <button
                                                    @click="cancelPayout(payoutItem.id)"
                                                    :disabled="loading"
                                                    type="button"
                                                    class="btn btn-link p-0"
                                            >
                                                Cancel
                                            </button>
                                        </div>

                                        <div v-if="payoutItem.transaction_status === 'FAILED' || payoutItem.transaction_status === 'BLOCKED'" class="mt-2">
                                            <button
                                                    @click="retryPayout(payoutItem.id)"
                                                    :disabled="loading"
                                                    type="button"
                                                    class="btn btn-link p-0"
                                            >
                                                Retry
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ payoutItem.updated_at }}
                                </td>
                                <th>
                                    <button
                                            @click="getDetails(payoutItem.id)"
                                            :disabled="loading"
                                            type="button"
                                            class="btn btn-default btn-sm"
                                    >
                                        Show Details
                                    </button>
                                </th>
                            </tr>
                            </tbody>
                        </table>

                        <pre class="mb-0"><textarea disabled class="form-control" rows="10">{{ payoutItemDetails }}</textarea></pre>
                    </div>
                </modal>

            </div>
        </div>
    </section>
</template>


<script>
    import { mapGetters, mapActions } from 'vuex'
    import MyMixins from '../../../mixins'
    import DatatableCheckbox from '../../dtmodules/payouts/DatatableCheckbox'
    import DatatableActions from '../../dtmodules/payouts/DatatableActions'
    import DatatableLastPayoutAmount from '../../dtmodules/payouts/DatatableLastPayoutAmount'
    import DatatableLastPayoutStatus from '../../dtmodules/payouts/DatatableLastPayoutStatus'
    import DatatableLastPayoutDate from '../../dtmodules/payouts/DatatableLastPayoutDate'
    import DatatablePaymentMethod from '../../dtmodules/payouts/DatatablePaymentMethod'

    export default {
        mixins: [MyMixins],
        data() {
            return {
                columns: [
                    { title: '', tdComp: DatatableCheckbox, visible: true, colStyle: 'width: 50px;' },
                    { title: '#', field: 'id', sortable: true, colStyle: 'width: 50px;' },
                    { title: 'Payment Method', tdComp: DatatablePaymentMethod, field: 'paypal_payment', sortable: true },
                    { title: 'Payment Email', field: 'payment_email', sortable: true },
                    { title: 'Display Name', field: 'display_name', sortable: true },
                    { title: 'Last Payout Amount', tdComp: DatatableLastPayoutAmount, sortable: false },
                    { title: 'Last Payout Status', tdComp: DatatableLastPayoutStatus, sortable: false },
                    { title: 'Last Payout Date', tdComp: DatatableLastPayoutDate, sortable: false },
                    { title: 'Current Balance', field: 'balance', sortable: true },
                    { title: 'Actions', tdComp: DatatableActions, visible: true, thClass: 'text-right', tdClass: 'text-right', colStyle: 'width: 130px;' }
                ],
                query: { sort: 'id', order: 'desc', fromDate: '', toDate: '', search: '', paypal_payment: 1 },
                xprops: {
                    module: 'PayoutsIndex',
                    route: 'payouts',
                    gate_name: 'payout',
                },
                modalRow: []
            }
        },
        created() {
            this.$root.relationships = this.relationships;

            this.query.paypal_payment = this.$route.query.paypal_payment === 0 ? 0 : 1;
        },
        destroyed() {
            this.resetState()
        },
        computed: {
            ...mapGetters('PayoutsIndex', [
                'data',
                'total',
                'loading',
                'relationships',
                'checkedAdvisors',
                'payoutItemDetails'
            ]),
        },
        watch: {
            "$route.query.paypal_payment": function() {
                this.query.paypal_payment = this.$route.query.paypal_payment === 0 ? 0 : 1;
            },
            query: {
                handler(query) {
                    this.setQuery(query)
                },
                deep: true
            }
        },
        methods: {
            ...mapActions('PayoutsIndex', [
                'fetchData',
                'setQuery',
                'resetState',
                'makePayouts',
                'getPayoutItemDetails',
                'resetPayoutItemDetails',
                'cancelUnclaimedPayout',
                'retryFailedPayout'
            ]),
            massPayouts() {
                this.$swal({
                    title: 'Are you sure you want to make payouts for selected advisors?',
                    text: '',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                    confirmButtonColor: '#dd4b39',
                    focusCancel: true,
                }).then(result => {
                    if (result.value) {
                        this.makePayouts(this.checkedAdvisors).then(result => {
                            this.$eventHub.$emit('payout-created')
                        })
                    }
                })
            },
            beforeModalOpen(event) {
                this.resetPayoutItemDetails();
                this.modalRow = event.params.row;
            },
            getClass(status) {
                switch (status) {
                    case 'SUCCESS':
                        return 'text-success';
                    case 'PENDING':
                        return 'text-warning';
                    default:
                        return 'text-danger'
                }
            },
            getDetails(id) {
                this.getPayoutItemDetails(id);
            },
            search(e) {
                if (e.target.value.length > 2 || e.target.value.length === 0) {
                    this.query.offset = 0;
                    this.query.search = e.target.value;
                }
            },
            getPayoutAmount() {
                let amount = 0;

                for (const user_id of this.checkedAdvisors) {
                    for (const user of this.data) {
                        if (user.id === user_id) {
                            amount += parseFloat(user.balance.replace('$', ''));
                        }
                    }
                }

                return amount;
            },
            cancelPayout(id) {
                this.cancelUnclaimedPayout(id).then(result => {
                    this.$eventHub.$emit('payout-cancelled');

                    this.$modal.hide('payouts-modal');
                })
            },
            retryPayout(id) {
                this.retryFailedPayout(id).then(result => {
                    this.$eventHub.$emit('payout-created');

                    this.$modal.hide('payouts-modal');
                })
            }
        }
    }
</script>


<style scoped>

</style>
