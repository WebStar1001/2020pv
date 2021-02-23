<template>
    <section class="block Meet">
        <div class="Meet2 text-center">
            <h2 class="block-title">Meet our psychics advisors</h2>
        </div>
        <div class="container" id="featured_advisor_section">
            <!--<ul class="nav nav-pills nav-fill navtop">-->
            <!--<li class="nav-item">-->
            <!--<a class="nav-link active" href="#menu1" data-toggle="tab">Featured</a>-->
            <!--</li>-->
            <!--<li class="nav-item">-->
            <!--<a class="nav-link" href="#menu2" data-toggle="tab">Top rated</a>-->
            <!--</li>-->
            <!--<li class="nav-item">-->
            <!--<a class="nav-link" href="#menu3" data-toggle="tab">Top paid</a>-->
            <!--</li>-->
            <!--<li class="nav-item">-->
            <!--<a class="nav-link" href="#menu4" data-toggle="tab">New psychics</a>-->
            <!--</li>-->
            <!--</ul>-->
            <div class="tab-content">
                <div class="tab-pane active" role="tabpanel" id="menu1">
                    <div class="row">
                        <div v-for="advisor in featuredAdvisors" class="col-md-4 col-lg-3">
                            <profile-card :profile="advisor"></profile-card>
                        </div>


                    </div>
                    <div class="row justify-content-center">
                        <div class="text-center">
                            <button v-if="total > query.featuredAdvisorsLimit" @click="loadmoreFeaturedAdvisors()"
                                    type="button" class="btn btn-outline-success">Load More
                            </button>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" role="tabpanel" id="menu2">
                    <div class="row">
                        <div v-for="advisor in topRatedAdvisors" class="col-md-4 col-lg-3">
                            <profile-card :profile="advisor"></profile-card>
                        </div>

                        <div class="text-center">
                            <button v-if="total > query.topRatedAdvisorsLimit" @click="loadmoreTopRatedAdvisors()"
                                    type="button" class="btn btn-outline-success">Load More
                            </button>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" role="tabpanel" id="menu3">
                    <div class="row">
                        <div v-for="advisor in topPaidAdvisors" class="col-md-4 col-lg-3">
                            <profile-card :profile="advisor"></profile-card>
                        </div>

                        <div class="text-center">
                            <button v-if="total > query.topPaidAdvisorsLimit" @click="loadmoreTopPaidAdvisors()"
                                    type="button" class="btn btn-outline-success">Load More
                            </button>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" role="tabpanel" id="menu4">
                    <div class="row">
                        <div v-for="advisor in newAdvisors" class="col-md-4 col-lg-3">
                            <profile-card :profile="advisor"></profile-card>
                        </div>

                        <div class="text-center">
                            <button v-if="total > query.newAdvisorsLimit" @click="loadmoreNewAdvisors()" type="button"
                                    class="btn btn-outline-success">Load More
                            </button>
                        </div>
                    </div>
                </div>


            </div>


        </div>
    </section>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex'

    export default {
        props: ['title'],
        data() {
            return {
                query: {
                    featuredAdvisorsSort: 'rank',
                    featuredAdvisorsOrder: 'desc',
                    featuredAdvisorsOffset: 0,
                    featuredAdvisorsLimit: 4,
                    topRatedAdvisorsSort: 'rating',
                    topRatedAdvisorsOrder: 'desc',
                    topRatedAdvisorsOffset: 0,
                    topRatedAdvisorsLimit: 4,
                    topPaidAdvisorsSort: 'sales',
                    topPaidAdvisorsOrder: 'desc',
                    topPaidAdvisorsOffset: 0,
                    topPaidAdvisorsLimit: 4,
                    newAdvisorsSort: 'id',
                    newAdvisorsOrder: 'desc',
                    newAdvisorsOffset: 0,
                    newAdvisorsLimit: 4
                },
                fetchDataIntervalId: null,
            };
        },
        created() {
            this.fetchDataIntervalId = setInterval(this.fetchData, 5000);
        },
        beforeDestroy() {
            clearInterval(this.fetchDataIntervalId);
        },
        destroyed() {
            this.resetState()
        },
        computed: {
            ...mapGetters('FeaturedAdvisors', [
                'featuredAdvisors',
                'topRatedAdvisors',
                'topPaidAdvisors',
                'newAdvisors',
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
            ...mapActions('FeaturedAdvisors', [
                'fetchData',
                'resetState',
                'setQuery'
            ]),
            loadmoreFeaturedAdvisors() {
                this.query.featuredAdvisorsLimit = this.query.featuredAdvisorsLimit + 4;
            },
            loadmoreTopRatedAdvisors() {
                this.query.topRatedAdvisorsLimit = this.query.topRatedAdvisorsLimit + 4;
            },
            loadmoreTopPaidAdvisors() {
                this.query.topPaidAdvisorsLimit = this.query.topPaidAdvisorsLimit + 4;
            },
            loadmoreNewAdvisors() {
                this.query.newAdvisorsLimit = this.query.newAdvisorsLimit + 4;
            }
        }
    }
</script>

<style lang="scss" scoped>
    .block {
        padding-bottom: 50px;
    }

    .Meet {
        ul.nav.nav-pills.nav-fill.navtop {
            margin: 0 90px;
            @media (max-width: 767.98px) {
                margin: 0;
            }

            li {
                @media (max-width: 767.98px) {
                    width: 50%;
                }
            }
        }

        .nav-link {
            display: block;
            background-color: #edf1f2;
            font-weight: 600;
            border-radius: 0;
            font-size: 16px;
            padding: 20px;
            color: #b0b9be;
            margin-right: 2px;

            &.active {
                color: #fff;
                background-color: #00bff0;
                padding: 20px;
            }
        }

        .tab-content {
            margin: 40px 0;
        }
    }
</style>
