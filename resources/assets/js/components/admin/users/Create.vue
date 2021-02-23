<template>
        <section class="content-box">
            <ValidationObserver v-slot="{ handleSubmit }">
                <form @submit.prevent="handleSubmit(submitForm)">
                    <div>
                        <div class="box-header">
                            <h3 class="box-title">Create Users</h3>
                        </div>

                        <div class="mb-3">
                            <back-buttton></back-buttton>
                        </div>

                        <div class="box mb-3">
                            <div class="form-group">
                                <label>Role</label><br>
                                <p-radio @change="updateRole" :value="$roles.SUPERADMIN" name="radio" color="info">Super Admin</p-radio>
                                <p-radio @change="updateRole" :value="$roles.ADMIN" name="radio" color="info">Admin</p-radio>
                                <p-radio @change="updateRole" :value="$roles.ADVISOR" name="radio" color="info">Advisor</p-radio>
                                <p-radio @change="updateRole" :value="$roles.CUSTOMER" name="radio" color="info">Customer</p-radio>
                            </div>

                            <ValidationProvider name="Email" rules="required|email" v-slot="{ errors }">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input
                                            type="email"
                                            :value="item.email"
                                            @input="updateEmail"
                                            id="email"
                                            class="form-control"
                                            :class="{'is-invalid': errors[0] }"
                                    >
                                    <div class="invalid-feedback">{{ errors[0] }}</div>
                                </div>
                            </ValidationProvider>

                            <ValidationProvider name="Screen Name" rules="required" v-slot="{ errors }">
                                <div class="form-group">
                                    <label for="display_name">Screen Name</label>
                                    <input
                                            type="text"
                                            :value="item.display_name"
                                            @input="updateDisplayName"
                                            id="display_name"
                                            class="form-control"
                                            :class="{'is-invalid': errors[0] }"
                                    >
                                    <div class="invalid-feedback">{{ errors[0] }}</div>
                                </div>
                            </ValidationProvider>

                            <div class="form-group">
                                <label for="description">Short Description</label>
                                <textarea
                                        class="form-control"
                                        id="description"
                                        name="description"
                                        placeholder="Short Description"
                                        :value="item.description"
                                        @input="updateDescription"
                                ></textarea>
                            </div>

                            <div class="form-group">
                                <label for="avatar">Photo</label>
                                <input
                                        type="file"
                                        class="form-control"
                                        id="avatar"
                                        name="avatar"
                                        @change="updateAvatar"
                                        ref="avatar"
                                >
                            </div>

                            <div v-if="item.role_id === $roles.ADVISOR">
                                <ValidationProvider name="Price Per Minute" rules="required" v-slot="{ errors }">
                                    <div class="form-group">
                                        <label for="price_per_minute">Price Per Minute</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input
                                                    type="text"
                                                    class="form-control"
                                                    id="price_per_minute"
                                                    style="max-width: 150px"
                                                    name="price_per_minute"
                                                    :value="item.price_per_minute"
                                                    @input="updatePricePerMinute"
                                                    :class="{'is-invalid': errors[0] }"
                                            >
                                            <div class="invalid-feedback">{{ errors[0] }}</div>
                                        </div>
                                    </div>
                                </ValidationProvider>

                                <ValidationProvider name="Commission Percentage" rules="required" v-slot="{ errors }">
                                    <div class="form-group">
                                        <label for="commission_percentage">Commission Percentage</label>
                                        <div class="input-group">
                                            <input
                                                    type="number"
                                                    class="form-control"
                                                    id="commission_percentage"
                                                    style="max-width: 150px"
                                                    name="commission_percentage"
                                                    :value="item.commission_percentage"
                                                    @input="updateCommissionPercentage"
                                                    :class="{'is-invalid': errors[0] }"
                                            >
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                            <div class="invalid-feedback">{{ errors[0] }}</div>
                                        </div>
                                    </div>
                                </ValidationProvider>
                            </div>

                            <vue-button-spinner
                                    class="btn btn-success"
                                    :isLoading="loading"
                                    :disabled="loading"
                            >
                                Save
                            </vue-button-spinner>
                        </div>
                    </div>
                </form>
            </ValidationObserver>
        </section>
</template>


<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    data() {
        return {
            user: []
        }
    },
    computed: {
        ...mapGetters('UsersSingle', ['item', 'loading'])
    },
    created() {

    },
    destroyed() {
        this.resetState()
    },
    methods: {
        ...mapActions('UsersSingle', [
            'storeData',
            'resetState',
            'setDisplayName',
            'setEmail',
            'setRole',
            'setPassword',
            'setDescription',
            'setPricePerMinute',
            'setCommissionPercentage',
            'setAvatar'
        ]),
        updateDisplayName(e) {
            this.setDisplayName(e.target.value)
        },
        updateEmail(e) {
            this.setEmail(e.target.value)
        },
        updatePassword(e) {
            this.setPassword(e.target.value)
        },
        updateRole(value) {
            this.setRole(value)
        },
        updateDescription(e) {
            this.setDescription(e.target.value)
        },
        updatePricePerMinute(e) {
            this.setPricePerMinute(e.target.value)
        },
        updateCommissionPercentage(e) {
            this.setCommissionPercentage(e.target.value)
        },
        updateAvatar(e) {
            this.setAvatar(this.$refs.avatar.files[0]);
        },
        submitForm() {
            this.storeData()
                .then(() => {
                    this.$eventHub.$emit('create-success');
                    this.$router.push({ name: 'admin.users.index' });
                })
                .catch((error) => {
                    console.error(error)
                })
        }
    }
}
</script>


<style scoped>

</style>
