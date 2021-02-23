<template>
    <section class="content-box">
        <ValidationObserver v-slot="{ handleSubmit }">
            <form @submit.prevent="handleSubmit(submitForm)">
                <div>
                    <div class="box-header">
                        <h3 class="box-title">Edit Discount</h3>
                    </div>

                    <div class="mb-3">
                        <back-buttton></back-buttton>
                    </div>

                    <div class="box">

                        <ValidationProvider name="Discount" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                                <label for="discount">Discount</label>
                                <div class="input-group">
                                    <input
                                            type="number"
                                            class="form-control"
                                            id="discount"
                                            style="max-width: 150px"
                                            name="discount"
                                            :value="item.discount"
                                            @input="updateDiscount"
                                            :class="{'is-invalid': errors[0] }"
                                    >
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <div class="invalid-feedback">{{ errors[0] }}</div>
                                </div>
                            </div>
                        </ValidationProvider>

                        <div class="form-group">
                            <p-check
                                    v-model="forNewUsersComputed"
                                    color="primary"
                            >
                                For new users
                            </p-check>
                        </div>

                        <div class="form-group">
                            <p-check
                                    v-model="activeComputed"
                                    color="primary"
                            >
                                Active
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
        ...mapGetters('DiscountsSingle', ['item', 'loading']),
        activeComputed: {
            get() {
                return this.item.active;
            },
            set(val) {
                this.setActive(val ? 1 : 0);
            }
        },
        forNewUsersComputed: {
            get() {
                return this.item.for_new_users;
            },
            set(val) {
                this.setForNewUsers(val ? 1 : 0);
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
        ...mapActions('DiscountsSingle', [
            'fetchData',
            'updateData',
            'resetState',
            'setDiscount',
            'setActive',
            'setForNewUsers'
        ]),
        updateDiscount(e) {
            this.setDiscount(e.target.value)
        },
        submitForm() {
            this.updateData(this.$route.params.id)
                .then(() => {
                    this.$eventHub.$emit('create-success');
                    this.$router.push({ name: 'admin.discounts.index' });
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
