<template>
    <section class="content-box">
        <div class="box-header">
            <h3 class="box-title">Discounts</h3>
        </div>

        <div class="d-flex justify-content-between mb-3">
            <div class="d-flex">
                <div class="mr-3">
                    <button type="button" class="btn btn-default" @click="fetchData">
                        <i class="fa fa-refresh mr-1" :class="{'fa-spin': loading}"></i>Refresh
                    </button>
                </div>
            </div>

            <div class="d-flex">
                <div>
                    <router-link :to="{ name: 'admin.discounts.create' }" class="btn btn-success">
                        <i class="fa fa-plus mr-1"></i> Add new
                    </router-link>
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
    </section>
</template>


<script>
    import { mapGetters, mapActions } from 'vuex'
    import DatatableStatus from '../../dtmodules/discounts/DatatableStatus'
    import DatatableType from '../../dtmodules/discounts/DatatableType'
    import DatatableActions from '../../dtmodules/discounts/DatatableActions'

    export default {
        data() {
            return {
                columns: [
                    { title: '#', field: 'id', sortable: true, colStyle: 'width: 50px;' },
                    { title: 'Discount', field: 'discount', sortable: true },
                    { title: 'Type', field: 'for_new_users', sortable: true, tdComp: DatatableType },
                    { title: 'Status', field: 'active', sortable: true, tdComp: DatatableStatus },
                    { title: 'Created At', field: 'created_at', sortable: true },
                    { title: 'Actions', tdComp: DatatableActions, visible: true, thClass: 'text-right', tdClass: 'text-right', colStyle: 'width: 130px;' }
                ],
                query: { sort: 'id', order: 'desc', search: '', role_id: '' },
                xprops: {
                    module: 'DiscountsIndex',
                    route: 'discount',
                    gate_name: 'discount',
                }
            }
        },
        created() {
            this.$root.relationships = this.relationships
        },
        destroyed() {
            this.resetState()
        },
        computed: {
            ...mapGetters('DiscountsIndex', ['data', 'total', 'loading', 'relationships']),
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
            ...mapActions('DiscountsIndex', ['fetchData', 'setQuery', 'resetState']),
        }
    }
</script>


<style scoped>

</style>
