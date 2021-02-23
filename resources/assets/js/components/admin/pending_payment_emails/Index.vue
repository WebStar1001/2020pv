<template>
    <section class="content-box">
        <div class="box-header">
            <h3 class="box-title">Pending Payment Emails</h3>
        </div>

        <div>
            <div class="d-flex mb-3">
                <button type="button" class="btn btn-default" @click="fetchData">
                    <i class="fa fa-refresh mr-1" :class="{'fa-spin': loading}"></i> Refresh
                </button>
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
import DatatableActions from '../../dtmodules/pending-emails/DatatableActions'
import DatatableUser from '../../dtmodules/pending-emails/DatatableUser'
import DatatableEmail from '../../dtmodules/pending-emails/DatatableEmail'

export default {
    data() {
        return {
            columns: [
                { title: '#', field: 'id', sortable: true, colStyle: 'width: 50px;' },
                { title: 'User Email', tdComp: DatatableUser, sortable: false },
                { title: 'Current Payment Email', tdComp: DatatableEmail, sortable: false },
                { title: 'New Payment Email', field: 'email', sortable: true },
                { title: 'Actions', tdComp: DatatableActions, visible: true, thClass: 'text-right', tdClass: 'text-right', colStyle: 'width: 130px;' }
            ],
            query: { sort: 'id', order: 'desc' },
            xprops: {
                module: 'PendingPaymentEmails',
                route: 'pending_payment_emails',
                gate_name: 'user',
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
        ...mapGetters('PendingPaymentEmails', ['data', 'total', 'loading', 'relationships']),
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
        ...mapActions('PendingPaymentEmails', ['fetchData', 'setQuery', 'resetState']),
    }
}
</script>


<style scoped>

</style>
