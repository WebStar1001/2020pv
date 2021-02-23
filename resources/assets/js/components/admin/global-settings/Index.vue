<template>
    <section class="content-box">
        <div class="">
            <div class="box-header">
                <h3 class="box-title">Global Settings</h3>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <div class="row">
                            <div class="col-md-8">
                                <ValidationObserver ref="observer" v-slot="{ handleSubmit }">
                                    <form @submit.prevent="handleSubmit(submitForm)">
                                        <div class="row">
                                            <div class="col-md-8">

                                                <ValidationProvider name="Discount" rules="required|min_value:0|max_value:99" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="discount">Discount</label>
                                                        <input
                                                                type="number"
                                                                v-model="discount"
                                                                id="discount"
                                                                class="form-control"
                                                                :class="{'is-invalid': errors[0] }"
                                                        >
                                                        <div class="invalid-feedback">{{ errors[0] }}</div>
                                                    </div>
                                                </ValidationProvider>

                                            </div>
                                        </div>

                                        <vue-button-spinner
                                                class="btn btn-success"
                                                :isLoading="loading"
                                                :disabled="loading"
                                        >
                                            Save
                                        </vue-button-spinner>
                                    </form>
                                </ValidationObserver>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</template>


<script>
    export default {
        data() {
            return {
                loading: false,
                discount: null,
            }
        },
        created() {
            this.fetchData();
        },
        destroyed() {
        },
        computed: {
        },
        methods: {
            fetchData() {
                this.loading = true;

                axios.get('/global-settings')
                    .then(resp => {
                        this.discount = resp.data.settings.discount
                    })
                    .finally(() => {
                        this.loading = false;
                    })
            },
            submitForm() {
                this.loading = true;

                axios.post('/global-settings', {
                    settings: [
                        {key: 'discount', value: this.discount}
                    ],
                })
                    .then(response => {
                        this.$eventHub.$emit('update-success');
                    })
                    .catch(error => {
                        alert('something went wrong');
                    })
                    .finally(() => {
                        this.loading = false;
                    })
            },
        }
    }
</script>


<style scoped>

</style>
