<template>
    <div>
        <section class="fist-step chat py-2">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 top-col">
                        <router-link :to="{name: 'home.home'}" class="btn bg-inherit small-btn">
                            <img src="/images/pages/arrow-left.png" alt="icon-time"> Go back to home
                        </router-link>
                    </div>
                </div><!--row-->
                <div class="row">
                    <div class="col-12 text-center pb-3 mobile-padd">
                        <img src="/images/pages/step-register-flower.png" class="img-fluid" />
                    </div>
                </div>
            </div>
            <div class="stepwizard">
                <ul class="nav nav-tabs stepwizard-row setup-panel">
                    <li class="stepwizard-step">
                        <div class="btn btn-custom btn-lg btn-circle">1</div>
                        <p class="color-custom mobile-p">Select your psychic</p>
                    </li>
                    <li class="stepwizard-step">
                        <div class="btn btn-default btn-lg btn-circle btn-second">2</div>
                        <p class="mobile-p second-p">Create a free account</p>
                    </li>
                    <li class="stepwizard-step">
                        <div class="btn btn-default btn-circle btn-third">3</div>
                        <p class="mobile-p third-p">Fund your account</p>
                    </li>
                    <li class="stepwizard-step">
                        <div class="btn btn-default btn-circle btn-fourth">4</div>
                        <p class="mobile-p fourth-p">Done! go to chat</p>
                    </li>
                </ul>
            </div><!--stepwizard-->
            <br>
            <div class="container-fluid desktop-padding">
                <div class="row">
                    <div class="col-12 tab-content">
                        <div class="tab-pane active">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-12 desktop-right">
                                    <div class="side-chat bg-white p-3 pb-2 pt-4 h-100">
                                        <h3 class="text-center">You are contacting</h3>
                                        <div v-if="advisor" class="side-img text-center l-auto mr-auto">
                                            <div class="avatar" :style="'background-image: url(' + advisor.avatar + ')'">
                                                <div v-if="advisor.status === 'online'" class="online-sign green">
                                                    <span></span>
                                                </div>
                                                <div v-if="advisor.status === 'offline'" class="online-sign gray">
                                                    <span></span>
                                                </div>
                                                <div v-if="advisor.status === 'busy'" class="online-sign orange">
                                                    <span></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-if="advisor" class="name-profile ml-auto mr-auto text-center py-4">
                                            <a><h3>{{ advisor.display_name }}</h3></a>
                                            <span>{{ advisor.master_service.title }}</span>
                                            <br><br>
                                            <small>Fee/minute</small><br>
                                            <span class="large-span chat-s-color" style="font-size:20px;" v-if="discount">
                                                <s>${{ toFixed(advisor.price_per_minute / 100,
                                            2)}}</s>
                                            </span>
                                            <span class="large-span chat-s-color">
                                                ${{ toFixed(getPricePerMinuteWithDiscount() / 100, 2) }}
                                            </span>
                                        </div>
                                        <div class="last-txt text-center ml-auto mr-auto">
                                            <br>
                                            <span>This psychic is waiting for you</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8 col-md-8 col-12 bg-white p-3 pt-4 mobile-padding">
                                    <event-hub></event-hub>

                                    <router-view v-bind="myProps"></router-view>
                                </div>
                            </div><!--row-->
                        </div><!--tab pane-->
                    </div>

                </div><!--row-->
                <div class="row">
                    <div class="col-12 py-2">
                        <span class="small">&nbsp; &nbsp; Disclaimer: Psychics are not emploees of GoPsys.</span>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    import MyMixins from '../../mixins';
    import { mapGetters, mapActions } from 'vuex'

    export default {
        props: ['error'],
        mixins: [MyMixins],
        data() {
            return {
                discount: 0,
            }
        },
        created() {
            this.getAdvisor(this.$route.params.slug);

            // if (this.$gate || sessionStorage.getItem('specialoffer')) {
            // if (!this.isUsedDiscount()) {
                this.discount = this.$store.getters.discount;
            // }
            // }
        },
        computed: {
            ...mapGetters('StepRegister', [
                'advisor'
            ]),
            myProps() {
                return { error: this.error }
            }
        },
        methods: {
            ...mapActions('StepRegister', [
                'getAdvisor'
            ]),
            getPricePerMinuteWithDiscount() {
                if (this.discount) {
                    return this.advisor.price_per_minute - (this.advisor.price_per_minute / 100) * this.discount;
                }

                return this.advisor.price_per_minute;
            },
        }
    }
