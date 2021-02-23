<template>
    <div>
        <div class="auth-box">
            <ValidationObserver ref="observer" v-slot="{ handleSubmit }">
                <h3 class="auth-title">Forgot Password</h3>

                <form style="width: 100%" @submit.prevent="handleSubmit(submitForm)">
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

                    <button
                            class="btn btn-success btn-block"
                            :isLoading="loading"
                            :disabled="loading"
                            type="submit"
                    >
                        Reset Password
                    </button>
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
            ...mapGetters('ResetPassword', ['email', 'loading'])
        },
        created() {
            // Code...
        },
        destroyed() {
            this.resetState();
        },
        methods: {
            ...mapActions('ResetPassword', [
                'resetPassword',
                'resetState',
                'setEmail'
            ]),
            updateEmail(e) {
                this.setEmail(e.target.value)
            },
            submitForm() {
                this.resetPassword()
                    .then(() => {
                        // reset form errors
                        this.$nextTick(() => this.$refs.observer.reset());
                    })
            }
        }
    }
</script>


<style scoped>

</style>
