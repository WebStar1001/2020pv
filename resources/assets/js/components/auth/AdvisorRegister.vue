<template>
    <div>
        <div class="auth-box">
            <ValidationObserver v-slot="{ handleSubmit }">
                <h1 class="auth-title text-center">SIGN UP</h1>

                <form v-if="!showSuccessMessage" style="width: 100%" @submit.prevent="handleSubmit(submitForm)">
                    <bootstrap-alert />

                    <ValidationProvider name="Full Name" rules="required" v-slot="{ errors }">
                        <div class="form-group">
                            <input
                                    type="text"
                                    :value="fullName"
                                    @input="updateFullName"
                                    id="full_name"
                                    class="form-control"
                                    :class="{'is-invalid': errors[0] }"
                                    placeholder="Full Name"
                            >
                            <div class="invalid-feedback">{{ errors[0] }}</div>
                        </div>
                    </ValidationProvider>

                    <ValidationProvider name="Display Name" rules="required" v-slot="{ errors }">
                        <div class="form-group">
                            <input
                                    type="text"
                                    :value="displayName"
                                    @input="updateDisplayName"
                                    id="display_name"
                                    class="form-control"
                                    :class="{'is-invalid': errors[0] }"
                                    placeholder="Profile Name"
                            >
                            <div class="invalid-feedback">{{ errors[0] }}</div>
                        </div>
                    </ValidationProvider>

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

                    <ValidationProvider name="Country" rules="required" v-slot="{ errors }">
                        <div class="form-group">
                            <v-select
                                    :value="country"
                                    @input="updateCountry"
                                    :options="countries"
                                    :clearable="false"
                                    label="title"
                                    inputId="country"
                                    :class="{'is-invalid': errors[0] }"
                                    placeholder="Country"
                            />
                            <div class="invalid-feedback" style="display: block;">{{ errors[0] }}</div>
                        </div>
                    </ValidationProvider>

                    <ValidationProvider name="Profile Picture" rules="required|ext:jpg,jpeg,png|size:10000" ref="avatarProvider" v-slot="{ validate, errors }">
                        <div class="form-group">
                            <label for="avatar">Profile picture (PNG or JPG, max 10MB)</label>
                            <input
                                    type="file"
                                    @change="updateAvatar"
                                    ref="avatar"
                                    accept="image/png, image/jpeg"
                                    class="form-control"
                                    id="avatar"
                                    :class="{'is-invalid': errors[0] }"
                            >
                            <div class="invalid-feedback">{{ errors[0] }}</div>
                        </div>
                    </ValidationProvider>

                    <ValidationProvider name="Resume" rules="required|ext:pdf|size:10000" ref="resumeProvider" v-slot="{ validate, errors }">
                        <div class="form-group">
                            <label for="resume">Resume (PDF, max 10MB)</label>
                            <input
                                    type="file"
                                    @change="updateResume"
                                    ref="resume"
                                    accept="application/pdf"
                                    class="form-control"
                                    id="resume"
                                    :class="{'is-invalid': errors[0] }"
                            >
                            <div class="invalid-feedback">{{ errors[0] }}</div>
                        </div>
                    </ValidationProvider>

                    <div class="form-group">
                        <p-check
                                @change="updateSubscribed"
                                :checked="subscribed"
                                color="primary"
                        >
                            I want to receive special offers, horoscopes and coupons by email.
                        </p-check>
                    </div>

                    <ValidationProvider name="Term and Conditions" :rules="{ required: { allowFalse: false } }" v-slot="{ errors }">
                        <div class="form-group">
                            <p-check
                                    v-model="termsAndConditionsComputed"
                                    color="primary"
                            >
                                I accept the <a href="/terms-and-conditions" target="_blank" style="position: relative; z-index: 2">terms and conditions</a> of the site.
                            </p-check>
                            <div class="text-danger" style="font-size: 80%">{{ errors[0] }}</div>
                        </div>
                    </ValidationProvider>

                    <ValidationProvider name="I am 18 years old or above" :rules="{ required: { allowFalse: false } }" v-slot="{ errors }">
                        <div class="form-group">
                            <p-check
                                    v-model="adultComputed"
                                    color="primary"
                            >
                                I am 18 years old or above.
                            </p-check>
                            <div class="text-danger" style="font-size: 80%">{{ errors[0] }}</div>
                        </div>
                    </ValidationProvider>

                    <div class="form-group">
                        <vue-button-spinner
                                class="btn btn-success btn-block"
                                :isLoading="loading"
                                :disabled="loading"
                        >
                            SIGN UP
                        </vue-button-spinner>
                    </div>

                    <div class="forgot-pass-link">
                        Already have an account?
                        <router-link :to="{ name: 'auth.login' }">
                            Login
                        </router-link>
                    </div>
                </form>

                <div v-if="showSuccessMessage" class="alert alert-success mb-0">
                    You have been successfully registered. Your account is awaiting approval by the site administrator. Once approved or denied you will receive an email notice.
                </div>
            </ValidationObserver>
        </div>

        <modal name="crop-modal" :clickToClose="false" :scrollable="true" :adaptive="true" height="auto">
            <div class="modal-body">
                <vue-cropper
                        ref="cropper"
                        :src="srcImage"
                        alt="Source Image"
                        :viewMode="1"
                        :zoomable="false"
                        :aspectRatio="1.4"
                        :minCropBoxWidth="150"
                        :minCropBoxHeight="150"
                >
                </vue-cropper>
            </div>
            <div class="modal-footer">
                <button @click="saveCroppedAvatar()" class="btn btn-success">Save</button>
            </div>
        </modal>

    </div>

