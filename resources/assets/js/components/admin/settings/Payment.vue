<template>
    <div>
        <div class="setting-title">Payment Settings</div>

        <ValidationObserver v-slot="{ handleSubmit }">
        <form @submit.prevent="handleSubmit(submitPaymentForm)">
            <!--<div class="row">-->
                <!--<div class="col-md-4">-->
                    <!--<div class="form-group">-->
                        <!--<label for="country">Country</label>-->
                        <!--<v-select-->
                                <!--:value="country"-->
                                <!--@input="updateCountry"-->
                                <!--:options="countries"-->
                                <!--:clearable="false"-->
                                <!--label="title"-->
                                <!--placeholder="Select Country"-->
                                <!--inputId="country"-->
                        <!--/>-->
                    <!--</div>-->
                <!--</div>-->
            <!--</div>-->

            <div class="form-group paypal-checkbox">
                <p-radio name="radio" color="primary" value="1" @change="updatePaymentMethod" :checked="paypalPayment == 1">
                    <img src="/images/admin/paypal.svg" alt="Paypal">
                </p-radio>
            </div>

            Emails are currently being send to {{ $gate.user.payment_email }}
            <span v-if="pending === true"><strong>(Request is pending)</strong></span>
            <span v-if="pending === false">(<a @click.prevent="$modal.show('change-payment-email-modal')" href="#">Request change</a>)</span>

            <div class="mt-4 mb-4">
                <strong>Note:</strong> The payment email should be a business paypal account. | <span class="text-primary">2% Paypal Charges</span>
            </div>

            <!--<div v-if="country === 'Pakistan'">-->
                <!--<div class="form-group">-->
                    <!--<p-radio name="radio" color="primary" value="0" @change="updatePaymentMethod" :checked="paypalPayment == '0'">-->
                        <!--Bank Transfer-->
                    <!--</p-radio>-->
                <!--</div>-->

                <!--<div v-if="paypalPayment == '0'" class="row">-->
                    <!--<div class="col-md-8">-->
                        <!--<ValidationProvider name="Full Name" rules="required" v-slot="{ errors }">-->
                            <!--<div class="form-group">-->
                                <!--<label for="fullname">Full Name</label>-->
                                <!--<input-->
                                        <!--type="text"-->
                                        <!--:value="bankDetails.full_name"-->
                                        <!--@input="updateFullName"-->
                                        <!--id="fullname"-->
                                        <!--class="form-control"-->
                                        <!--:class="{'is-invalid': errors[0] }"-->
                                <!--&gt;-->
                                <!--<div class="invalid-feedback">{{ errors[0] }}</div>-->
                            <!--</div>-->
                        <!--</ValidationProvider>-->

                        <!--<ValidationProvider name="Address" rules="required" v-slot="{ errors }">-->
                            <!--<div class="form-group">-->
                                <!--<label for="address">Address</label>-->
                                <!--<input-->
                                        <!--type="text"-->
                                        <!--:value="bankDetails.address"-->
                                        <!--@input="updateAddress"-->
                                        <!--id="address"-->
                                        <!--class="form-control"-->
                                        <!--:class="{'is-invalid': errors[0] }"-->
                                <!--&gt;-->
                                <!--<div class="invalid-feedback">{{ errors[0] }}</div>-->
                            <!--</div>-->
                        <!--</ValidationProvider>-->

                        <!--<ValidationProvider name="Zipcode" rules="required" v-slot="{ errors }">-->
                            <!--<div class="form-group">-->
                                <!--<label for="zipcode">Zipcode</label>-->
                                <!--<input-->
                                        <!--type="text"-->
                                        <!--:value="bankDetails.zipcode"-->
                                        <!--@input="updateZipcode"-->
                                        <!--id="zipcode"-->
                                        <!--class="form-control"-->
                                        <!--:class="{'is-invalid': errors[0] }"-->
                                        <!--style="max-width: 200px"-->
                                <!--&gt;-->
                                <!--<div class="invalid-feedback">{{ errors[0] }}</div>-->
                            <!--</div>-->
                        <!--</ValidationProvider>-->

                        <!--<ValidationProvider name="IBAN" rules="required" v-slot="{ errors }">-->
                            <!--<div class="form-group">-->
                                <!--<label for="iban">IBAN</label>-->
                                <!--<input-->
                                        <!--type="text"-->
                                        <!--:value="bankDetails.iban"-->
                                        <!--@input="updateIban"-->
                                        <!--id="iban"-->
                                        <!--class="form-control"-->
                                        <!--:class="{'is-invalid': errors[0] }"-->
                                <!--&gt;-->
                                <!--<div class="invalid-feedback">{{ errors[0] }}</div>-->
                            <!--</div>-->
                        <!--</ValidationProvider>-->

                        <!--<ValidationProvider name="SWIFT" rules="required" v-slot="{ errors }">-->
                            <!--<div class="form-group">-->
                                <!--<label for="swift">SWIFT</label>-->
                                <!--<input-->
                                        <!--type="text"-->
                                        <!--:value="bankDetails.swift"-->
                                        <!--@input="updateSwift"-->
                                        <!--id="swift"-->
                                        <!--class="form-control"-->
                                        <!--:class="{'is-invalid': errors[0] }"-->
                                <!--&gt;-->
                                <!--<div class="invalid-feedback">{{ errors[0] }}</div>-->
                            <!--</div>-->
                        <!--</ValidationProvider>-->

                        <!--<ValidationProvider name="Bank Name" rules="required" v-slot="{ errors }">-->
                            <!--<div class="form-group">-->
                                <!--<label for="bank_name">Bank name</label>-->
                                <!--<input-->
                                        <!--type="text"-->
                                        <!--:value="bankDetails.bank_name"-->
                                        <!--@input="updateBankName"-->
                                        <!--id="bank_name"-->
                                        <!--class="form-control"-->
                                        <!--:class="{'is-invalid': errors[0] }"-->
                                <!--&gt;-->
                                <!--<div class="invalid-feedback">{{ errors[0] }}</div>-->
                            <!--</div>-->
                        <!--</ValidationProvider>-->
                    <!--</div>-->
                <!--</div>-->
            <!--</div>-->


            <!--<div class="form-group">-->
                <!--<vue-button-spinner-->
                        <!--class="btn btn-primary"-->
                        <!--:isLoading="loading"-->
                        <!--:disabled="loading"-->
                <!--&gt;-->
                    <!--Save-->
                <!--</vue-button-spinner>-->
            <!--</div>-->
        </form>
        </ValidationObserver>

        <modal name="change-payment-email-modal" :scrollable="true" :adaptive="true" width="500" height="auto">
            <div class="modal-header">
                <div class="title">
                    Change Payment Email
                </div>
                <div>
                    <button @click="$modal.hide('change-payment-email-modal')" class="btn btn-link btn-close">
                        <i class="icomoon icomoon-cross"></i>
                    </button>
                </div>
            </div>
            <ValidationObserver v-slot="{ handleSubmit }">
                <form @submit.prevent="handleSubmit(submitForm)">
                    <div class="modal-body">
                        <ValidationProvider name="Email" rules="required|email" v-slot="{ errors }">
                            <div class="form-group">
                                <label for="email">Payment Email</label>
                                <input
                                        type="email"
                                        :value="newPaymentEmail"
                                        @input="updateNewPaymentEmail"
                                        id="email"
                                        class="form-control"
                                        :class="{'is-invalid': errors[0] }"
                                >
                                <div class="invalid-feedback">{{ errors[0] }}</div>
                            </div>
                        </ValidationProvider>
                    </div>
                    <div class="modal-footer">
                        <vue-button-spinner
                                class="btn btn-success"
                                :isLoading="loading"
                                :disabled="loading"
                        >
                            Submit
                        </vue-button-spinner>

                        <button @click="$modal.hide('change-payment-email-modal')" type="button" class="btn btn-secondary">
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
                radio: null
            }
        },
        created() {
            this.fetchData();
        },
        destroyed() {
            this.resetState()
        },
        computed: {
            ...mapGetters('SettingsPayment', [
                'loading',
                'pending',
                'newPaymentEmail',
                'countries',
                'country',
                'paypalPayment',
                'bankDetails'
            ]),
        },
        methods: {
            ...mapActions('SettingsPayment', [
                'resetState',
                'fetchData',
                'setNewPaymentEmail',
                'updatePayment',
                'setCountry',
                'setPaypalPayment',
                'setFullName',
                'setAddress',
                'setZipcode',
                'setIban',
                'setSwift',
                'setBankName'
            ]),
            updateNewPaymentEmail(e) {
                this.setNewPaymentEmail(e.target.value)
            },
            updateCountry(e) {
                this.setCountry(e);

                if (e !== 'Pakistan') {
                    this.setPaypalPayment(1)
                }
            },
            submitForm() {
                this.updatePayment()
                    .then(() => {
                        this.fetchData();
                        this.$modal.hide('change-payment-email-modal');
                    })
            },
            updatePaymentMethod(e) {
                this.setPaypalPayment(e);
            },
            submitPaymentForm() {
                this.updatePayment()
                    .then(() => {
                        this.$eventHub.$emit('update-success')
                    })
            },
            updateFullName(e) {
                this.setFullName(e.target.value);
            },
            updateAddress(e) {
                this.setAddress(e.target.value);
            },
            updateZipcode(e) {
                this.setZipcode(e.target.value);
            },
            updateIban(e) {
                this.setIban(e.target.value);
            },
            updateSwift(e) {
                this.setSwift(e.target.value);
            },
            updateBankName(e) {
                this.setBankName(e.target.value);
            }
        }
    }
</script>


<style lang="scss" scoped>
</style>
