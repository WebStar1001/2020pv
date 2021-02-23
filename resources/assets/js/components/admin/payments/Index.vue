<template>
    <section class="content-box">
        <div class="">
            <div class="box-header">
                <h3 class="box-title">Payments</h3>
            </div>
            <div>
                <div class="d-flex mb-3">
                    <div class="mr-3">
                        <button type="button" class="btn btn-default" @click="fetchData">
                            <i class="fa fa-refresh mr-1" :class="{'fa-spin': loading}"></i> Refresh
                        </button>
                    </div>
                    <div class="mr-3">
                        <div class="date-control">
                            <date-picker
                                    v-model="fromDate"
                                    :config="dateOptions"
                                    placeholder="From Date"
                            >
                            </date-picker>
                        </div>
                    </div>
                    <div class="mr-3">
                        <div class="date-control">
                            <date-picker
                                    v-model="toDate"
                                    :config="dateOptions"
                                    placeholder="To Date"
                            >
                            </date-picker>
                        </div>
                    </div>
                    <div>
                        <button @click="applyDateFilter()" type="button" class="btn btn-primary">Apply</button>
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

                <modal name="invoice-modal" @before-open="beforeModalOpen" :scrollable="true" :adaptive="true" width="500" height="auto">
                    <div class="modal-header">
                        <div class="title">
                            Invoice: #{{ invoice.id }}
                        </div>
                        <div>
                            <button @click="$modal.hide('invoice-modal')" class="btn btn-link btn-close">
                                <i class="icomoon icomoon-cross"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div v-if="invoice.session_with" class="mb-3">
                            <strong>Session with:</strong> {{ invoice.session_with }}
                        </div>

                        <div class="mb-3">
                            <strong>Type:</strong> {{ invoice.type }}
                        </div>

                        <div class="mb-3">
                            <strong>Amount Invoiced:</strong> ${{ invoice.amount }}
                        </div>

                        <div>
                            <strong>Date:</strong> {{ invoice.created_at }}
                        </div>
                    </div>
                </modal>
            </div>
        </div>
    </section>
</template>


<script>
    import { mapGetters, mapActions } from 'vuex'
    import DatatableActions from '../../dtmodules/payment/DatatableActions'
    import DatatableBalance from '../../dtmodules/payment/DatatableBalance'
    import DatatableAmount from '../../dtmodules/DatatableAmount'

    export default {
        data() {
            return {
                columns: [
                    { title: '#', field: 'id', sortable: true, colStyle: 'width: 50px;' },
                    { title: 'Date & Time', field: 'created_at', sortable: true },
                    { title: 'Type', field: 'type', sortable: true },
                    { title: 'Amount', field: 'amount', tdComp: DatatableAmount, sortable: true },
                    { title: 'Balance', field: 'balance', tdComp: DatatableBalance, sortable: true },
                    { title: 'Actions', tdComp: DatatableActions, visible: true, thClass: 'text-right', tdClass: 'text-right', colStyle: 'width: 130px;' }
                ],
                query: { sort: 'id', order: 'desc', fromDate: '', toDate: '' },
                xprops: {
                    module: 'PaymentsIndex',
                    route: 'payments',
                    gate_name: 'payment',
                },
                fromDate: '',
                toDate: '',
                dateOptions: {
                    format: 'MM/DD/YYYY',
                },
                invoice: {}
            }
        },
        created() {
            this.$root.relationships = this.relationships;
        },
        destroyed() {
            this.resetState()
        },
        computed: {
            ...mapGetters('PaymentsIndex', ['data', 'total', 'loading', 'relationships']),
        },
        watch: {
            query: {
                handler(query) {
                    this.setQuery(query)
                },
                deep: true
            }
        },
        methods: {
            ...mapActions('PaymentsIndex', ['fetchData', 'setQuery', 'resetState']),
            applyDateFilter() {
                this.query.fromDate = this.fromDate;
                this.query.toDate = this.toDate;
            },
            beforeModalOpen(event) {
                this.invoice = event.params.invoice;
            },
        }
    }
</script>


<style scoped>

</style>
