<template>
    <section class="content-box">
        <div class="box-header">
            <h3 class="box-title">Newsletter</h3>
        </div>

        <div class="row">
            <div class="col-md-8">
                <ValidationObserver ref="observer" v-slot="{ handleSubmit }">
                    <form @submit.prevent="handleSubmit(submitForm)">
                        <div class="box">
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select v-model="role_id" id="role" class="form-control">
                                    <option value="">All</option>
                                    <option :value="$roles.CUSTOMER">Customers</option>
                                    <option :value="$roles.ADVISOR">Advisors</option>
                                </select>
                            </div>

                            <ValidationProvider name="Subject" rules="required" v-slot="{ errors }">
                                <div class="form-group">
                                    <label for="subject">Subject</label>
                                    <input
                                            type="text"
                                            v-model="subject"
                                            id="subject"
                                            class="form-control"
                                            :class="{'is-invalid': errors[0] }"
                                    >
                                    <div class="invalid-feedback">{{ errors[0] }}</div>
                                </div>
                            </ValidationProvider>

                            <ValidationProvider name="Message" rules="required" v-slot="{ errors }">
                                <div class="form-group">
                                    <label for="message">Message</label>
                                    <textarea
                                            v-model="message"
                                            rows="12"
                                            id="message"
                                            class="form-control"
                                            :class="{'is-invalid': errors[0] }"
                                    ></textarea>
                                    <div class="invalid-feedback">{{ errors[0] }}</div>
                                </div>
                            </ValidationProvider>

                            <vue-button-spinner
                                    class="btn btn-success"
                                    :isLoading="loading"
                                    :disabled="loading"
                            >
                                Submit
                            </vue-button-spinner>
                        </div>
                    </form>
                </ValidationObserver>
            </div>

            <div class="col-md-4">
                <div class="box">
                    <h4 class="mb-3">Send Test Email</h4>
                    <ValidationObserver ref="TestFormObserver" v-slot="{ handleSubmit }">
                        <form @submit.prevent="handleSubmit(submitTestForm)">
                            <ValidationProvider name="Email" rules="required" v-slot="{ errors }">
                                <div class="form-group">
                                    <label for="test-email">Email</label>
                                    <input
                                            type="email"
                                            v-model="testEmail"
                                            id="test-email"
                                            class="form-control"
                                            :class="{'is-invalid': errors[0] }"
                                    >
                                    <div class="invalid-feedback">{{ errors[0] }}</div>
                                </div>
                            </ValidationProvider>

                            <vue-button-spinner
                                    class="btn btn-success"
                                    :isLoading="loading"
                                    :disabled="loading"
                            >
                                Submit
                            </vue-button-spinner>
                        </form>
                    </ValidationObserver>
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
                role_id: '',
                subject: '',
                message: '',
                testEmail: ''
            }
        },
        created() {

        },
        destroyed() {
        },
        computed: {
        },
        watch: {
            query: {
                handler(query) {
                    this.setQuery(query)
                },
                deep: true,
            }
        },
        methods: {
            submitForm() {
                this.loading = true;

                axios.post('/newsletter', {
                    role_id: this.role_id,
                    subject: this.subject,
                    message: this.message
                })
                    .then(response => {
                        this.$eventHub.$emit('newsletter-submitted');
                        this.subject = '';
                        this.message = '';

                        // reset form errors
                        this.$nextTick(() => this.$refs.observer.reset());
                    })
                    .catch(error => {
                        alert('something went wrong');
                    })
                    .finally(() => {
                        this.loading = false;
                    })
            },
            submitTestForm() {
                if (this.subject && this.message) {
                    this.loading = true;

                    axios.post('/newsletter/test', {
                        email: this.testEmail,
                        subject: this.subject,
                        message: this.message
                    })
                        .then(response => {
                            this.$eventHub.$emit('test-email-submitted');

                            // reset form errors
                            this.$nextTick(() => this.$refs.TestFormObserver.reset());
                        })
                        .catch(error => {
                            alert('something went wrong');
                        })
                        .finally(() => {
                            this.loading = false;
                        })
                } else {
                    alert('Subject and Message are not filled')
                }
            }
        }
    }
</script>


<style scoped>

</style>
