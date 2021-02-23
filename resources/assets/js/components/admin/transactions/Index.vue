<template>
    <section class="content-box">
        <div class="box-header">
            <h3 class="box-title">Transactions</h3>
        </div>

        <div>
            <div class="d-flex justify-content-between mb-3">
                <div class="mr-3">
                    <button type="button" class="btn btn-default" @click="fetchData">
                        <i class="fa fa-refresh mr-1" :class="{'fa-spin': loading}"></i> Refresh
                    </button>
                </div>
                <div>
                    <input
                            type="search"
                            :value="query.search"
                            @input="search"
                            id="search"
                            class="form-control"
                            placeholder="Search..."
                    >
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

        </div>
    </section>
</template>


<script>
    import { mapGetters, mapActions } from 'vuex'
    import DatatableAmount from '../../dtmodules/transactions/DatatableAmount'
    import DatatableStatus from '../../dtmodules/transactions/DatatableStatus'
    import DatatableUser from '../../dtmodules/transactions/DatatableUser'

    export default {
        data() {
            return {
                columns: [
                    { title: '#', field: 'id', sortable: true, colStyle: 'width: 50px;' },
                    { title: 'Payment ID', field: 'payment_id', sortable: true },
                    { title: 'Transaction ID', field: 'transaction_id', sortable: true },
                    { title: 'User', field: 'user', tdComp: DatatableUser, sortable: true },
                    { title: 'Amount', field: 'amount', tdComp: DatatableAmount, sortable: true },
                    { title: 'Status', field: 'status', tdComp: DatatableStatus, sortable: true },
                    { title: 'Balance', field: 'balance_after', sortable: true },
                    { title: 'Created At', field: 'created_at', sortable: true },
                ],
                query: { sort: 'paypal_payments.id', order: 'desc', search: ''},
                xprops: {
                    module: 'TransactionsIndex',
                    route: 'transactions',
                    gate_name: 'transactions',
                },
            }
        },
        created() {
            this.$root.relationships = this.relationships;
        },
        destroyed() {
            this.resetState()
        },
        computed: {
            ...mapGetters('TransactionsIndex', [
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
            }
        },
        methods: {
            ...mapActions('TransactionsIndex', [
                'fetchData',
                'setQuery',
                'resetState',
            ]),
            search(e) {
                if (e.target.value.length > 2 || e.target.value.length === 0) {
                    this.query.offset = 0;
                    this.query.search = e.target.value;
                }
            }
        }
    }
</script>


<style scoped>

</style>
