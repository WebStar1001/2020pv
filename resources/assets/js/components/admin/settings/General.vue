<template>
    <div>
        <div class="setting-title">General Information</div>

        <ValidationObserver v-slot="{ handleSubmit }">
            <form @submit.prevent="handleSubmit(submitForm)">
                <ValidationProvider name="Profile Picture" rules="ext:jpg,jpeg,png|size:10000" ref="avatarProvider" v-slot="{ validate, errors }">
                    <div class="form-group">

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div v-if="srcImage" class="avatar" :style="'background: url(' + srcImage+ ')'"></div>
                                <div v-if="item.avatar" class="avatar" :style="'background: url(' + item.avatar+ ')'"></div>

                                <div class="avatar-title">
                                    Change Avatar
                                    <!--<div class="caption">-->
                                        <!--Photos should be square and at least 400 x 400 pixels-->
                                    <!--</div>-->
                                </div>
                            </div>
                            <div>
                                <label for="avatar" class="btn btn-success">
                                    Upload
                                </label>
                            </div>

                            <input
                                    type="file"
                                    @change="updateAvatar"
                                    ref="avatar"
                                    accept="image/png, image/jpeg"
                                    class="form-control"
                                    id="avatar"
                                    style="display: none;"
                                    :class="{'is-invalid': errors[0] }"
                            >
                        </div>

                        <div class="invalid-feedback">{{ errors[0] }}</div>
                    </div>
                </ValidationProvider>

                <div class="row">
                    <div class="col-md-6">
                        <ValidationProvider v-if="$gate.user.role_id === $roles.ADVISOR" name="Full Name" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                                <label for="full_name">Full Name</label>
                                <input
                                        type="text"
                                        :value="item.full_name"
                                        @input="updateFullName"
                                        id="full_name"
                                        class="form-control"
                                        :class="{'is-invalid': errors[0] }"
                                >
                                <div class="invalid-feedback">{{ errors[0] }}</div>
                            </div>
                        </ValidationProvider>

                        <div v-if="$gate.user.role_id === $roles.CUSTOMER" class="form-group">
                            <label for="full_name">Full Name</label>
                            <input
                                    type="text"
                                    :value="item.full_name"
                                    @input="updateFullName"
                                    id="full_name"
                                    class="form-control"
                            >
                        </div>
                    </div>
                    <div class="col-md-6">
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
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
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
                                        disabled
                                >
                                <div class="invalid-feedback">{{ errors[0] }}</div>
                            </div>
                        </ValidationProvider>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="country">Country</label>
                            <v-select
                                    :value="item.country"
                                    @input="updateCountry"
                                    :options="countries"
                                    :clearable="false"
                                    label="title"
                                    placeholder="Select Country"
                                    inputId="country"
                            />
                        </div>
                    </div>
                </div>

                <div v-if="$gate.user.role_id === $roles.ADVISOR" class="row">
                    <div class="col-md-6">
                        <ValidationProvider name="Languages" rules="required" v-slot="{ errors }">
                        <div class="form-group">
                            <label for="languages">Languages</label>
                            <v-select
                                    multiple
                                    :value="item.languages"
                                    @input="updateLanguages"
                                    :options="languages"
                                    label="title"
                                    placeholder="Select Languages"
                                    inputId="languages"
                                    :class="{'is-invalid': errors[0] }"
                            />
                            <div class="invalid-feedback" style="display: block;">{{ errors[0] }}</div>
                        </div>
                        </ValidationProvider>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="horoscope">Horoscope Sign</label>
                            <v-select
                                    :value="horoscope"
                                    @input="updateHoroscope"
                                    :options="horoscopes"
                                    :clearable="false"
                                    label="title"
                                    placeholder="Select Horoscope"
                                    inputId="horoscope"
                            />
                        </div>
                    </div>
                </div>

                <div v-if="$gate.user.role_id === $roles.ADVISOR" class="row">
                    <div class="col-md-6">
                        <ValidationProvider name="Master Service" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                                <label for="master_service">Master Service</label>
                                <v-select
                                        :value="masterService"
                                        @input="updateMasterService"
                                        :options="masterServices"
                                        :clearable="false"
                                        label="title"
                                        placeholder="Select Master Service"
                                        inputId="master_service"
                                        :class="{'is-invalid': errors[0] }"
                                />
                                <div class="invalid-feedback" style="display: block;">{{ errors[0] }}</div>
                            </div>
                        </ValidationProvider>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="services">Other Services</label>
                            <v-select
                                    multiple
                                    :value="item.services"
                                    @input="updateSecondaryServices"
                                    :options="services"
                                    :clearable="false"
                                    label="title"
                                    placeholder="Select Services"
                                    inputId="services"
                            />
                        </div>
                    </div>
                </div>

                <div v-if="$gate.user.role_id === $roles.ADVISOR" class="row">
                    <div class="col-md-6">
                        <ValidationProvider name="Per min rate" rules="required|min_value:1.99" v-slot="{ errors }">
                            <div class="form-group">
                                <label for="price_per_minute">Per min rate</label>
                                <div class="d-flex align-items-center">
                                    <div class="mr-2">
                                        <input
                                                type="text"
                                                min="1"
                                                :value="item.price_per_minute"
                                                @input="updatePricePerMinute"
                                                id="price_per_minute"
                                                class="form-control"
                                                :class="{'is-invalid': errors[0] }"
                                        >
                                        <div class="invalid-feedback">{{ errors[0] }}</div>
                                    </div>
                                    <div v-if="item.price_per_minute" class="text-muted">
                                        (<strong>${{ getPricePerHour() }}</strong> per hour)
                                    </div>
                                </div>
                            </div>
                        </ValidationProvider>
                    </div>
                    <div class="col-md-6">
                        <!-- TODO: email rate -->
                    </div>
                </div>

                <div v-if="$gate.user.role_id === $roles.ADVISOR" class="row">
                    <div class="col-md-6">
                        <ValidationProvider name="Experience in years" rules="required|numeric" v-slot="{ errors }">
                            <div class="form-group">
                                <label for="experience_years">Experience in years</label>
                                <input
                                        type="number"
                                        :value="item.experience_years"
                                        @input="updateExperienceYears"
                                        id="experience_years"
                                        class="form-control"
                                        :class="{'is-invalid': errors[0] }"
                                >
                                <div class="invalid-feedback">{{ errors[0] }}</div>
                            </div>
                        </ValidationProvider>
                    </div>
                    <div class="col-md-6">
                        <ValidationProvider name="Experience" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                                <label for="experience">Tell us about your Experience:</label>
                                <textarea
                                        :value="item.experience"
                                        @input="updateExperience"
                                        rows="5"
                                        id="experience"
                                        class="form-control"
                                        :class="{'is-invalid': errors[0] }"
                                ></textarea>
                                <div class="invalid-feedback">{{ errors[0] }}</div>
                            </div>
                        </ValidationProvider>
                    </div>
                </div>

                <div v-if="$gate.user.role_id === $roles.ADVISOR" class="row">
                    <div class="col-md-6">
                        <ValidationProvider name="Highlight" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                                <label for="highlight">Highlight yourself</label>
                                <textarea
                                        :value="item.highlight"
                                        @input="updateHighlight"
                                        rows="5"
                                        id="highlight"
                                        class="form-control"
                                        :class="{'is-invalid': errors[0] }"
                                ></textarea>
                                <div class="invalid-feedback">{{ errors[0] }}</div>
                            </div>
                        </ValidationProvider>
                    </div>

                    <div class="col-md-6">
                        <ValidationProvider name="About yourself" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                                <label for="description">About yourself</label>
                                <textarea
                                        :value="item.description"
                                        @input="updateDescription"
                                        rows="5"
                                        id="description"
                                        class="form-control"
                                        :class="{'is-invalid': errors[0] }"
                                ></textarea>
                                <div class="invalid-feedback">{{ errors[0] }}</div>
                            </div>
                        </ValidationProvider>
                    </div>
                </div>

                <div v-if="$gate.user.role_id === $roles.ADVISOR" class="row">
                    <div class="col-md-6">
                        <ValidationProvider name="About My Services" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                                <label for="about_services">About My Services</label>
                                <textarea
                                        :value="item.about_services"
                                        @input="updateAboutServices"
                                        rows="5"
                                        id="about_services"
                                        class="form-control"
                                        :class="{'is-invalid': errors[0] }"
                                ></textarea>
                                <div class="invalid-feedback">{{ errors[0] }}</div>
                            </div>
                        </ValidationProvider>
                    </div>

                    <div class="col-md-6">
                        <ValidationProvider name="Qualifications" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                                <label for="qualification">Other qualifications</label>
                                <textarea
                                        :value="item.qualification"
                                        @input="updateQualification"
                                        rows="5"
                                        id="qualification"
                                        class="form-control"
                                        :class="{'is-invalid': errors[0] }"
                                ></textarea>
                                <div class="invalid-feedback">{{ errors[0] }}</div>
                            </div>
                        </ValidationProvider>
                    </div>
                </div>

                <div>
                    <vue-button-spinner
                            class="btn btn-success settings-submit-btn"
                            :isLoading="loading"
                            :disabled="loading"
                    >
                        UPDATE
                    </vue-button-spinner>
                </div>

            </form>
        </ValidationObserver>

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
    import { mapGetters, mapActions } from 'vuex';
    import VueCropper from 'vue-cropperjs';
    import 'cropperjs/dist/cropper.css';
    import MyMixins from '../../../mixins'

    export default {
        components: { VueCropper },
        mixins: [MyMixins],
        data() {
            return {
                srcImage: '',
            }
        },
        created() {
            this.fetchData();
        },
        destroyed() {
            this.resetState()
        },
        computed: {
            ...mapGetters('SettingsGeneral', [
                'loading',
                'item',
                'countries',
                'languages',
                'horoscopes',
                'masterServices',
                'services'
            ]),
            horoscope() {
                for (let horoscope of this.horoscopes) {
                    if (horoscope.id === this.item.horoscope_id) {
                        return horoscope;
                    }
                }
            },
            masterService() {
                for (let masterService of this.masterServices) {
                    if (masterService.id === this.item.master_service_id) {
                        return masterService;
                    }
                }
            }
        },
        methods: {
            ...mapActions('SettingsGeneral', [
                'fetchData',
                'resetState',
                'setEmail',
                'setFullName',
                'setDisplayName',
                'setCountry',
                'setAvatar',
                'setSelectedLanguages',
                'setHoroscope',
                'setMasterService',
                'setSecondaryServices',
                'setPricePerMinute',
                'setExperienceYears',
                'setExperience',
                'setHighlight',
                'setDescription',
                'setAboutServices',
                'setQualification',
                'updateGeneralInformation'
            ]),
            updateEmail(e) {
                this.setEmail(e.target.value)
            },
            updateFullName(e) {
                this.setFullName(e.target.value)
            },
            updateDisplayName(e) {
                this.setDisplayName(e.target.value)
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
                    _this.$modal.hide('crop-modal');

                    const reader = new FileReader();
                    reader.readAsDataURL(blob);
                    reader.onload = ()=> {
                        _this.srcImage = reader.result;
                    };
                }, 'image/jpeg');
            },
            updateLanguages(e) {
                this.setSelectedLanguages(e)
            },
            updateHoroscope(e) {
                this.setHoroscope(e.id)
            },
            updateMasterService(e) {
                this.setMasterService(e.id)
            },
            updateSecondaryServices(e) {
                this.setSecondaryServices(e)
            },
            updatePricePerMinute(e) {
                const filteredValue = e.target.value.replace(/[^\d.-]/g, '');

                this.setPricePerMinute(filteredValue)
            },
            updateExperienceYears(e) {
                this.setExperienceYears(e.target.value)
            },
            updateExperience(e) {
                this.setExperience(e.target.value)
            },
            updateHighlight(e) {
                this.setHighlight(e.target.value)
            },
            updateDescription(e) {
                this.setDescription(e.target.value)
            },
            updateAboutServices(e) {
                this.setAboutServices(e.target.value)
            },
            updateQualification(e) {
                this.setQualification(e.target.value)
            },
            getPricePerHour() {
                return this.toFixed(this.item.price_per_minute * 60, 2)
            },
            submitForm() {
                this.updateGeneralInformation()
                    .then(() => {
                        this.$eventHub.$emit('update-success')
                    })
            },
        }
    }
</script>


<style lang="scss" scoped>
    .avatar {
        margin-right: 20px;
        width: 100px;
        height: 100px;
        background-size: cover !important;
        background-position: center center !important;
    }
    .avatar-title {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        .caption {
            font-weight: 500;
            color: #949494;
        }
    }
    .btn-upload {
        cursor: pointer;
        .icomoon {
            margin-right: 5px;
            background: linear-gradient(270deg, #F58233 0%, #C457B7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    }
</style>
