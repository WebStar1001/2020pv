<template>
    <div>
        <vue-stars
                v-if="row.review"
                :name="ratingId"
                :max="5"
                :value="row.review.rating"
                :readonly="true"
        />

        <vue-stars
                v-if="!row.review"
                :name="ratingId"
                :max="5"
                :value="0"
                :readonly="true"
        />
    </div>
</template>


<script>
    export default {
        props: [
            'row',
            'xprops',
        ],
        data() {
            return {
                ratingId: null
            }
        },
        created() {
            this.ratingId = Math.random().toString();
        },
        methods: {
        }
    }
</script>


<style scoped>

</style>
