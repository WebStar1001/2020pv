<template>
    <div>
        <div class="title">Fund your account to be able to pay your psychic</div>

        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-10 col-12 pt-4 pb-1">
                <ValidationObserver v-slot="{ handleSubmit }">
                    <form @submit.prevent="handleSubmit(submitPayForm)">
                        <div class="form-group">
                            <select @change="updatePayAmount" :disabled="loading" class="form-control mb-0">
                                <option value="10">$10 / {{ getMinutesByPrice(10) }} minutes</option>
                                <option value="25">$25 / {{ getMinutesByPrice(25) }} minutes</option>
                                <option value="50">$50 / {{ getMinutesByPrice(50) }} minutes</option>
                                <option value="100">$100 / {{ getMinutesByPrice(100) }} minutes</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>

                        <div v-if="showCustomField">
                            <ValidationProvider name="Amount" rules="required|min_value:10|integer" v-slot="{ errors }">
                                <div class="form-group">
                                    <label for="custom-amount">Amount</label>
                                    <input
                                            :value="payAmount"
                                            @input="updateCustomPayAmount"
                                            type="number"
                                            :disabled="loading"
                                            id="custom-amount"
                                            class="form-control"
                                            :class="{'is-invalid': errors[0] }"
                                    >
                                    <div class="invalid-feedback">{{ errors[0] }}</div>
                                </div>
                            </ValidationProvider>
                        </div>

                        <div class="form-group">
                            <vue-button-spinner
                                    class="btn btn-success btn-sm mt-0 pl-4 pr-4"
                                    :isLoading="loading"
                                    :disabled="loading"
                            >
                                Add Funds
                            </vue-button-spinner>
                        </div>
                    </form>
                </ValidationObserver>


                <div v-if="showError" class="text-danger">
                    Please add funds!
                </div>

                <div class="text-right">
                    <button
                            class="btn bg-chat-s btn-cs-lg"
                            :isLoading="loading"
                            :disabled="loading"
                            @click="showError = true"
                    >
                        NEXT
                    </button>
                </div>
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
                showError: false,
                showCustomField: false,
                discount: 0
            }
        },
        computed: {
            ...mapGetters('StepRegister', [
                'loading',
                'advisor',
                'payAmount'
            ])
        },
        created() {
            // if (this.$gate || sessionStorage.getItem('specialoffer')) {
                if (!this.isUsedDiscount()) {
                    this.discount = this.$store.getters.discount;
                }
            // }

            this.getAdvisor(this.$route.params.slug)
        },
        destroyed() {
            this.resetState();
        },
        methods: {
            ...mapActions('StepRegister', [
                'resetState',
                'getAdvisor',
                'setPayAmount',
                'pay'
            ]),
            getMinutesByPrice(amount) {
                if (this.advisor) {
                    return Math.floor(amount / (this.getPricePerMinuteWithDiscount() / 100));
                }
            },
            updatePayAmount(e) {
                if (e.target.value === 'custom') {
                    this.showCustomField = true;
                    this.setPayAmount(null)
                } else {
                    this.showCustomField = false;
                    this.setPayAmount(e.target.value)
                }
            },
            updateCustomPayAmount(e) {
                this.setPayAmount(e.target.value);
            },
            submitPayForm() {
                this.pay(this.$route.params.slug);
            },
            getPricePerMinuteWithDiscount() {
                if (this.discount) {
                    return this.advisor.price_per_minute - (this.advisor.price_per_minute / 100) * this.discount;
                }

                return this.advisor.price_per_minute;
            }
        }
    }
</script>


<style scoped>
    .title {
        font-size: 1.61rem;
        text-align: center;
        font-weight: 300;
        margin-bottom: .5rem;
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

    .form-control {
        height: 47px;
        font-size: 14px;
        color: #868585;
    }
</style>
