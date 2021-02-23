<template>
    <section class="content-box">
        <div class="">
            <div class="box-header">
                <h3 class="box-title">Create new subject</h3>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="box">
                        <ValidationObserver ref="observer" v-slot="{ handleSubmit }">
                            <form @submit.prevent="handleSubmit(submitForm)">

                                <div v-if="user" class="form-group d-flex align-items-center">
                                    <span class="mr-2">To</span>
                                    <img :src="user.avatar" class="avatar mr-2">
                                    {{ user.display_name }}
                                </div>

                                <ValidationProvider name="Subject" rules="required" v-slot="{ errors }">
                                    <div class="form-group">
                                        <label for="subject">Subject</label>
                                        <input
                                                type="text"
                                                :value="subject"
                                                @input="updateSubject"
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
                                                :value="message"
                                                @input="updateMessage"
                                                id="message"
                                                class="form-control"
                                                :class="{'is-invalid': errors[0] }"
                                                rows="3"
                                        >
                                        </textarea>
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
        </div>
    </section>
</template>


<script>
    import { mapGetters, mapActions } from 'vuex'

    export default {
        data() {
            return {
                user: null

            }
        },
        created() {
            this.getUser();
        },
        destroyed() {
            this.resetState()
        },
        computed: {
            ...mapGetters('MessagesNewSubject', [
                'loading',
                'subject',
                'message'
            ]),
        },
        methods: {
            ...mapActions('MessagesNewSubject', [
                'resetState',
                'createSubject',
                'setSubject',
                'setMessage'
            ]),
            updateSubject(e) {
                this.setSubject(e.target.value)
            },
            updateMessage(e) {
                this.setMessage(e.target.value)
            },
            submitForm() {
                this.createSubject(this.$route.params.slug)
                    .then(resp => {
                        this.$router.push({ name: 'admin.messages.show', params: {subjectId: resp.data} })
                    });
            },
            getUser() {
                axios.get('/user/' + this.$route.params.slug)
                    .then(response => {
                        this.user = response.data;
                    });
            }
        }
    }
</script>


<style lang="scss" scoped>
    .avatar {
        width: 25px;
        height: 25px;
        border-radius: 50%
    }
</style>
