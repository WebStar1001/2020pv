<template>
    <section class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-lg-7 text-left Accurate">
                    <h1>Accurate guidance...</h1>
                    <h2>FIND YOUR <br>WAY TO LOVE <br>AND HAPPYNESS!</h2>
                    <!--<a @click.prevent="$modal.show('video-modal')" href="#">-->
                        <!--<img src="/images/homepage/youtube.png" alt="youtube"> <span>Watch how it works!</span>-->
                    <!--</a>-->

                    <modal name="video-modal" :scrollable="true" :adaptive="true" width="800" height="auto">
                        <div class="modal-body mb-0 p-0">
                            <div class="embed-responsive embed-responsive-16by9 z-depth-1-half">
                                <iframe allowfullscreen class="embed-responsive-item" src="https://www.youtube.com/embed/vr0qNXmkUJ8"></iframe>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button @click="$modal.hide('video-modal')" class="btn btn-outline-primary btn-rounded btn-md ml-4 text-center" type="button">Close</button>
                        </div>
                    </modal>
                </div>
                <div class="col-md-5 col-lg-5" v-if="!this.$gate">
                    <div class="login-form">
                        <div class="main-div">
                            <div class="panel2">
                                <h2>Get 3 min Free</h2>
                                <p v-if="discount">
                                    and {{ discount }}% off if you register today
                                </p>
                            </div>

                            <ValidationObserver v-slot="{ handleSubmit }">
                                <form style="width: 100%" @submit.prevent="handleSubmit(submitForm)">
                                    <bootstrap-alert />

                                    <div class="form-group">
                                        <input
                                                type="text"
                                                :value="displayName"
                                                @input="updateDisplayName"
                                                id="display_name"
                                                class="form-control"
                                                placeholder="Name"
                                        >
                                    </div>

                                    <ValidationProvider name="Email" rules="required|email" v-slot="{ errors }">
                                        <div class="form-group">
                                            <input
                                                    type="email"
                                                    :value="email"
                                                    @input="updateEmail"
                                                    id="email"
                                                    class="form-control"
                                                    :class="{'is-invalid': errors[0] }"
                                                    placeholder="Email"
                                            >
                                            <div class="invalid-feedback">{{ errors[0] }}</div>
                                        </div>
                                    </ValidationProvider>

                                    <ValidationProvider name="Password" rules="required" v-slot="{ errors }">
                                        <div class="form-group">
                                            <input
                                                    type="password"
                                                    :value="password"
                                                    @input="updatePassword"
                                                    id="password"
                                                    class="form-control"
                                                    :class="{'is-invalid': errors[0] }"
                                                    placeholder="Password"
                                            >
                                            <div class="invalid-feedback">{{ errors[0] }}</div>
                                        </div>
                                    </ValidationProvider>

                                    <vue-button-spinner
                                            class="btn btn-success btn-block text-uppercase"
                                            :isLoading="loading"
                                            :disabled="loading"
                                    >
                                        Get a reading Now
                                    </vue-button-spinner>
                                    <div class="agreeing">
                                        <p>
                                            You are agreeing to our
                                            <router-link :to="{name: 'public.terms_and_conditions'}">
                                                Term and Services
                                            </router-link>
                                        </p>
                                    </div>
                                </form>
                            </ValidationObserver>
                        </div>
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
                discount: 0
            }
        },
        computed: {
            ...mapGetters('CustomerRegister', [
                'loading',
                'email',
                'displayName',
                'password'
            ])
        },
        created() {
            // if (this.$gate || sessionStorage.getItem('specialoffer')) {
            // if (!this.isUsedDiscount()) {
                this.discount = this.$store.getters.discount;
            // }
            // }
        },
        destroyed() {
            this.resetState();
        },
        methods: {
            ...mapActions('CustomerRegister', [
                'register',
                'resetState',
                'setEmail',
                'setDisplayName',
                'setPassword'
            ]),
            updateEmail(e) {
                this.setEmail(e.target.value)
            },
            updateDisplayName(e) {
                this.setDisplayName(e.target.value)
            },
            updatePassword(e) {
                this.setPassword(e.target.value)
            },
            submitForm() {
                this.register(3)
                    .then(() => {
                        window.location.href = '/admin/dashboard'
                    })
            }
        }
    }
</script>

<style lang="scss" scoped>
    .banner {
        background-image: url(/images/homepage/bg.jpg);
        background-color: #10bfea;
        text-align: center;
        color: #fff;
        padding: 100px 0;
        background-repeat: no-repeat;
        background-attachment: scroll;
        background-position: top center;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        @media (max-width: 767.98px) {
            padding: 90px 0 50px;
        }
    }

    .Accurate {
        @media (max-width: 767.98px) {
            margin-bottom: 30px;
        }
        h1 {
            font-size: 24px;
            color: #fff;
            margin-top: 30px;
            font-weight: 400;
        }
        h2 {
            font-size: 58px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 30px;
            @media (max-width: 991.98px) and (min-width: 768px) {
                font-size: 36px;
            }
            @media (max-width: 767.98px) {
                font-size: 24px;
            }
        }
        a {
            font-size: 15px;
            color: #fff;
            font-weight: 300;
            text-decoration: none;
            outline: none;
        }
        img {
            margin-right: 15px;
        }
        span {
            border-bottom: 1px solid;
        }
    }

    .login-form {
        background-color: #fff;
        padding: 20px 20px 1px;
        margin-right: 110px;
        box-shadow: 0 3px 5px rgba(0,0,0,.10);
        @media (max-width: 991.98px) {
            margin-right: 0;
        }
        .panel2 {
            h2 {
                color: #404f54;
                font-size: 32px;
                font-weight: 400;
            }
            p {
                color: #404f54;
                font-size: 16px;
                font-weight: 500;
                @media (max-width: 991.98px) and (min-width: 768px) {
                    font-size: 14px;
                }
            }
        }
        .form-control {
            padding: 20px 14px;
            background-color: #f7f7f7;
        }
        .agreeing p {
            color: #939393;
            font-size: 12px;
            margin-top: 30px;
            @media (max-width: 991.98px) and (min-width: 768px) {
                font-size: 10px;
            }
        }
    }
</style>
