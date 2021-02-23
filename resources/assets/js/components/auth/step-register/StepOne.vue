<template>
    <div>
        <div v-if="!discount" class="title">Get 3 maaaain free on your first reading</div>
        <div v-if="discount" class="title">Get 3 min free and {{ discount }}% off your first reading</div>

        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-10 col-12 pt-4 pb-1">
                <ValidationObserver v-slot="{ handleSubmit }">
                    <form @submit.prevent="handleSubmit(submitForm)">
                        <bootstrap-alert />

                        <ValidationProvider name="Email" rules="required|email" v-slot="{ errors }">
                            <div class="form-group">
                                <label for="email">Email</label>
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

                        <div class="form-group">
                            <label for="display_name">Choose a screen name</label>
                            <input
                                    type="text"
                                    :value="displayName"
                                    @input="updateDisplayName"
                                    id="display_name"
                                    class="form-control"
                                    placeholder="Screen Name"
                            >
                        </div>

                        <ValidationProvider name="Password" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                                <label for="password">Choose a password</label>
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

                        <div class="form-group">
                            <p-check
                                    @change="updateSubscribed"
                                    :checked="subscribed"
                                    color="primary"
                            >
                                I want to receive special offers
                            </p-check>
                        </div>

                        <ValidationProvider name="Term and Conditions" :rules="{ required: { allowFalse: false } }" v-slot="{ errors }">
                            <div class="form-group">
                                <p-check
                                        v-model="termsAndConditionsComputed"
                                        color="primary"
                                >
                                    I accept the <a href="/terms-and-conditions" target="_blank" style="position: relative; z-index: 2">terms and conditions</a> of the site.
                                </p-check>
                                <div class="text-danger" style="font-size: 80%">{{ errors[0] }}</div>
                            </div>
                        </ValidationProvider>

                        <ValidationProvider name="I am 18 years old or above" :rules="{ required: { allowFalse: false } }" v-slot="{ errors }">
                            <div class="form-group">
                                <p-check
                                        v-model="adultComputed"
                                        color="primary"
                                >
                                    I am 18 years old or above.
                                </p-check>
                                <div class="text-danger" style="font-size: 80%">{{ errors[0] }}</div>
                            </div>
                        </ValidationProvider>

                        <div class="text-right">
                            <button
                                    class="btn bg-chat-s btn-cs-lg"
                                    :isLoading="loading"
                                    :disabled="loading"
                            >
                                NEXT
                            </button>
                        </div>

                        <div class="ml-auto mr-auto text-center mt-4 mb-2">
                            <span class="t">
                                Already have an account?
                                <router-link :to="{ name: 'auth.login' }">
                                    Sign In
                                </router-link>
                            </span>
                        </div>
                    </form>
                </ValidationObserver>
            </div>
        </div>
    </div>

</template>


<script>
    import { mapGetters, mapActions } from 'vuex'
    import MyMixins from '../../../mixins'

    export default {
        mixins: [MyMixins],
        data() {
            return {
                discount: 0
            }
        },
        computed: {
            ...mapGetters('StepRegister', [
                'loading',
                'email',
                'displayName',
                'password',
                'subscribed',
                'termsAndConditions',
                'adult',
            ]),
            termsAndConditionsComputed: {
                get() {
                    return this.termsAndConditions;
                },
                set(val) {
                    this.setTermsAndConditions(val)
                }
            },
            adultComputed: {
                get() {
                    return this.adult;
                },
                set(val) {
                    this.setAdult(val)
                }
            },
        },
        created() {
            // if (this.$gate || sessionStorage.getItem('specialoffer')) {
            if (!this.isUsedDiscount()) {
                this.discount = this.$store.getters.discount;
            }
            // }
        },
        destroyed() {
            this.resetState();
        },
        methods: {
            ...mapActions('StepRegister', [
                'register',
                'resetState',
                'setEmail',
                'setDisplayName',
                'setPassword',
                'setSubscribed',
                'setTermsAndConditions',
                'setAdult'
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
            updateSubscribed(e) {
                this.setSubscribed(e)
            },
            submitForm() {
                this.register(3)
                    .then(() => {
                        this.$router.push({ name: 'step_register.two', params: {slug: this.$route.params.slug} })
                    })
            },
        }
    }
</script>


<style lang="scss" scoped>
    .title {
        font-size: 1.61rem;
        text-align: center;
        font-weight: 300;
        margin-bottom: .5rem;
    }

    .form-control {
        height: 47px;
        font-size: 14px;
        color: #868585;
    }

    .bg-chat-s {
        background-color: #45cfe4;
        border-color: #45cfe4;
        color: white;
    }

    .btn-cs-lg {
        padding: 10px 55px;
        font-size: 14px;
        font-weight: 300;
    }

    a {
        color: #93e406;
    }
</style>
