<template>
    <div class="">
        <div class="setting-title">
            Withdrawals
        </div>
        <div class="row">
            <div class="col-md-12">
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
        </div>
    </div>
</template>


<script>
    import { mapGetters, mapActions } from 'vuex'
    import DatatableStatus from '../../dtmodules/withdrawal-settings/DatatableStatus'
    import DatatableAmount from '../../dtmodules/withdrawal-settings/DatatableAmount'

    export default {
        data() {
            return {
                columns: [
                    { title: '#', field: 'id', sortable: true, colStyle: 'width: 50px;' },
                    { title: 'Amount', tdComp: DatatableAmount, sortable: false },
                    { title: 'Status', tdComp: DatatableStatus, sortable: false },
                    { title: 'Created Date', field: 'created_at', sortable: true },
                    { title: 'Processed Date', field: 'updated_at', sortable: true },
                ],
                query: { sort: 'id', order: 'desc', fromDate: '', toDate: '' },
                xprops: {
                    module: 'SettingsWithdrawals',
                    route: 'withdrawals',
                    gate_name: 'withdrawal',
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
            ...mapGetters('SettingsWithdrawals', ['data', 'total', 'loading', 'relationships']),
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
            ...mapActions('SettingsWithdrawals', ['fetchData', 'setQuery', 'resetState']),
        }
    }
</script>


<style scoped>

</style>
