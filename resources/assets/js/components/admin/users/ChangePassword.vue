<template>
        <section class="content-box">
            <div>
                <div class="mb-3">
                    <back-buttton></back-buttton>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="box">
                            <div class="box-header box-success">
                                <h3 class="box-title">Change Password</h3>
                            </div>

                            <form style="width: 100%" @submit.prevent="submitForm">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label> <b>{{data.email}}</b></label>

                                    </div>
                                    <div class="form-group">
                                        <label for="new_password">New password</label>
                                        <input
                                                type="password"
                                                class="form-control"
                                                name="new_password"
                                                @input="updateNewPassword"

                                        >
                                    </div>
                                    <div class="form-group">
                                        <label for="new_password_confirmation">Confirm new password</label>
                                        <input
                                                type="password"
                                                class="form-control"
                                                name="new_password_confirmation"
                                                @input="updateNewPasswordConfirmation"
                                        >
                                    </div>
                                </div>

                                <div class="box-footer">
                                    <vue-button-spinner
                                            class="btn btn-success"
                                            :isLoading="loading"
                                            :disabled="loading"
                                    >
                                        Save
                                    </vue-button-spinner>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="box">
                            <div class="box-header box-success">
                                <h3 class="box-title">Reset Password</h3>
                            </div>

                            <div class="box-body">
                                <button @click="sendResetLink()" type="button" class="btn btn-success">Send reset password link</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
</template>


<script>
    import { mapGetters, mapActions } from 'vuex';

    export default {
        data() {
            return {
                data: {}
            }
        },
        computed: {
            ...mapGetters('ChangePassword', ['new_password', 'new_password_confirmation', 'loading'])
        },
        created() {
            this.getUserSettings(this.$route.params.id)
        },
        destroyed() {
        },
        methods: {
            ...mapActions('ChangePassword', [
                'updatePassword',
                'sendResetPasswordLink',
                'resetState',
                'setNewPassword',
                'setNewPasswordConfirmation'
            ]),
            getUserSettings(id) {
                axios.get('/users/' + id)
                    .then(response => {
                        this.data = response.data.data;
                    })
            },
            updateNewPassword(e) {
                this.setNewPassword(e.target.value)
            },
            updateNewPasswordConfirmation(e) {
                this.setNewPasswordConfirmation(e.target.value)
            },
            submitForm() {
                this.updatePassword(this.$route.params.id)
                    .then(() => {
                        this.$router.push({ name: 'admin.users.index' });
                        this.$eventHub.$emit('update-success')
                    }).catch((error) => {
                        console.error(error)
                    })
            },
            sendResetLink() {
                this.sendResetPasswordLink(this.$route.params.id).then(() => {
                    this.$router.push({ name: 'admin.users.index' });
                    this.$eventHub.$emit('password-link-sent')
                }).catch((error) => {
                        console.error(error)
                    });
            }
        }
    }
</script>


<style scoped>

</style>
