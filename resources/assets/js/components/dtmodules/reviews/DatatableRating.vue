<template>
    <div>
        <vue-stars
                :name="row.id.toString()"
                :max="5"
                :value="row.rating"
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
            }
        },
        created() {
        },
        methods: {
        }
    }
</script>


<style scoped>

</style>
