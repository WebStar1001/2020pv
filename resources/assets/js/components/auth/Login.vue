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
                        <form @submit.prevent="handleSubmit(submitForm)">
                            <bootstrap-alert />
                            <div v-if="error" v-html="error" class="alert alert-danger"></div>

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

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group d-flex justify-content-between">
                                            <div class="remember">
                                                <p-check
                                                        @change="updateRemember"
                                                        :checked="remember"
                                                        color="primary"
                                                >
                                                    Remember me
                                                </p-check>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 text-right">
                                        <div class="forgot-pass-link">
                                            <router-link :to="{ name: 'auth.reset_password' }">
                                                Forgot Password
                                            </router-link>
                                        </div>
                                    </div>
                                </div>

                                <button
                                        class="btn btn-success btn-block"
                                        :isLoading="loading"
                                        :disabled="loading"
                                        type="submit"
                                >
                                    LOGIN
                                </button>
                            </div>
                        </form>

                    </ValidationObserver>
                </div>
            </div>
        </div>
    </div>

</template>


<script>
    import { mapGetters, mapActions } from 'vuex'
    import Gate from '../../gate/Gate';

    export default {
        props: ['error'],
        data() {
            return {

            }
        },
        computed: {
            ...mapGetters('Login', ['email', 'password', 'remember', 'loading'])
        },
        created() {

            // Code...
        },
        destroyed() {
            this.resetState();
        },
        methods: {
            ...mapActions('Login', [
                'login',
                'resetState',
                'setEmail',
                'setPassword',
                'setRemember'
            ]),
            updateEmail(e) {
                this.setEmail(e.target.value)
            },
            updatePassword(e) {
                this.setPassword(e.target.value)
            },
            updateRemember(e) {
                this.setRemember(e)
            },
            submitForm() {
                this.login()
                    .then(resp => {
                        // Vue.prototype.$gate = new Gate(resp.user);
                        // Vue.prototype.$roles = resp.roles;
                        //
                        // document.querySelector('meta[name=csrf-token]').setAttribute('content', resp.csrf_token);
                        //
                        // window.axios.defaults.headers.common['X-CSRF-TOKEN'] = resp.csrf_token;
                        //
                        // this.$router.push({ name: 'admin.dashboard.index' });
                        sessionStorage.setItem('specialoffer', true);
                        window.location.href = '/admin/dashboard';
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
    .forgot-pass-link,
    .remember {
        font-size: 12px;
    }
</style>
