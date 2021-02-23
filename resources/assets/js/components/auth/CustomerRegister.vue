<template>
    <div>
        <div class="panel-heading">
            <div class="row">
                <div class="col-6 col-md-6 col-lg-6" style="padding-right:0;">
                    <router-link :to="{ name: 'auth.login' }">
                        Login
                    </router-link>
                </div>
                <div class="col-6 col-md-6 col-lg-6" style="padding-left:0;">
                    <router-link :to="{ name: 'auth.customer_register' }">
                        Sign up
                    </router-link>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <ValidationObserver v-slot="{ handleSubmit }">
                        <form style="width: 100%" @submit.prevent="handleSubmit(submitForm)">
                            <bootstrap-alert/>

                            <div class="auth-box">
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

                                <div class="form-group">
                                    <input
                                            type="text"
                                            :value="displayName"
                                            @input="updateDisplayName"
                                            id="display_name"
                                            class="form-control"
                                            placeholder="Screen name"
                                    >
                                </div>

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

                                <div class="form-group">
                                    <vue-button-spinner
                                            class="btn btn-success btn-block"
                                            :isLoading="loading"
                                            :disabled="loading"
                                    >
                                        SUBMIT
                                    </vue-button-spinner>
                                </div>

                                <div class="form-group d-flex justify-content-between">
                                    <div class="checkbox">
                                        <p-check
                                                color="primary"
                                        >
                                            I want to receive special offers
                                        </p-check>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div class="checkbox">
                                        <p-check
                                                color="primary"
                                        >
                                            I agree to the
                                        </p-check>
                                        <router-link :to="{name: 'public.terms_and_conditions'}">Terms & Conditions
                                        </router-link>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </ValidationObserver>
                </div>
            </div>
        </div>
    </div>

</template>


<script>
    import {mapGetters, mapActions} from 'vuex'

    export default {
        data() {
            return {}
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
            // Code...
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
                        sessionStorage.setItem('specialoffer', true);
                    })
            }
        }
    }
</script>


<style lang="scss" scoped>
    .panel-heading {
        a {
            padding: 10px;
            display: block;
            color: #747e80;
            font-size: 18px;
            background-color: #edf1f2;

            &.router-link-active {
                background-color: #fff;
            }
        }
    }

    .checkbox {
        color: #8d9599;
    }
</style>
