<template>
    <section class="content-box">
        <div class="">
            <div class="box-header">
                <h3 class="box-title">Email Chats</h3>
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
    import DatatableId from '../../dtmodules/email-subjects/DatatableId'
    import DatatableCustomer from '../../dtmodules/email-subjects/DatatableCustomer'
    import DatatableAdvisor from '../../dtmodules/email-subjects/DatatableAdvisor'

    export default {
        data() {
            return {
                columns: [
                    { title: '#', field: 'id', tdComp: DatatableId, sortable: true, colStyle: 'width: 50px;' },
                    { title: 'Customer', tdComp: DatatableCustomer, sortable: false },
                    { title: 'Advisor', tdComp: DatatableAdvisor, sortable: false },
                    { title: 'Subject', field: 'subject', sortable: true },
                    { title: 'Created At', field: 'created_at', sortable: true },
                ],
                query: { sort: 'email_subjects.id', order: 'desc', search: ''},
                xprops: {
                    module: 'EmailSubjectsIndex',
                    route: 'email_subjects',
                    gate_name: 'email_subjects',
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
            ...mapGetters('EmailSubjectsIndex', [
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
            ...mapActions('EmailSubjectsIndex', [
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
