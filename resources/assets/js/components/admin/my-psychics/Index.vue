<template>
    <section class="content-box">
        <div class="">
            <div class="box-header">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="box-title">My Psychics</h3>
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
                        <div v-for="advisor in data" class="col-lg-6 col-xl-3">
                            <div class="profile-card text-center mb-4">
                                <div class="profile-card-header">
                                    <div @click="favoriteAdvisor(advisor.id)" class="badge-like">
                                        <i class="fa fa-heart"></i>
                                    </div>

                                    <router-link :to="{ name: 'profile.profile.index', params: { masterService: advisor.master_service.slug, slug: advisor.slug } }" class="d-table m-auto">
                                        <div class="avatar" :style="'background: url(' + advisor.avatar + ')'">
                                            <div class="user-status" :class="advisor.status"><span></span></div>
                                        </div>
                                    </router-link>
                                </div>

                                <div class="profile-card-body">
                                    <div class="name mb-1">
                                        {{ advisor.display_name }}
                                    </div>
                                    <div class="text-muted mb-3">
                                        Advisor Since {{ advisor.created_at | moment("YYYY") }}
                                    </div>
                                    <div class="history-link mb-3">
                                        <router-link :to="{ name: 'admin.chat_history.index', params: {id: advisor.id} }">
                                            Chat History
                                        </router-link>
                                    </div>

                                    <div v-if="isBlockedAdvisor(advisor.id)" class="text-danger pb-3">
                                        Psychic blocked you
                                    </div>

                                    <button class="btn btn-success w-100"
                                            v-if="!isBlockedAdvisor(advisor.id) && advisor.status === 'online'"
                                            @click="openStartChatModal(advisor)"
                                    >
                                        <img src="/images/profile-card/meassage.png" class="mr-1">CHAT NOW
                                    </button>

                                    <div v-if="advisor.status !== 'online'">
                                        <router-link class="btn btn-email w-100"
                                                     :to="{ name: 'admin.messages.new_subject', params: {slug: advisor.slug} }"
                                        >
                                            <i class="icomoon icomoon-messages mr-2"></i>SEND MESSAGE
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="!loading && !total" class="mb-5">
                        You don't have any favourite Psychics yet, choose any Psychic and add him/her to your favourites.
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

                    <div class="my-psychics-featured-advisors">
                        <featured-advisors title="Available Psychics"></featured-advisors>
                    </div>

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
                    search: ''
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
            ...mapGetters('MyPsychicsIndex', [
                'data',
                'total',
                'loading'
            ]),
            ...mapGetters('CategoryIndex', [
                'freeSeconds'
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
            ...mapActions('MyPsychicsIndex', [
                'fetchData',
                'resetState',
                'setQuery',
                'setLoading'
            ]),
            ...mapActions('CategoryIndex', [
                'getFreeMinutes',
                'addOrRemoveFavoriteAdvisor'
            ]),
            paginateHandler(pageNum) {
                this.query.offset = (pageNum - 1) * this.query.limit;
            },
            favoriteAdvisor(advisor_id) {
                this.setLoading(true);

                this.addOrRemoveFavoriteAdvisor(advisor_id).then(resp => {
                    this.$store.dispatch('setFavoriteAdvisors', advisor_id);
                    this.fetchData();
                });
            },
            openStartChatModal(advisor) {
                this.getFreeMinutes(advisor.id).then(() => {
                    this.$modal.show('start-chat-modal', {
                        advisor: advisor,
                        freeSeconds: this.freeSeconds
                    });
                })
            },
            isBlockedAdvisor(advisor_id) {
                return this.$store.getters.blockedAdvisors.indexOf(advisor_id) !== -1;
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
