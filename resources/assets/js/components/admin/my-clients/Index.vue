<template>
    <section class="content-box">
        <div class="">
            <div class="box-header">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="box-title">My Clients</h3>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        <div class="my-psychic-search">
                            <input
                                    type="search"
                                    :value="query.search"
                                    @input="search"
                                    id="search"
                                    class="form-control"
                                    placeholder="Search for your psychic"
                            >
                            <button @click="search" class="btn" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <div v-if="loading" class="loading-box">
                        <i class="fa fa-spin fa-refresh"></i> Loading
                    </div>

                    <div v-if="!loading" class="row">
                        <div v-for="customer in data" class="col-lg-6 col-xl-3">
                            <div class="profile-card text-center mb-4">
                                <div class="profile-card-header">
                                    <div v-if="!customer.blocked" @click="block(customer.id)" class="badge-like">
                                        <i class="fa fa-ban"></i>
                                    </div>
                                    <div v-if="customer.blocked" class="badge-like">
                                        <button @click="unblock(customer.id)" class="btn btn-warning btn-sm">Unblock</button>
                                    </div>

                                    <div class="d-table m-auto">
                                        <div class="avatar" :style="'background: url(' + customer.avatar + ')'">
                                            <div class="user-status" :class="customer.status"><span></span></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="profile-card-body">
                                    <div class="name mb-1">
                                        {{ customer.display_name }}
                                    </div>
                                    <div class="text-muted mb-3">
                                        Since {{ customer.created_at | moment("YYYY") }}
                                    </div>
                                    <div class="history-link mb-3">
                                        <router-link :to="{ name: 'admin.chat_history.index', params: {id: customer.id} }">
                                            Chat History
                                        </router-link>
                                    </div>
                                    <router-link class="btn btn-email w-100"
                                                 :to="{ name: 'admin.messages.new_subject', params: {slug: customer.slug} }"
                                    >
                                        <i class="icomoon icomoon-messages mr-2"></i>SEND MESSAGE
                                    </router-link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="!loading && !total" class="mb-5">
                        You don't have any clients yet
                    </div>

                    <paginate
                            v-if="total > 0"
                            :page-count="Math.ceil(total / query.limit)"
                            :click-handler="paginateHandler"
                            :prev-text="'Prev'"
                            :next-text="'Next'"
                            :container-class="'pagination'"
                            :page-class="'page-item'"
                            :page-link-class="'page-link'"
                            :prev-class="'page-item'"
                            :prev-link-class="'page-link'"
                            :next-class="'page-item'"
                            :next-link-class="'page-link'">
                    </paginate>

                </div>
            </div>
        </div>
    </section>
</template>


<script>
    import { mapGetters, mapActions } from 'vuex'

    export default {
        data() {
            return {
                query: {
                    offset: 0,
                    limit: 10,
                    search: '',
                },
                fetchDataIntervalId: null,
            }
        },
        created() {
            this.setLoading(true);
            this.fetchDataIntervalId = setInterval(this.fetchData, 5000);
        },
        beforeDestroy() {
            clearInterval(this.fetchDataIntervalId);
        },
        destroyed() {
            this.resetState()
        },
        computed: {
            ...mapGetters('MyClientsIndex', [
                'data',
                'total',
                'loading'
            ]),
        },
        watch: {
            query: {
                handler(query) {
                    this.setQuery({
                        query: query
                    });

                    this.fetchData();
                },
                deep: true,
                immediate: true
            },
        },
        methods: {
            ...mapActions('MyClientsIndex', [
                'fetchData',
                'resetState',
                'setLoading',
                'setQuery',
                'blockCustomer',
                'unblockCustomer'
            ]),
            paginateHandler(pageNum) {
                this.query.offset = (pageNum - 1) * this.query.limit;
            },
            block(customer_id) {
                this.blockCustomer(customer_id);
            },
            unblock(customer_id) {
                this.unblockCustomer(customer_id);
            },
            search(e) {
                this.query.offset = 0;
                this.query.search = e.target.value;
            }
        }
    }
</script>


<style scoped>

</style>
