<template>
    <div class="h-100">
        <div v-if="advisor" class="d-flex h-100 justify-content-center align-items-center text-center">
            <div>
                <img src="/images/pages/step-register-check.png" class="img-fluid">
                <h3 v-if="$gate && $store.getters.user.balance > 0">Congratulations, All done.</h3>
                <h3 v-if="$gate && $store.getters.user.balance <= 0">Add funds</h3>
                <span>You have <b>{{ getAvalilableMinutes() }}</b> with the psychic</span><br>
                <div v-if="$gate && $store.getters.user.balance <= 0" class="mt-2">
                    <div class="row justify-content-center text-left">
                        <div class="col-lg-10 col-md-10 col-12 pt-4 pb-1">
                            <div>Low balance! Please add funds to use this offer. Funds left will be waiting for your next sessions or can be redeemed later.</div>

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

                                    <div class="form-group text-left">
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
                        </div>
                    </div>
                </div>

                <div v-if="paidAmount" class="text-success text-center mt-2">
                    Congrats! You have added ${{ paidAmount }} into your account.
                </div>
                <br>
                <button
                        v-if="$gate && $store.getters.user.balance > 0"
                        @click="chat(advisor)"
                        :disabled="loading || advisor.status !== 'online'"
                        class="btn btn-custom btn-cs-lg"
                >
                    START CHAT SESSION
                </button>
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
                fetchDataIntervalId: null,
                paidAmount: null,
                countdownSeconds: 180, // 180
                startChatIntervalId: null,
                showCustomField: false,
                discount: 0
            }
        },
        computed: {
            ...mapGetters('StepRegister', [
                'loading',
                'advisor',
                'payAmount'
            ]),
            ...mapGetters('CategoryIndex', [,
                'freeSeconds'
            ]),
        },
        created() {
            // if (this.$gate || sessionStorage.getItem('specialoffer')) {
                if (!this.isUsedDiscount()) {
                    this.discount = this.$store.getters.discount;
                }
            // }

            this.getAdvisor(this.$route.params.slug).then(() => {
                if (this.$route.query.amount) {
                    this.paidAmount = this.$route.query.amount;
                }
            });
            const _this = this;
            this.fetchDataIntervalId = setInterval(function () {
                _this.getAdvisor(_this.$route.params.slug);
            }, 5000);
        },
        beforeDestroy() {
            clearInterval(this.fetchDataIntervalId);
            clearInterval(this.startChatIntervalId);
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
            ...mapActions('CategoryIndex', [
                'getFreeMinutes',
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
            getAvalilableMinutes() {
                if (this.$gate) {
                    return this.getAvailableTime( (this.$store.getters.user.balance / (this.getPricePerMinuteWithDiscount() / 100) + this.freeSeconds / 60) * 60 );
                }
            },
            getAvailableTime(duration) {
                let sec_num = parseInt(duration, 10);
                let hours   = Math.floor(sec_num / 3600);
                let minutes = Math.floor((sec_num - (hours * 3600)) / 60);
                let seconds = sec_num - (hours * 3600) - (minutes * 60);

                if (hours > 0) {
                    return hours + ' hours ' + minutes + ' min ' + seconds + ' sec';
                }

                if (minutes > 0) {
                    return minutes + ' min ' + seconds + ' sec';
                }

                return seconds + ' sec';
            },
            chat(advisor) {
                if (this.$store.getters.user.balance + this.freeSeconds > 0) {
                    this.$store.commit('setUserStatus', 'busy');

                    this.startChat({
                        advisor_id: advisor.id,
                        countdownSeconds: this.countdownSeconds,
                        firstRequest: true
                    }).then(() => {
                        this.$modal.hide('start-chat-modal');
                        this.$modal.show('outgoing-call-modal', {advisor: advisor});
                    }).catch((error) => {
                        this.$store.commit('setUserStatus', 'online');
                    });
                }
            },
            startChat(data) {
                return new Promise((resolve, reject) => {
                    axios.post('/chat/' + data.advisor_id + '/start', {
                        countdown_seconds: data.countdownSeconds,
                        first_request: data.firstRequest
                    })
                        .then(response => {
                            resolve()
                        })
                })
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


<style lang="scss" scoped>
    .fist-step h3 {
        font-size: 1.61rem;
        font-weight: 300;
    }

    .btn-custom {
        background: #85cd03;
        border-color: #85cd03;
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
