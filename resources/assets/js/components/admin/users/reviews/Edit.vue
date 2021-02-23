<template>
        <section class="content-box">
            <ValidationObserver v-slot="{ handleSubmit }">
                <form @submit.prevent="handleSubmit(submitForm)">
                    <div>
                        <div class="box-header">
                            <h3 class="box-title">Edit Review</h3>
                        </div>

                        <div class="mb-3">
                            <back-buttton></back-buttton>
                        </div>

                        <div class="box mb-3">

                            <div class="form-group">
                                <label>Review Score</label>
                                <div style="font-size: 26px">
                                    <vue-stars
                                            name="rating"
                                            :max="5"
                                            :value="review.rating"
                                            @input="updateRating"
                                            :readonly="false"
                                    />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="text">Text</label>
                                <textarea
                                        rows="5"
                                        :value="review.text"
                                        @input="updateText"
                                        id="text"
                                        class="form-control"
                                >

                                            </textarea>
                            </div>

                            <div class="box-footer">
                                <vue-button-spinner
                                        class="btn btn-success"
                                        :isLoading="loading"
                                        :disabled="loading"
                                >
                                    Save
                                </vue-button-spinner>
                            </div>
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
        ...mapGetters('ReviewsSingle', [
            'review',
            'loading'
        ]),
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
        ...mapActions('ReviewsSingle', [
            'fetchData',
            'updateData',
            'resetState',
            'setRating',
            'setText'
        ]),
        updateRating(e) {
            this.setRating(e)
        },
        updateText(e) {
            this.setText(e.target.value)
        },
        submitForm() {
            this.updateData(this.$route.params.id)
                .then(() => {
                    this.$eventHub.$emit('update-success');
                    this.$router.go(-1);
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
