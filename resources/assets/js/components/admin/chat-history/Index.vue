<template>
    <section class="content-box">
        <div class="">
            <div class="box-header">
                <h3 class="box-title">Chat History</h3>
            </div>
            <div>
                <div class="d-flex mb-3">
                    <div class="mr-3">
                        <button type="button" class="btn btn-default" @click="fetchData($route.params.id)">
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

                <chat-details-modal></chat-details-modal>
            </div>
        </div>
    </section>
</template>


<script>
    import { mapGetters, mapActions } from 'vuex'
    import MyMixins from '../../../mixins'
    import DatatableAvatar from '../../dtmodules/users/DatatableAvatar'
    import DatatableAmount from '../../dtmodules/DatatableAmount'
    import DatatableSection from '../../dtmodules/reading-history/DatatableSection'
    import DatatableRating from '../../dtmodules/reading-history/DatatableRating'

    export default {
        mixins: [MyMixins],
        data() {
            return {
                columns: [
                    { title: '#', field: 'id', sortable: true, colStyle: 'width: 50px;' },
                    { title: this.getUserTitle(), field: 'display_name', tdComp: DatatableAvatar, sortable: true },
                    { title: 'Date & Time', field: 'ended_at', sortable: true },
                    { title: 'Section', tdComp: DatatableSection, sortable: false },
                    { title: this.getAmountTitle(), field: 'amount', tdComp: DatatableAmount, sortable: false },
                    { title: 'Rating', field: 'review', tdComp: DatatableRating, sortable: false },
                ],
                query: { sort: 'id', order: 'desc', fromDate: '', toDate: '' },
                xprops: {
                    module: 'ReadingHistoryIndex',
                    route: 'reading-history',
                    gate_name: 'reading-history',
                },
                fromDate: '',
                toDate: '',
                dateOptions: {
                    format: 'MM/DD/YYYY',
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
            ...mapGetters('ChatHistoryIndex', ['data', 'total', 'loading', 'relationships']),
        },
        watch: {
            query: {
                handler(query) {
                    this.setQuery({
                        'query': query,
                        'user_id': this.$route.params.id
                    })
                },
                deep: true
            },
        },
        methods: {
            ...mapActions('ChatHistoryIndex', ['fetchData', 'setQuery', 'resetState']),
            getUserTitle() {
                return this.$gate.user.role_id === this.$roles.CUSTOMER ? 'Psychic Name' : 'Client Name';
            },
            getAmountTitle() {
                return this.$gate.user.role_id === this.$roles.CUSTOMER ? 'Amount Spent' : 'Amount Earned';
            },
            applyDateFilter() {
                this.query.fromDate = this.fromDate;
                this.query.toDate = this.toDate;
            },
        }
    }
</script>


<style scoped>

</style>