</script>

<style lang="scss" scoped>
    .top-col {
        margin-bottom: -30px;
        a {
            position: relative;
            z-index: 1;
        }
    }

    .small-btn {
        font-size: 0.75rem;
    }

    .stepwizard {
        display: table;
        width: 80%;
        position: relative;
        margin-left: auto;
        margin-right: auto;
        @media (max-width: 768.98px) {
            width: 100%;
        }
        .stepwizard-row {
            display: table-row;
            &:before {
                top: 25px;
                bottom: 0;
                left: 11%;
                position: absolute;
                content: " ";
                width: 78%;
                height: 1px;
                background-color: #ccc;
                @media (max-width: 768.98px) {
                    top: 18px;
                }
            }
        }
        .stepwizard-step {
            display: table-cell;
            text-align: center;
            position: relative;
            @media (max-width: 768.98px) {
                div {
                    font-size: 16px !important;
                }
                p {
                    margin-left: 38% !important;
                }
            }
            div, p {
                font-size: 23px;
                cursor: default;
            }
            p {
                margin-top: 10px;
                margin-left: 41%;
                font-size: 17px;
                text-align: left;
            }
            .color-custom {
                color: #93e406;
            }
        }
        .btn-circle {
            width: 50px;
            height: 50px;
            text-align: center;
            padding: 6px 0;
            font-size: 12px;
            line-height: 1.428571429;
            border-radius: 50%;
            @media (max-width: 768.98px) {
                width: 35px;
                height: 35px;
            }
        }
        .btn-custom {
            background: #85cd03;
            border-color: #85cd03;
            color: white;
        }
        .btn-default {
            background: white;
            color: gray;
        }
    }

    @media (max-width: 768.98px) {
        .mobile-padd {
            padding-top: 20px;
        }
        .mobile-p {
            font-size: 11px !important;
        }
    }

    @media (min-width: 768px) {
        .desktop-right {
            padding-right: .25rem;
        }
    }

    @media (max-width: 768.98px) {
        .desktop-right {
            background: white;
            border-bottom: 1px solid #e2e2e2;
        }
    }

    .fist-step h3 {
        font-size: 1.61rem;
        font-weight: 300;
    }

    .side-img {
        position: relative;
        border-radius: 50%;
        @media (min-width: 768.98px) {
            padding-top: 3rem;
        }
    }

    .avatar {
        position: relative;
        display: table;
        margin: 0 auto;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background-size: cover;
        background-position: center center;
        .online-sign {
            position: absolute;
            right: 0;
            bottom: 0;
            height: 30px;
            width: 30px;
            border: 2px solid white;
            background: #85cd03;
            border-radius: 50%;
            &.green {
                background: #86cd03;
            }
            &.gray {
                background: #5e6a6e;
            }
            &.orange {
                background: #f5a623;
            }
            span {
                width: 14px;
                height: 14px;
                background: white;
                margin-left: auto;
                margin-right: auto;
                border-radius: 50%;
                position: absolute;
                top: 6px;
                right: 0;
                left: 0;
            }
        }
    }

    .large-span {
        font-size: 2.8rem;
        font-weight: 300;
    }

    .chat-s-color {
        color: #45cfe4;
    }

    @media (min-width: 768px) {
        .desktop-padding {
            padding-left: 3rem;
            padding-right: 3rem;
        }
    }
</style>