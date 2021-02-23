<template>
    <div>
        <div class="auth-box">
            <ValidationObserver ref="observer" v-slot="{ handleSubmit }">
                <h1 class="auth-title">Set Password</h1>
                <form @submit.prevent="handleSubmit(submitForm)">
                    <bootstrap-alert />

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

                    <ValidationProvider name="Password" rules="required|min:6" vid="confirmation" v-slot="{ errors }">
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

                    <ValidationProvider name="Confirm Password" rules="required|min:6|confirmed:confirmation" v-slot="{ errors }">
                        <div class="form-group">
                            <input
                                    type="password"
                                    :value="confirmPassword"
                                    @input="updateConfirmPassword"
                                    id="password_confirmation"
                                    class="form-control"
                                    :class="{'is-invalid': errors[0] }"
                                    placeholder="Confirm Password"
                            >
                            <div class="invalid-feedback">{{ errors[0] }}</div>
                        </div>
                    </ValidationProvider>

                    <vue-button-spinner
                            class="btn btn-success btn-block"
                            :isLoading="loading"
                            :disabled="loading"
                    >
                        Submit
                    </vue-button-spinner>
                </form>
            </ValidationObserver>
        </div>
    </div>
</template>


<script>
    import { mapGetters, mapActions } from 'vuex'

    export default {
        data() {
            return {

            }
        },
        computed: {
            ...mapGetters('SetPassword', ['email', 'password', 'confirmPassword', 'loading'])
        },
        created() {
            // Code...
        },
        destroyed() {
            this.resetState();
        },
        methods: {
            ...mapActions('SetPassword', [
                'resetPassword',
                'resetState',
                'setEmail',
                'setPassword',
                'setConfirmPassword'
            ]),
            updateEmail(e) {
                this.setEmail(e.target.value)
            },
            updatePassword(e) {
                this.setPassword(e.target.value)
            },
            updateConfirmPassword(e) {
                this.setConfirmPassword(e.target.value)
            },
            submitForm() {
                this.resetPassword(this.$route.params.token)
                    .then(response => {
                        // reset form errors
                        this.$nextTick(() => this.$refs.observer.reset());

                        if (response.data === 'passwords.reset') {
                            this.$eventHub.$emit('password-updated');
                            window.location.href = '/admin/dashboard'
                        }
                    });
            }
        }
    }
</script>


<style scoped>

</style>
