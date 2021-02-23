<template>
    <section class="content-box">
        <div class="box-header">
            <h3 class="box-title">Disputes</h3>
        </div>

        <div>
            <div class="d-flex mb-3">
                <button type="button" class="btn btn-default" @click="fetchData">
                    <i class="fa fa-refresh mr-1" :class="{'fa-spin': loading}"></i> Refresh
                </button>
            </div>

            <div>
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
    </section>
</template>


<script>
    import { mapGetters, mapActions } from 'vuex'
    import DatatableStatus from '../../dtmodules/disputes/DatatableStatus'
    import DatatableUser from '../../dtmodules/disputes/DatatableUser'
    import DatatableActions from '../../dtmodules/disputes/DatatableActions'

    export default {
        data() {
            return {
                columns: [
                    { title: '#', field: 'id', sortable: true, colStyle: 'width: 50px;' },
                    { title: 'Dispute ID', field: 'dispute_id', sortable: true },
                    { title: 'User', tdComp: DatatableUser, sortable: false },
                    { title: 'Reason', field: 'reason', sortable: true },
                    { title: 'Status', tdComp: DatatableStatus, sortable: false },
                    { title: 'Amount', field: 'dispute_amount', sortable: true },
                    { title: 'Create Date', field: 'created_at', sortable: true },
                    { title: 'Update Date', field: 'updated_at', sortable: true },
                    { title: 'Actions', tdComp: DatatableActions, visible: true, thClass: 'text-right', tdClass: 'text-right', colStyle: 'width: 130px;' }
                ],
                query: { sort: 'id', order: 'desc', fromDate: '', toDate: '' },
                xprops: {
                    module: 'DisputesIndex',
                    route: 'dispute',
                    gate_name: 'dispute',
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
            ...mapGetters('DisputesIndex', ['data', 'total', 'loading', 'relationships']),
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
            ...mapActions('DisputesIndex', ['fetchData', 'setQuery', 'resetState']),
        }
    }
</script>


<style scoped>

</style>
