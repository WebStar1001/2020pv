<template>
    <div>
        <section v-if="advisor" class="banner2">
            <div class="container">
                <div class="goback2 text-left">
                    <router-link :to="{ name: 'home.home' }" class="ui-link">
                        <i class="fa fa-long-arrow-left"></i>Home
                    </router-link>
                </div>
                <div class="row">
                    <div class="col-lg-12 abotss2 text-left">
                        <br>
                        <h2>{{ advisor.master_service.title }}</h2>
                        <br>
                        <p>{{ advisor.display_name }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section v-if="advisor" class="about-bottom77">
            <div class="container main-container">
                <div class="row">
                    <div class="col-md-7 col-12 col-lg-8 order-2 order-md-1">
                        <div class="supportpart2 elit2">
                            <div class="about-box">
                                <h4>About</h4>
                                <p>
                                    {{ advisor.description }}
                                </p>
                            </div>

                            <div class="about-box">
                                <h4>About my Services</h4>
                                <p>
                                    {{ advisor.about_services }}
                                </p>
                                <div v-if="!readmoreDescription" class="readcaret">
                                    <a @click.prevent="readmoreDescription = true" href="#" class="nav-toggle">Read more
                                        <i class="fa fa-caret-down"></i></a>
                                </div>
                            </div>

                            <transition name="fade">
                                <div v-if="readmoreDescription">
                                    <div class="about-box">
                                        <h4>Experience & Qualifications</h4>
                                        <p>
                                            {{ advisor.experience }}
                                        </p>
                                    </div>

                                    <div class="about-box">
                                        <h4>Other Qualification</h4>
                                        <p>
                                            {{ advisor.qualification }}
                                        </p>
                                        <div v-if="readmoreDescription" class="readcaret">
                                            <a @click.prevent="readmoreDescription = false" href="#" class="nav-toggle">Read
                                                less <i class="fa fa-caret-up"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </transition>

                            <hr>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="supportpart2">
                                    <h4>Specialties</h4>
                                    <br>
                                    <ul class="footnav22">
                                        <li v-for="service in advisor.services.slice(0, 5)">
                                            <img src="/images/pages/check.png" alt="sign"> {{ service.service.title }}
                                        </li>
                                    </ul>
                                    <ul v-if="readmoreSpecialties" class="footnav22">
                                        <li v-for="service in advisor.services.slice(5)">
                                            <img src="/images/pages/check.png" alt="sign"> {{ service.service.title }}
                                        </li>
                                    </ul>
                                    <div v-if="advisor.services.length > 5">
                                        <div v-if="!readmoreSpecialties" class="readcaret">
                                            <a @click.prevent="readmoreSpecialties = true" href="#" class="nav-toggle">Read
                                                more <i class="fa fa-caret-down"></i></a>
                                        </div>
                                        <div v-if="readmoreSpecialties" class="readcaret">
                                            <a @click.prevent="readmoreSpecialties = false" href="#" class="nav-toggle">Read
                                                less <i class="fa fa-caret-up"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="supportpart2 elit2">
                            <br>
                            <h4>Ratings &amp; Reviews</h4>
                            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="rates1">
                                        <span class="totalrating">{{ toFixed(advisor.rating, 1) }}</span>
                                        <div class="rating">
                                            <stars-rating
                                                    :config="{rating: advisor.rating, style: {starWidth: 18, starHeight: 18}}"
                                                    style="width: 100px; height: 20px; margin-right: 5px"></stars-rating>
                                        </div>
                                    </div>
                                    <div class="rates2">
                                        <span>{{ advisor.reviewsTotal }} Reviews</span>
                                    </div>
                                </div>
                                <div class="rates3 align-self-end">
                                    <div class="sort-select">
                                        <span data-v-1319bfe0="">Sort&nbsp;by:</span>
                                        <select @change="updateRatingFilter" class="form-control">
                                            <option value="">Any</option>
                                            <option value="5">5 Stars</option>
                                            <option value="4">4 Stars</option>
                                            <option value="3">3 Stars</option>
                                            <option value="2">2 Stars</option>
                                            <option value="1">1 Star</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <h5>Ratings &amp; Reviews</h5>
                            <br>
                            <div v-if="!advisor.reviews.length">
                                No Reviews
                            </div>

                            <div v-if="advisor.reviews.length">
                                <div v-for="review in advisor.reviews" :key="review.id" class="row boxrate">
                                    <div class="col-md-3 col-4 col-lg-4">
                                        <div class="d-flex">
                                            <div class="review-avatar"
                                                 :style="'background-image: url(' + review.customer.avatar + ')'"></div>

                                            <div class="rates5">
                                                <h6>{{ review.customer.display_name }}</h6>
                                                <span>{{ review.created_at | moment('DD MMMM YYYY') }}</span>
                                                <div class="rating">
                                                    <stars-rating
                                                            :config="{rating: review.rating, style: {starWidth: 13, starHeight: 13}}"
                                                            style="width: 80px; height: 20px;"></stars-rating>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="review.text" class="col-md-12 col-12 col-lg-8 rates6">
                                        <span>{{ review.text }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-table m-auto">
                                <paginate
                                        v-if="advisor.reviewsTotal > 0"
                                        :page-count="Math.ceil(advisor.reviewsTotal / query.limit)"
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
                    <div class="col-md-5 col-12 col-lg-4 order-1 order-md-2">
                        <div class="lftsidssa33">
                            <div class="lftsidssa">
                                <div class="ourprofiles">
                                    <img :src="advisor.avatar" alt="" style="width: 100%">
                                </div>
                                <div class="row chat">
                                    <div v-if="discount" class="old-price col-3 col-lg-3">
                                        <p class="font-weight-light"><s>${{ toFixed(advisor.price_per_minute / 100,
                                            2)}}</s></p>
                                    </div>
                                    <div class="col-lg-4 col-4">
                                        <p>${{ toFixed(getPricePerMinuteWithDiscount() / 100, 2) }}/min</p>
                                    </div>
                                </div>
                                <div class="row justify-content-end">
                                    <div class="col-9 col-lg-8 newheart mb-3">
                                        <div class="heart2">
                                            <a v-if="$gate" @click.prevent="favoriteAdvisor(advisor.id)" href="#">
                                                <span v-if="!isFavoriteAdvisor(advisor.id)">
                                                    Add to favourites <i class="pull-right fa fa-heart-o"></i>
                                                </span>
                                                <span v-if="isFavoriteAdvisor(advisor.id)">
                                                    Remove <i class="pull-right fa fa-heart"></i>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="strap">
                                    <p>
                                        <i v-if="advisor.status === 'online'" class="fa green fa-circle"></i>
                                        <i v-if="advisor.status === 'offline'" class="fa gray fa-circle"></i>
                                        <i v-if="advisor.status === 'busy'" class="fa orange fa-circle"></i>
                                        <span class="Status"> Status:</span> <strong>{{ advisor.status }}</strong>
                                    </p>
                                    <hr>
                                </div>
                                <div class="strap">
                                    <p><img width="12px" src="/images/pages/icon-time.svg" alt="icon-time"> <span
                                            class="Status"> Readings:</span> <strong>{{ advisor.chatSessionsCount
                                        }}</strong></p>
                                    <hr>
                                </div>
                                <div class="strap">
                                    <p><img width="12px" src="/images/pages/icon-per.svg" alt="icon-time"> <span
                                            class="Status"> Advisor since:</span> <strong>{{ advisor.created_at.date |
                                        moment('YYYY') }}</strong></p>
                                    <hr>
                                </div>
                                <div class="strap">
                                    <p>
                                        <i class="fa fa-globe"></i> <span class="Status"> Language:</span>
                                        <strong v-for="(language, index) in advisor.languages">
                                            {{ language.language.title }}<span
                                                v-if="index !== advisor.languages.length - 1">, </span>
                                        </strong>
                                    </p>

                                </div>
                                <div class="Started44">
                                    <br>
                                    <p v-if="$gate && !isBlockedAdvisor(advisor.id)">
                                        <button class="btn btn-success btn-block"
                                                @click="openStartChatModal(advisor)"
                                                :disabled="advisor.status !== 'online' || $store.getters.user.status === 'busy' || $gate.user.role_id !== $roles.CUSTOMER"
                                        >
                                            Chat Now
                                        </button>
                                    </p>

                                    <p v-if="$gate && !isBlockedAdvisor(advisor.id)">
                                        <router-link class="btn btn-block btn-outline-secondary"
                                                     :to="{ name: 'admin.messages.new_subject', params: {slug: advisor.slug} }"
                                                     :class="{ disabled: $gate.user.role_id !== $roles.CUSTOMER }"
                                        >
                                            Send Email
                                        </router-link>
                                    </p>

                                    <p v-if="!$gate">
                                        <router-link class="btn btn-success btn-block"
                                                     :to="{ name: 'step_register.one', params: {slug: advisor.slug} }"
                                                     :class="{ disabled: advisor.status !== 'online' }"
                                        >
                                            Chat Now
                                        </router-link>
                                    </p>

                                    <p v-if="!$gate">
                                        <router-link class="btn btn-block btn-outline-secondary"
                                                     :to="{ name: 'auth.customer_register' }"
                                        >
                                            Send Email
                                        </router-link>
                                    </p>

                                    <div v-if="isBlockedAdvisor(advisor.id)" class="text-danger">
                                        Psychic blocked you
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <become-user-section v-if="!this.$gate"></become-user-section>

    </div>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex'
    import MyMixins from '../../../mixins'
    import starsRating from "../../common/StarRating.vue";


    export default {
        mixins: [MyMixins],
        components: {
            starsRating: starsRating
        },
        data() {
            return {
                query: {
                    offset: 0,
                    limit: 5,
                    rating: null,
                    sort: 'id',
                    order: 'desc'
                },
                fetchAdvisorIntervalId: null,
                readmoreDescription: false,
                readmoreSpecialties: false,
                discount: 0,
            }
        },
        created() {
            this.fetchData(this.$route.params.slug).then(() => {
                // if (this.$gate || sessionStorage.getItem('specialoffer')) {
                    if (!this.isUsedDiscount()) {
                        this.discount = this.$store.getters.discount;
                    }
                // }

                if (this.$route.query.amount) {
                    this.openStartChatModal(this.advisor, this.$route.query.amount)
                }
            });

            this.fetchAdvisorIntervalId = setInterval(function () {
                this.fetchData(this.$route.params.slug);
            }.bind(this), 5000);
        },
        beforeDestroy() {
            clearInterval(this.fetchAdvisorIntervalId);
        },
        destroyed() {
            this.resetState()
        },
        computed: {
            ...mapGetters('ProfileIndex', [
                'advisor',
                'loading',
            ]),
            ...mapGetters('CategoryIndex', [
                'freeSeconds'
            ])
        },
        watch: {
            query: {
                handler(query) {
                    this.setQuery({
                        query: query
                    });

                    this.fetchData(this.$route.params.slug);
                },
                deep: true,
                immediate: true
            },
        },
        methods: {
            ...mapActions('ProfileIndex', [
                'resetState',
                'fetchData',
                'setQuery'
            ]),
            ...mapActions('CategoryIndex', [
                'getFreeMinutes',
                'addOrRemoveFavoriteAdvisor'
            ]),
            openStartChatModal(advisor, modalPaidAmount = null) {
                this.getFreeMinutes(advisor.id).then(() => {
                    this.$modal.show('start-chat-modal', {
                        advisor: advisor,
                        freeSeconds: this.freeSeconds,
                        modalPaidAmount: modalPaidAmount
                    });
                })
            },
            paginateHandler(pageNum) {
                this.query.offset = (pageNum - 1) * this.query.limit;
            },
            updateRatingFilter(e) {
                this.query.rating = e.target.value;
            },
            favoriteAdvisor(advisor_id) {
                this.addOrRemoveFavoriteAdvisor(advisor_id);
                this.$store.dispatch('setFavoriteAdvisors', advisor_id);
            },
            isFavoriteAdvisor(advisor_id) {
                return this.$store.getters.favoriteAdvisors.indexOf(advisor_id) !== -1;
            },
            isBlockedAdvisor(advisor_id) {
                return this.$store.getters.blockedAdvisors.indexOf(advisor_id) !== -1;
            },
            getPricePerMinuteWithDiscount() {
                if (this.discount) {
                    return this.advisor.price_per_minute - (this.advisor.price_per_minute / 100) * this.discount;
                }

                return this.advisor.price_per_minute;
            },
            isUsedDiscount() {
                let used_discount = false;

                for (let advisor_id of this.$store.getters.discountHistory) {
                    if (this.advisor.id === advisor_id) {
                        used_discount = true;
                    }
                }

                return used_discount;
            }
        }
    }
</script>


<style lang="scss" scoped>
    .abotss2 {
        h2 {
            font-size: 38px;
            font-weight: 600;
            text-shadow: 0 4px 8px rgba(15, 89, 83, .51);
            /*color: #fff;*/
        }

        p {
            font-size: 18px;
            font-weight: 500;
        }
    }

    .supportpart2 {
        h4 {
            font-weight: 600;
            margin-top: 10px;
            font-size: 18px;
            color: #5e6a6e;
        }

        li a {
            color: #5e6a6e;
        }

        img {
            margin-right: 15px;
        }
    }

    .elit2 p {
        padding-right: 100px;
    }

    .readcaret a {
        color: #00bff0;
    }

    ul.footnav, ul.footnav2, .footnav22 {
        padding: 0;
        list-style-type: none;
    }

    ul.footnav22 li {
        margin-bottom: 15px;
    }

    span.totalrating {
        color: #5e6a6e;
        font-size: 30px;
        font-weight: 600;
    }

    .rates1 {
        span {
            display: inline-block;
            margin-right: 20px;
        }

        .rating {
            display: inline-block;
        }
    }

    .rates2 {
        span {
            color: #5e6a6e;
            font-size: 16px;
            font-weight: 600;
        }
    }

    .rates3 {
        padding-right: 0;
    }

    .rates5 {
        h6 {
            margin: 0;
        }

        span {
            color: #5e6a6e;
            font-size: 12px;
        }
    }

    .rates6 {
        span {
            color: #5e6a6e;
        }

        @media (max-width: 767.98px) {
            margin-top: 15px;
        }
    }

    .review-avatar {
        width: 64px;
        height: 64px;
        min-width: 64px;
        background-size: cover;
        background-position: center center;
        border-radius: 50%;
        margin-right: 15px;
    }

    .boxrate {
        margin-bottom: 30px;
    }

    .about-box {
        margin-bottom: 50px;
    }

    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }

    .fade-enter, .fade-leave-to {
        opacity: 0;
    }

    .lftsidssa {
        background-color: #fff;
        border: 1px solid #ddd;
        padding: 10px;
        margin-top: -194px;
        margin-left: 40px;
    }

    .chat {
        margin-top: 10px;

        p {
            color: #00bff0;
            font-weight: 700;
            font-size: 22px;
        }
    }

    .heart2 {
        a {
            color: #939393;
            border: 1px solid #ddd;
            padding: 3px 9px;
            font-size: 13px;
            display: block;
            border-radius: 4px;
            margin-left: 30px;
        }

        .fa {
            color: #939393;
            line-height: 22px;

            &.fa-heart {
                color: red;
            }
        }
    }

    .strap {
        padding: 0;

        span {
            color: #92a5a4;
        }

        img, .fa {
            margin-right: 10px;
            width: 13px;
        }
    }

    .btn-outline-secondary {
        color: #626f6e;
        border-color: #626f6e;
        border-radius: 30px;

        &:hover {
            color: #ffffff;
        }
    }

    @media (max-width: 767.98px) {
        .lftsidssa {
            margin-top: 0;
            margin-left: 0;
        }
    }
</style>
