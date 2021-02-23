<template>
    <section class="content-box">
        <div class="">
            <div class="box-header">
                <h3 class="box-title">Chat Sessions</h3>
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
        </div>
    </section>
</template>


<script>
    import { mapGetters, mapActions } from 'vuex'
    import DatatableId from '../../dtmodules/chat-sessions/DatatableId'
    import DatatableCustomer from '../../dtmodules/chat-sessions/DatatableCustomer'
    import DatatableAdvisor from '../../dtmodules/chat-sessions/DatatableAdvisor'
    import DatatablePricePerMinute from '../../dtmodules/chat-sessions/DatatablePricePerMinute'
    import DatatablePaidChatDuration from '../../dtmodules/chat-sessions/DatatablePaidChatDuration'
    import DatatableFreeChatDuration from '../../dtmodules/chat-sessions/DatatableFreeChatDuration'
    import DatatableCustomerBalance from '../../dtmodules/chat-sessions/DatatableCustomerBalance'
    import DatatableAdvisorBalance from '../../dtmodules/chat-sessions/DatatableAdvisorBalance'
    import DatatableEndedAt from '../../dtmodules/chat-sessions/DatatableEndedAt'

    export default {
        data() {
            return {
                columns: [
                    { title: '#', field: 'id', tdComp: DatatableId, sortable: true, colStyle: 'width: 50px;' },
                    { title: 'Customer', tdComp: DatatableCustomer, sortable: false },
                    { title: 'Advisor', tdComp: DatatableAdvisor, sortable: false },
                    { title: 'Price, $', field: 'price_per_minute', tdComp: DatatablePricePerMinute, sortable: true },
                    { title: 'Discount, %', field: 'discount', sortable: true },
                    { title: 'Paid Chat Duration', field: 'duration', tdComp: DatatablePaidChatDuration, sortable: true },
                    { title: 'Free Chat Duration', field: 'free_chat_duration', tdComp: DatatableFreeChatDuration, sortable: true },
                    { title: 'Customer Balance', tdComp: DatatableCustomerBalance, sortable: false },
                    { title: 'Advisor Balance', tdComp: DatatableAdvisorBalance, sortable: false },
                    { title: 'Created At', field: 'created_at', sortable: true },
                    { title: 'Ended At', field: 'ended_at', tdComp: DatatableEndedAt, sortable: true },
                ],
                query: { sort: 'chat_sessions.id', order: 'desc', search: ''},
                xprops: {
                    module: 'ChatSessionsIndex',
                    route: 'chat_sessions',
                    gate_name: 'chat_sessions',
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
            ...mapGetters('ChatSessionsIndex', [
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
            ...mapActions('ChatSessionsIndex', [
                'fetchData',
                'setQuery',
                'resetState',
            ]),
            search(e) {
                this.query.offset = 0;
                this.query.search = e.target.value;
            }
        }
    }
</script>


<style scoped>

</style>