</template>


<script>
    import { mapGetters, mapActions } from 'vuex'
    import VueCropper from 'vue-cropperjs';
    import 'cropperjs/dist/cropper.css';

    export default {
        components: { VueCropper },
        data() {
            return {
                showSuccessMessage: false,
                srcImage: '',
            }
        },
        computed: {
            ...mapGetters('AdvisorRegister', [
                'loading',
                'fullName',
                'displayName',
                'country',
                'email',
                'subscribed',
                'termsAndConditions',
                'adult',
                'countries'
            ]),
            termsAndConditionsComputed: {
                get() {
                    return this.termsAndConditions;
                },
                set(val) {
                    this.setTermsAndConditions(val)
                }
            },
            adultComputed: {
                get() {
                    return this.adult;
                },
                set(val) {
                    this.setAdult(val)
                }
            },
        },
        created() {
            this.fetchData();
        },
        destroyed() {
            this.resetState();
        },
        methods: {
            ...mapActions('AdvisorRegister', [
                'fetchData',
                'register',
                'resetState',
                'setFullName',
                'setDisplayName',
                'setEmail',
                'setCountry',
                'setAvatar',
                'setResume',
                'setSubscribed',
                'setTermsAndConditions',
                'setAdult'
            ]),
            updateFullName(e) {
                this.setFullName(e.target.value)
            },
            updateDisplayName(e) {
                this.setDisplayName(e.target.value)
            },
            updateEmail(e) {
                this.setEmail(e.target.value)
            },
            updateCountry(e) {
                this.setCountry(e)
            },
            async updateAvatar(e) {
                const { valid } = await this.$refs.avatarProvider.validate(e);

                if (valid) {
                    this.setAvatar(this.$refs.avatar.files[0]);

                    const reader = new FileReader();
                    reader.readAsDataURL(this.$refs.avatar.files[0]);
                    reader.onload = ()=> {
                        this.srcImage = reader.result;
                        this.$modal.show('crop-modal');
                    };
                }
            },
            saveCroppedAvatar() {
                const _this = this;

                this.$refs.cropper.getCroppedCanvas({
                    width: 200,
                    height: 200,
                    fillColor: '#ffffff',
                }).toBlob((blob) => {
                    _this.setAvatar(blob);
                    _this.$modal.hide('crop-modal')
                }, 'image/jpeg');
            },
            async updateResume(e) {
                const { valid } = await this.$refs.resumeProvider.validate(e);

                if (valid) {
                    this.setResume(this.$refs.resume.files[0]);
                }
            },
            updateSubscribed(e) {
                this.setSubscribed(e)
            },
            submitForm() {
                this.register(4)
                    .then(() => {
                        this.showSuccessMessage = true;
                    })
            },
        }
    }
</script>


<style scoped>
    .forgot-pass-link {
        font-size: 14px;
        text-align: center;
    }
</style>
