<template>
    <section class="content-box">
        <div class="box-header">
            <h3 class="box-title">Reports</h3>
        </div>

        <div>
            <div class="d-flex mb-3">
                <button type="button" class="btn btn-default" @click="fetchData">
                    <i class="fa fa-refresh mr-1" :class="{'fa-spin': loading}"></i> Refresh
                </button>
            </div>

            <div class="box-body">
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
    import DatatableChatSession from '../../dtmodules/reports/DatatableChatSession'
    import DatatableCustomer from '../../dtmodules/reports/DatatableCustomer'
    import DatatableAdvisor from '../../dtmodules/reports/DatatableAdvisor'

    export default {
        data() {
            return {
                columns: [
                    { title: '#', field: 'id', sortable: true, colStyle: 'width: 50px;' },
                    { title: 'Customer',  tdComp: DatatableCustomer, sortable: false },
                    { title: 'Advisor',  tdComp: DatatableAdvisor, sortable: false },
                    { title: 'Comment', field: 'comment', sortable: true },
                    { title: 'Chat Session', field: 'chat_session_id', tdComp: DatatableChatSession, sortable: false },
                ],
                query: { sort: 'id', order: 'desc', fromDate: '', toDate: '' },
                xprops: {
                    module: 'ReportsIndex',
                    route: 'report',
                    gate_name: 'report',
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
            ...mapGetters('ReportsIndex', ['data', 'total', 'loading', 'relationships']),
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
            ...mapActions('ReportsIndex', ['fetchData', 'setQuery', 'resetState']),
        }
    }
</script>


<style scoped>

</style>
