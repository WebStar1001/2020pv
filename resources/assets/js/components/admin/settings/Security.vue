<template>
    <div>
        <div class="setting-title">Change Password</div>

        <ValidationObserver ref="observer" v-slot="{ handleSubmit }">
            <form @submit.prevent="handleSubmit(submitForm)">

                <div class="row">
                    <div class="col-md-6">
                        <ValidationProvider name="Current Password" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <input
                                        type="password"
                                        :value="currentPassword"
                                        @input="updateCurrentPassword"
                                        id="current_password"
                                        class="form-control"
                                        :class="{'is-invalid': errors[0] }"
                                >
                                <div class="invalid-feedback">{{ errors[0] }}</div>
                            </div>
                        </ValidationProvider>
                    </div>
                    <div class="col-md-6">
                        <ValidationProvider name="New Password" rules="required|min:6" v-slot="{ errors }">
                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input
                                        type="password"
                                        :value="newPassword"
                                        @input="updateNewPassword"
                                        id="new_password"
                                        class="form-control"
                                        :class="{'is-invalid': errors[0] }"
                                >
                                <div class="invalid-feedback">{{ errors[0] }}</div>
                            </div>
                        </ValidationProvider>
                    </div>
                </div>

                <div class="text-right">
                    <div class="form-group">
                        <vue-button-spinner
                                class="btn btn-success settings-submit-btn"
                                :isLoading="loading"
                                :disabled="loading"
                        >
                            UPDATE
                        </vue-button-spinner>
                    </div>
                </div>

            </form>
        </ValidationObserver>

        <div class="setting-title">Delete Account</div>
        <button @click="$modal.show('delete-account-modal')" type="button" class="btn btn-danger mb-5">Delete Account</button>

        <modal name="delete-account-modal" :scrollable="true" :adaptive="true" width="500" height="auto">
            <div class="modal-header">
                <div class="title">
                    Delete Account
                </div>
                <div>
                    <button @click="$modal.hide('delete-account-modal')" class="btn btn-link btn-close">
                        <i class="icomoon icomoon-cross"></i>
                    </button>
                </div>
            </div>
            <ValidationObserver ref="deleteAccountObserver" v-slot="{ handleSubmit }">
                <form @submit.prevent="handleSubmit(submitDeleteAccountForm)">
                    <div class="modal-body">
                        <div class="form-group text-secondary" style="font-weight: 500">
                            Are you sure you want to delete this account? This cannot be undone.
                        </div>


                        <ValidationProvider name="Password" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input
                                        type="password"
                                        :value="deletePassword"
                                        @input="updateDeletePassword"
                                        placeholder="Enter Your Password"
                                        id="password"
                                        class="form-control"
                                        :class="{'is-invalid': errors[0] }"
                                >
                                <div class="invalid-feedback">{{ errors[0] }}</div>
                            </div>
                        </ValidationProvider>
                    </div>
                    <div class="modal-footer">
                        <vue-button-spinner
                                class="btn btn-danger"
                                :isLoading="loading"
                                :disabled="loading"
                        >
                            Delete Account
                        </vue-button-spinner>

                        <button @click="$modal.hide('delete-account-modal')" type="button" class="btn btn-secondary">
                            Cancel
                        </button>
                    </div>
                </form>
            </ValidationObserver>
        </modal>

    </div>
</template>


<script>
    import { mapGetters, mapActions } from 'vuex';

    export default {
        data() {
            return {

            }
        },
        created() {

        },
        destroyed() {
            this.resetState()
        },
        computed: {
            ...mapGetters('SettingsSecurity', [
                'loading',
                'currentPassword',
                'newPassword',
                'deletePassword'
            ]),
        },
        methods: {
            ...mapActions('SettingsSecurity', [
                'resetState',
                'setCurrentPassword',
                'setNewPassword',
                'setDeletePassword',
                'updateSecurity',
                'deleteAccount'
            ]),
            updateCurrentPassword(e) {
                this.setCurrentPassword(e.target.value)
            },
            updateNewPassword(e) {
                this.setNewPassword(e.target.value)
            },
            updateDeletePassword(e) {
                this.setDeletePassword(e.target.value)
            },
            submitForm() {
                this.updateSecurity()
                    .then(resp => {
                        // reset form errors
                        this.$nextTick(() => this.$refs.observer.reset());

                        if (resp.data.error) {
                            this.$eventHub.$emit('incorrect-current-password')
                        } else {
                            this.$eventHub.$emit('update-success')
                        }
                    })
            },
            submitDeleteAccountForm() {
                this.deleteAccount()
                    .then(resp => {
                        // reset form errors
                        this.$nextTick(() => this.$refs.deleteAccountObserver.reset());

                        if (resp.data.error) {
                            this.$eventHub.$emit('incorrect-current-password')
                        } else {
                            this.$eventHub.$emit('account-deleted');

                            axios.post('/logout').then(resp => {
                                // Vue.prototype.$gate = null;
                                // this.$router.push({ name: 'public.home' });

                                window.location.href = '/';
                            });
                        }
                    })
            }
        }
    }
</script>


<style scoped>

</style>
