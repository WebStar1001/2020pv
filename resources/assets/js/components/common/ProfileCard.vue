<template>
    <div class="h-100">
        <!-- start grid view -->
        <div v-if="gridView && !mobileView" class="box1">
            <div class="advisors">
                <router-link
                        :to="{ name: 'profile.profile.index', params: { masterService: profile.master_service.slug, slug: profile.slug } }">
                    <div class="avatar" :style="'background: url(' + profile.avatar + ')'"></div>
                </router-link>
                <div v-if="profile.top_advisor" class="psychic">
                    <p>Top psychic</p>
                </div>
                <div @click="favoriteAdvisor(profile.id)" class="heart">
                    <i class="text-white fa fa-heart" :class="{ active: isFavoriteAdvisor(profile.id) }"></i>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="row strap">
                    <div class="col-8 col-lg-8" style="white-space: nowrap;">
                        <p>{{ profile.display_name }}</p>
                    </div>
                    <div class="col-4 col-lg-4 text-right">
                        <p v-if="profile.status === 'online'"><i class="fa green fa-circle"></i> Online</p>
                        <p v-if="profile.status === 'offline'"><i class="fa gray fa-circle"></i> Offline</p>
                        <p v-if="profile.status === 'busy'"><i class="fa orange fa-circle"></i> Busy</p>
                    </div>
                </div>
                <h4>{{ limitStr(profile.highlight, 50) }}</h4>
                <div class="rating mt-3 d-flex">
                    <stars-rating
                            :config="{rating: profile.rating, style: {starWidth: 16, starHeight: 16}}"></stars-rating>
                    <span>{{ profile.rating }}</span>
                </div>
                <div class="row strap">
                    <div class="col-6 col-lg-6">
                        <p><img src="/images/profile-card/icon-time.svg" alt="icon-time"> {{ profile.chatSessionsCount
                            }}</p>
                    </div>
                    <div class="col-6 col-lg-6">
                        <p><img src="/images/profile-card/icon-per.svg" alt="icon-per"> {{ profile.created_at |
                            moment('YYYY') }}</p>
                    </div>
                </div>
                <div class="row chat align-items-lg-end align-items-end">
                    <div class="col-lg-4 col-4">
                        <div class="row">
                            <div class="old-price col-12 col-lg-12 chat">
                                <p class="font-weight-light">
                                    <s v-if="discount">${{ toFixed(profile.price_per_minute / 100, 2)}}</s>
                                    <s v-if="!discount">Used</s>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="old-price col-12 col-lg-12 chat">
                                <p>
                                    ${{ toFixed(getPricePerMinuteWithDiscount() / 100, 2) }}/min
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-8 col-lg-8 text-right mb-3">
                        <button class="btn btn-success"
                                v-if="buttons_show && $gate && !isBlockedAdvisor(profile.id) && profile.status !== 'offline' && profile.status !== 'busy'"
                                @click="openStartChatModal(profile)"
                                :disabled="profile.status !== 'online' || ($gate && $gate.user.role_id !== $roles.CUSTOMER)"
                        >
                            <img src="/images/profile-card/meassage.png" alt=""> Chat Now
                        </button>
                        <router-link class="btn btn-secondary"
                                     v-if="buttons_show && $gate && !isBlockedAdvisor(profile.id) && (profile.status === 'offline' || profile.status === 'busy')"
                                     :to="{ name: 'admin.messages.new_subject', params: {slug: profile.slug} }"
                                     :class="{ disabled: $gate.user.role_id !== $roles.CUSTOMER }"
                        >
                            <img src="/images/profile-card/mail.png" alt="mail"> Send Mail
                        </router-link>

                        <router-link class="btn btn-success"
                                     v-if="buttons_show && !$gate && profile.status === 'online'"
                                     :to="{ name: 'step_register.one', params: {slug: profile.slug} }"
                        >
                            <img src="/images/profile-card/meassage.png" alt=""> Chat Now
                        </router-link>

                        <router-link class="btn btn-secondary"
                                     v-if="buttons_show && !$gate && profile.status !== 'online'"
                                     :to="{ name: 'auth.customer_register' }"
                        >
                            <img src="/images/profile-card/mail.png" alt="mail"> Send Mail
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
        <!-- end grid view -->

        <!-- start list view -->
        <div v-if="!gridView" class="box1 listview">
            <div class="row">
                <div class="col-md-4 col-lg-3">
                    <div class="advisors">
                        <router-link
                                :to="{ name: 'profile.profile.index', params: { masterService: profile.master_service.slug, slug: profile.slug } }">
                            <div class="avatar" :style="'background: url(' + profile.avatar + ')'"></div>
                        </router-link>
                        <div v-if="profile.top_advisor" class="psychic">
                            <p>Top psychic</p>
                        </div>
                        <div @click="favoriteAdvisor(profile.id)" class="heart">
                            <i class="text-white fa fa-heart" :class="{ active: isFavoriteAdvisor(profile.id) }"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-lg-9">
                    <div class="list-view-container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                        <div class="strap mr-3">
                                            <p>{{ profile.display_name }}</p>
                                        </div>
                                        <div class="strap mr-3">
                                            <p v-if="profile.status === 'online'"><i class="fa green fa-circle"></i>
                                                Online</p>
                                            <p v-if="profile.status === 'offline'"><i class="fa gray fa-circle"></i>
                                                Offline</p>
                                            <p v-if="profile.status === 'busy'"><i class="fa orange fa-circle"></i> Busy
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="chat">
                                            <p>
                                                <span v-if="discount"><s>${{ toFixed(profile.price_per_minute / 100, 2)}}</s></span>
                                                <span>${{ toFixed(getPricePerMinuteWithDiscount() / 100, 2) }}/min</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-11 accurate">
                                <h4 style="height: auto">{{ limitStr(profile.highlight, 50) }}</h4>
                                <p class="description">{{ limitStr(profile.description, 200) }}</p>
                            </div>
                            <div class="col-md-12 col-lg-12">
                                <div class="row align-items-center">
                                    <div class="col-md-4 col-lg-3 ">
                                        <div class="rating d-flex">
                                            <stars-rating
                                                    :config="{rating: profile.rating, style: {starWidth: 16, starHeight: 16}}"></stars-rating>
                                            <span>{{ profile.rating }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-6">
                                        <div class="row strap">
                                            <div class="col-6 col-lg-6">
                                                <p><img src="/images/profile-card/icon-time.svg" alt="icon-time"> {{
                                                    profile.chatSessionsCount }} Readings</p>
                                            </div>
                                            <div class="col-6 col-lg-6">
                                                <p><img src="/images/profile-card/icon-per.svg" alt="icon-per"> Advisor
                                                    since {{ profile.created_at | moment('YYYY') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-3">
                                        <div class="chat">
                                            <div class="text-right">
                                                <button class="btn btn-success"
                                                        v-if="buttons_show && $gate && !isBlockedAdvisor(profile.id) && profile.status !== 'offline' && profile.status !== 'busy'"
                                                        @click="openStartChatModal(profile)"
                                                        :disabled="profile.status !== 'online' || ($gate && $gate.user.role_id !== $roles.CUSTOMER)"
                                                >
                                                    <img src="/images/profile-card/meassage.png" alt=""> Chat Now
                                                </button>

                                                <router-link class="btn btn-secondary"
                                                             v-if="buttons_show && $gate && !isBlockedAdvisor(profile.id) && (profile.status === 'offline' || profile.status === 'busy')"
                                                             :to="{ name: 'admin.messages.new_subject', params: {slug: profile.slug} }"
                                                             :class="{ disabled: $gate.user.role_id !== $roles.CUSTOMER }"
                                                >
                                                    <img src="/images/profile-card/mail.png" alt="mail"> Send Mail
                                                </router-link>

                                                <router-link class="btn btn-success"
                                                             v-if="buttons_show && !$gate && profile.status === 'online'"
                                                             :to="{ name: 'step_register.one', params: {slug: profile.slug} }"
                                                >
                                                    <img src="/images/profile-card/meassage.png" alt=""> Chat Now
                                                </router-link>

                                                <router-link class="btn btn-secondary"
                                                             v-if="buttons_show && !$gate && profile.status !== 'online'"
                                                             :to="{ name: 'auth.customer_register' }"
                                                >
                                                    <img src="/images/profile-card/mail.png" alt="mail"> Send Mail
                                                </router-link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end list view -->

        <!-- start mobile view -->
        <div v-if="mobileView" class="mobile-view">
            <div class="d-flex mb-2">
                <div class="avatar-container">
                    <router-link
                            :to="{ name: 'profile.profile.index', params: { masterService: profile.master_service.slug, slug: profile.slug } }">
                        <div class="avatar" :style="'background: url(' + profile.avatar + ')'"></div>
                        <div v-if="profile.top_advisor" class="top-psychic">
                            <img src="/images/profile-card/crown.svg" alt="Top Psychic">
                        </div>
                    </router-link>
                </div>
                <div class="d-flex w-100 justify-content-between">
                    <div class="mr-3">
                        <div class="name mb-1">{{ profile.display_name }}</div>
                        <div class="rating mb-1 d-flex">
                            <stars-rating
                                    :config="{rating: profile.rating, totalStars: 1, style: {starWidth: 16, starHeight: 16}}"></stars-rating>
                            <span>{{ profile.rating }}</span>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div v-if="discount" class="old-price">
                                <s>${{ toFixed(profile.price_per_minute / 100, 2) }}/min</s>
                            </div>
                            <div class="price mr-2">
                                <span>${{ toFixed(getPricePerMinuteWithDiscount() / 100, 2) }}</span>/min
                            </div>
                        </div>
                        <div class="highlight">
                            {{ limitStr(profile.highlight, 50) }}
                            <router-link
                                    :to="{ name: 'profile.profile.index', params: { masterService: profile.master_service.slug, slug: profile.slug } }">
                                Read More
                            </router-link>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="status" :class="profile.status">
                            <span v-if="profile.status === 'online'">Online</span>
                            <span v-if="profile.status === 'offline'">Offline</span>
                            <span v-if="profile.status === 'busy'">Busy</span>
                        </div>
                        <div class="chat">
                            <button class="btn"
                                    v-if="buttons_show && $gate && !isBlockedAdvisor(profile.id) && profile.status !== 'offline' && profile.status !== 'busy'"
                                    @click="openStartChatModal(profile)"
                                    :disabled="profile.status !== 'online' || ($gate && $gate.user.role_id !== $roles.CUSTOMER)"
                            >
                                <img src="/images/profile-card/chat.svg">
                                Chat
                            </button>

                            <router-link class="btn"
                                         v-if="buttons_show && $gate && !isBlockedAdvisor(profile.id) && (profile.status === 'offline' || profile.status === 'busy')"
                                         :to="{ name: 'admin.messages.new_subject', params: {slug: profile.slug} }"
                                         :class="{ disabled: $gate.user.role_id !== $roles.CUSTOMER }"
                            >
                                <img src="/images/profile-card/chat.svg">
                                Mail
                            </router-link>

                            <router-link class="btn"
                                         v-if="buttons_show && !$gate && profile.status === 'online'"
                                         :to="{ name: 'step_register.one', params: {slug: profile.slug} }"
                            >
                                <img src="/images/profile-card/chat.svg">
                                Chat
                            </router-link>

                            <router-link class="btn"
                                         v-if="buttons_show && !$gate && profile.status !== 'online'"
                                         :to="{ name: 'auth.customer_register' }"
                            >
                                <img src="/images/profile-card/chat.svg">
                                Mail
                            </router-link>
                        </div>
                        <div class="min-free">
                            <div v-if="$route.name === 'public.landing'">
                                ${{ toFixed(getPricePerMinuteWithDiscount() * 3 / 100, 2) }} Free<br> to Try
                            </div>
                            <div v-else>
                                <div v-if="$gate && freeSeconds">3 Min<br> Free</div>
                                <div v-if="!$gate">3 Min<br> Free</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <div class="readings mr-3">
                    <img src="/images/profile-card/icon-time.svg" width="14" alt="">
                    {{ profile.chatSessionsCount }} Readings
                </div>
                <div class="text-right">
                    <div v-if="discount" class="special-offer">
                        <span style="color: #FFC104;">Special offer:</span>
                        Enjoy {{ discount }}% OFF
                    </div>

                    <div v-if="$gate && $gate.user.role_id === $roles.CUSTOMER && !discount && $store.getters.discountActive"
                         class="special-offer">
                        <span style="color: #FFC104;">Special offer:</span>
                        Used
                    </div>
                </div>
            </div>
        </div>
        <!-- end mobile view -->
    </div>
</template>


<script>
    import MyMixins from '../../mixins'
    import {mapGetters, mapActions} from 'vuex'
    import starsRating from "./StarRating.vue";

    export default {
        components: {
            starsRating: starsRating,
        },
        mixins: [MyMixins],
        props: {
            profile: {},
            buttons_show: {
                default: true
            },
            row: false
        },
        data() {
            return {
                gridView: false,
                mobileView: false,
                discount: 0,
            }
        },
        created() {
            this.gridView = !this.row;
            if (this.$gate) {
                this.getFreeMinutes(this.profile.id)
            }
        },

        updated() {

        },
        mounted() {
            if (this.buttons_show) {
                this.$nextTick(function () {
                    window.addEventListener('resize', this.getWindowWidth);

                    this.getWindowWidth()
                })
            }
            // if (this.$gate || sessionStorage.getItem('specialoffer')) {
            if (!this.isUsedDiscount()) {
                this.discount = this.$store.getters.discount;
            }
            // }

        },
        computed: {
            ...mapGetters('CategoryIndex', [
                'freeSeconds'
            ]),
        },
        watch: {
            row: function () {
                this.gridView = !this.row;
            }
        },
        methods: {
            ...mapActions('CategoryIndex', [
                'getFreeMinutes',
                'addOrRemoveFavoriteAdvisor'
            ]),
            getWindowWidth(event) {
                if (window.innerWidth < 576) {
                    this.gridView = true;
                    this.mobileView = true;
                } else {
                    this.gridView = !this.row;
                    this.mobileView = false;
                }
            },
            openStartChatModal(advisor) {
                this.$modal.show('start-chat-modal', {
                    advisor: advisor,
                    freeSeconds: this.freeSeconds
                });
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
            limitStr(string, limit) {
                let str = string;

                if (typeof str === 'string' && str.length > limit) {
                    str = str.slice(0, limit) + '...';
                }

                return str;
            },
            getPricePerMinuteWithDiscount() {
                if (this.discount) {
                    return this.profile.price_per_minute - (this.profile.price_per_minute / 100) * this.discount;
                }

                return this.profile.price_per_minute;
            },
            isUsedDiscount() {
                let used_discount = false;
                for (let advisor_id of this.$store.getters.discountHistory) {
                    if (this.profile.id === advisor_id) {
                        used_discount = true;
                    }
                }

                return used_discount;
            }
        }
    }
</script>


<style lang="scss" scoped>
    .box1 {
        box-shadow: 0 2px 4px rgba(0, 0, 0, .14);
        border-radius: 4px;
        margin-bottom: 30px;
        background: #ffffff;

        h4 {
            font-size: 16px;
            font-weight: 700;
            height: 38px;
            overflow: hidden;
        }
    }

    .advisors {
        position: relative;

        img {
            border-radius: 4px 4px 0 0;
        }

        .avatar {
            height: 181px;
            background-size: cover !important;
            background-position: center center !important;
        }
    }

    .psychic {
        position: absolute;
        top: 25px;

        p {
            background-color: #f5a623;
            font-size: 11px;
            color: #25292f;
            margin: 0;
            padding: 4px 20px 4px 13px;

            &:before {
                width: 0;
                content: '';
                height: 0;
                border-top: 20px solid #f5a623;
                border-right: 14px solid transparent;
                position: absolute;
                right: -14px;
                top: 0;
            }

            &:after {
                width: 0;
                content: '';
                height: 0;
                border-bottom: 20px solid #f5a623;
                border-right: 14px solid transparent;
                position: absolute;
                right: -14px;
                bottom: 0;
            }
        }
    }

    .heart {
        position: absolute;
        top: 25px;
        right: 12px;
        cursor: pointer;

        .fa {
            font-size: 20px;

            &.active {
                color: red !important;
            }
        }
    }

    .strap {
        padding: 16px 0 0;

        p {
            color: #5e6a6e;
            font-size: 12px;
        }

        img {
            margin-right: 10px;
            width: 13px;
        }
    }

    .chat {
        /*margin-top: 10px;*/

        p {
            color: #00bff0;
            font-weight: 700;
            font-size: 18px;
        }

        .btn-success {
            font-size: 12px;
            border-radius: 22px;
        }
    }

    .rating {
        span {
            font-size: 12px;
            color: #939393;
            margin-left: 7px;
        }
    }

    .strap22 {
        padding-top: 8px;
    }

    .accurate {
        color: #5e6a6e;
    }

    .list-view-container {
        padding: 15px 30px 15px 0;
        @media (max-width: 767.98px) {
            padding-left: 15px;
            padding-right: 15px;
        }
    }

    .description {
        height: 42px;
    }

    .listview {
        box-shadow: none;
        border: 1px solid #eee;

        .strap {
            padding: 0;

            p {
                margin: 0;
                font-size: 13px;
            }
        }

        .chat {
            margin-top: 0;

            p {
                margin: 0;
                @media (max-width: 767.98px) {
                    font-size: 11px;
                }
            }

            .btn-success,
            .btn-secondary {
                padding: .7em 1em;
                width: 100%;
            }
        }

        @media (max-width: 767.98px) {
            .rating {
                margin-bottom: 10px;
            }
            .strap {
                margin-bottom: 10px;
            }
        }
    }

    .mobile-view {
        margin-bottom: 20px;

        .avatar-container {
            position: relative;
            margin-right: 20px;

            .avatar {
                width: 100px;
                height: 130px;
                border-radius: 10px;
                background-size: cover !important;
                background-position: center center !important;
            }

            .top-psychic {
                position: absolute;
                left: -8px;
                top: -8px;
            }
        }

        .name {
            font-size: 20px;
            font-weight: 600;
        }

        .price {
            span {
                font-size: 20px;
                font-weight: 600;
            }
        }

        .old-price {
            font-weight: 600;
            color: #CACACA;
            text-decoration: line-through;
        }

        .highlight {
            font-size: 12px;
            color: #8D9599;
        }

        .status {
            margin-bottom: 5px;
            font-size: 12px;

            &.online {
                color: #EC7A4C;
            }

            &.busy {
                color: red;
            }

            &.offline {
                color: #CACACA;
            }
        }

        .chat {
            margin-bottom: 5px;

            .btn {
                padding: 0;
                font-weight: 500;
                color: #86CD03;
            }

            img {
                display: table;
                margin: 0 auto;
            }
        }

        .min-free {
            font-size: 12px;
            color: #FFC104;
            text-align: center;
        }

        .readings {
            font-size: 13px;
            font-weight: 600;

            img {
                margin-right: 2px;
                opacity: 0.7;
            }
        }
    }

    .btn-secondary {
        background-color: #cdcdcd;
        border-color: #cdcdcd;
        color: #ffffff;

        &:hover {
            background-color: #ededed;
            border-color: #ddd;
            color: #333;
        }
    }

</style>
