<template>
    <div>
        <section class="container main-container">
            <h2 class="text-center">Choose From {{ total }} <span class="text-capitalize">{{ getPageTitle() }}</span> Advisors</h2>
            <br>

            <div class="d-flex filters row">
                <div class="col-lg-5 d-flex align-items-center mb-3 mb-lg-0">
                    <div class="d-flex mr-4">
                        <img src="/images/category/filter.png" alt="Filter" class="mr-1">
                        Filter
                    </div>

                    <div class="mr-3">Price:&nbsp;<span v-if="priceValues">${{ priceValues[0] }}</span></div>

                    <div class="w-100 filter-price-slider-wrapper">
                        <vue-slider
                                v-if="priceValues"
                                :value="priceValues"
                                @drag-end="setPriceFilter"
                                :tooltip-formatter="val => '$' + val"
                                :min="1"
                                :max="maxPricePerMinute"
                                ref="priceSlider"
                        />
                    </div>
                    <div v-if="priceValues" class="ml-3">
                        ${{ priceValues[1] }}
                    </div>
                </div>
                <div class="col-lg-7 d-flex justify-content-end align-items-lg-center flex-column flex-lg-row">
                    <div class="sort-select mr-3">
                        <span>Availability:</span>
                        <select @change="updateAvailability" id="availability" class="form-control">
                            <option value="">Any</option>
                            <option value="online">Online</option>
                            <option value="offline">Offline</option>
                        </select>
                    </div>

                    <div class="sort-select mr-3">
                        <span>Sort&nbsp;By:</span>
                        <select @change="updateSort" id="sort" class="form-control">
                            <option value="id">New</option>
                            <option value="rating">Top Rated</option>
                            <option value="top-paid">Top Paid</option>
                            <option value="low-paid">Low Paid</option>
                        </select>
                    </div>

                    <ul class="nav nav-pills nav-fill navtop3 d-none d-lg-flex">
                        <li class="nav-item">
                            <a @click.prevent="setGridView(false)" :class="{ active: !gridView }" class="nav-link ui-link" href="#list" data-toggle="tab"><i class="fa fa-th-list"></i></a>
                        </li>
                        <li class="nav-item">
                            <a @click.prevent="setGridView(true)" :class="{ active: gridView }" class="nav-link ui-link" href="#grid" data-toggle="tab"><i class="fa fa-th"></i></a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="view row">
                <div v-for="advisor in advisors" :class="{'col-sm-6 col-lg-3': gridView}" class="col-12">
                    <profile-card :profile="advisor" :row="!gridView"></profile-card>
                </div>
            </div>

            <div class="d-flex justify-content-between pagination-row"  v-if="total > 0">
                <paginate
                        :page-count="Math.ceil(total / query.limit)"
                        :click-handler="paginateHandler"
                        :prev-text="'<i class=\'fa fa-angle-left prev\'></i>'"
                        :next-text="'<i class=\'fa fa-angle-right next\'></i>'"
                        :container-class="'pagination'"
                        :page-class="'page-item'"
                        :page-link-class="'page-link'"
                        :prev-class="'page-item'"
                        :prev-link-class="'page-link'"
                        :next-class="'page-item'"
                        :next-link-class="'page-link'">
                </paginate>
            </div>
            <div v-if="total === 0">
                Not Found
            </div>
        </section>

        <become-user-section v-if="!this.$gate"></become-user-section>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import MyMixins from '../../../mixins'
    import VueSlider from 'vue-slider-component'
    import 'vue-slider-component/theme/antd.css'

    export default {
        components: {
            VueSlider
        },
        mixins: [MyMixins],
        data() {
            return {
                query: {
                    sort: 'id',
                    order: 'desc',
                    offset: 0,
                    limit: 10,
                    availability: '',
                    minPrice: null,
                    maxPrice: null
                },
                customer: {},
                advisor: {},
                fetchDataIntervalId: null,
                countdownSeconds: 180, // 180,
                gridView: false,
            }
        },
        created() {
            if (localStorage.getItem('gridView')) {
                this.gridView = localStorage.getItem('gridView') === 'true';
            }

            this.fetchDataIntervalId = setInterval(this.fetchData, 5000);
        },
        beforeDestroy() {
            clearInterval(this.fetchDataIntervalId);
        },
        destroyed() {
            this.resetState()
        },
        computed: {
            ...mapGetters('CategoryIndex', [
                'advisors',
                'total',
                'loading',
                'maxPricePerMinute',
                'priceValues'
            ]),
        },
        watch: {
            query: {
                handler(query) {
                    this.setQuery({
                        query: query,
                        slug: this.$route.params.slug
                    });

                    this.fetchData();
                },
                deep: true,
                immediate: true
            },
            '$route.params.slug': function (slug) {
                this.setQuery({
                    query: this.query,
                    slug: slug
                });

                this.fetchData();
            },
        },
        methods: {
            ...mapActions('CategoryIndex', [
                'setQuery',
                'resetState',
                'fetchData',
                'setPriceValues',
            ]),
            paginateHandler(pageNum) {
                this.query.offset = (pageNum - 1) * this.query.limit;
            },
            setGridView(value) {
                localStorage.setItem('gridView', value);
                this.gridView = value;
            },
            updateAvailability(e) {
                this.query.availability = e.target.value;
            },
            setPriceFilter() {
                const values = this.$refs.priceSlider.getValue();

                this.query.minPrice = values[0];
                this.query.maxPrice = values[1];
            },
            updateSort(e) {
                this.query.sort = e.target.value;
            },
            getPageTitle() {
                return this.$route.params.slug.replace('-and-', ' & ').split('-').join(' ')
            }
        }
    }
</script>


<style lang="scss" scoped>
    @media (max-width: 767.98px) {
        h2 {
            font-size: 18px;
        }
    }

    .filters {
        margin-bottom: 40px;
    }

    ul.nav.nav-pills.nav-fill.navtop3 a {
        padding: 4px 10px;
        background-color: #f4fafa;
        color: #b3bdbc;
        &.active {
            border-radius: 0;
            background-color: #00bff0;
            color: #fff;
        }
    }
</style>
