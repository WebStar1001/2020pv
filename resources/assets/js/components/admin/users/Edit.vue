<template>
        <section class="content-box">
            <ValidationObserver v-slot="{ handleSubmit }">
                <form @submit.prevent="handleSubmit(submitForm)">
                    <div>
                        <div class="box-header">
                            <h3 class="box-title">Edit Users</h3>
                        </div>

                        <div class="mb-3">
                            <back-buttton></back-buttton>
                        </div>

                        <div v-if="item" class="box">
                            <div class="form-group">
                                <label>Role</label><br>
                                <p-radio @change="updateRole" :value="$roles.SUPERADMIN" name="radio" color="info" v-model="item.role_id">Super Admin</p-radio>
                                <p-radio @change="updateRole" :value="$roles.ADMIN" name="radio" color="info" v-model="item.role_id">Admin</p-radio>
                                <p-radio @change="updateRole" :value="$roles.ADVISOR" name="radio" color="info" v-model="item.role_id">Advisor</p-radio>
                                <p-radio @change="updateRole" :value="$roles.CUSTOMER" name="radio" color="info" v-model="item.role_id">Customer</p-radio>
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

                            <ValidationProvider name="Email" rules="required|email" v-slot="{ errors }">
                                <div class="form-group">
                                    <label for="payment_email">Payment Email</label>
                                    <input
                                            type="email"
                                            :value="item.payment_email"
                                            @input="updatePaymentEmail"
                                            id="payment_email"
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

                            <ValidationProvider name="slug" rules="required" v-slot="{ errors }">
                                <div class="form-group">
                                    <label for="slug">Slug</label>
                                    <input
                                            type="text"
                                            :value="item.slug"
                                            @input="updateSlug"
                                            id="slug"
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

                            <ValidationProvider name="Readings" rules="required" v-slot="{ errors }">
                                <div class="form-group">
                                    <label for="readings">Readings</label>
                                    <input
                                            type="number"
                                            :value="item.readings"
                                            @input="updateReadings"
                                            id="readings"
                                            class="form-control"
                                            :class="{'is-invalid': errors[0] }"
                                    >
                                    <div class="invalid-feedback">{{ errors[0] }}</div>
                                </div>
                            </ValidationProvider>

                            <div class="form-group">
                                <label for="avatar">Photo</label><br>
                                <div v-if="item.avatar">
                                    <img :src="item.avatar" height="100" alt="" class="mr-2">
                                    <button @click="deleteAvatar" type="button" class="btn btn-danger">Delete</button>
                                </div>
                                <input
                                        v-if="!item.avatar"
                                        type="file"
                                        class="form-control"
                                        id="avatar"
                                        name="avatar"
                                        @input="updateAvatar"
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

                            <div v-if="item.role_id === $roles.ADVISOR" class="form-group">
                                <label for="rank">Rank</label>
                                <input
                                        type="number"
                                        class="form-control"
                                        id="rank"
                                        style="max-width: 150px"
                                        name="rank"
                                        :value="item.rank"
                                        @input="updateRank"
                                >
                            </div>

                            <div v-if="item.role_id === $roles.ADVISOR" class="form-group">
                                <p-check
                                        v-model="freeMinutesEnabledComputed"
                                        color="primary"
                                >
                                    Free Minutes Enabled
                                </p-check>
                            </div>

                            <div class="form-group">
                                <p-check
                                        v-model="subscribedComputed"
                                        color="primary"
                                >
                                    Subscribed to Newsletter
                                </p-check>
                            </div>

                            <div class="form-group">
                                <p-check
                                        v-model="blockedComputed"
                                        color="primary"
                                >
                                    Blocked
                                </p-check>
                            </div>

                            <div v-if="item.role_id === $roles.ADVISOR" class="form-group">
                                <p-check
                                        v-model="topAdvisorComputed"
                                        color="primary"
                                >
                                    Top Advisor
                                </p-check>
                            </div>

                            <div class="form-group">
                                <p-check
                                        v-model="deletedComputed"
                                        color="primary"
                                >
                                    Deleted
                                </p-check>
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

        }
    },
    computed: {
        ...mapGetters('UsersSingle', ['item', 'loading']),
        freeMinutesEnabledComputed: {
            get() {
                return this.item.free_minutes_enabled;
            },
            set(val) {
                this.setFreeMinutesEnabled(val ? 1 : 0);
            }
        },
        subscribedComputed: {
            get() {
                return this.item.subscribed;
            },
            set(val) {
                this.setSubscribed(val ? 1 : 0);
            }
        },
        blockedComputed: {
            get() {
                return this.item.blocked;
            },
            set(val) {
                this.setBlocked(val ? 1 : 0);
            }
        },
        topAdvisorComputed: {
            get() {
                return this.item.top_advisor;
            },
            set(val) {
                this.setTopAdvisor(val ? 1 : 0);
            }
        },
        deletedComputed: {
            get() {
                return this.item.deleted;
            },
            set(val) {
                this.setDeleted(val ? 1 : 0);
            }
        },
    },
    created() {
        this.fetchData(this.$route.params.id);
    },
    destroyed() {
        this.resetState()
    },
    watch: {
        "$route.params.id": function() {
            this.resetState();
            this.fetchData(this.$route.params.id)
        }
    },

    methods: {
        ...mapActions('UsersSingle', [
            'fetchData',
            'updateData',
            'resetState',
            'setDisplayName',
            'setSlug',
            'setEmail',
            'setPaymentEmail',
            'setRole',
            'setDescription',
            'setPricePerMinute',
            'setCommissionPercentage',
            'setAvatar',
            'setFreeMinutesEnabled',
            'setSubscribed',
            'setBlocked',
            'setTopAdvisor',
            'setRank',
            'setDeleted',
            'setReadings'
        ]),
        updateDisplayName(e) {
            this.setDisplayName(e.target.value)
        },
        updateSlug(e) {
            this.setSlug(e.target.value)
        },
        updateEmail(e) {
            this.setEmail(e.target.value)
        },
        updatePaymentEmail(e) {
            this.setPaymentEmail(e.target.value)
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
            this.setAvatar(this.$refs.avatar.files[0])
        },
        updateRank(e) {
            this.setRank(e.target.value)
        },
        updateReadings(e) {
            this.setReadings(e.target.value)
        },
        deleteAvatar() {
            this.setAvatar(null)
        },
        submitForm() {
            this.updateData()
                .then(() => {
                    this.$router.push({ name: 'admin.users.index' });
                    this.$eventHub.$emit('update-success')
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
