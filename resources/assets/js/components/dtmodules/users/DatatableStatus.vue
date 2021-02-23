<template>
    <div>
        <span v-if="!value" class="badge badge-pill badge-success">
            Active
        </span>

        <span v-if="value" class="badge badge-pill badge-danger">
            Blocked
        </span>
    </div>
</template>


<script>
export default {
    props: [
        'value',
        'xprops'
    ],
    data() {
        return {
            // Code...
        }
    },
    created() {
        // Code...
    },
    methods: {
        // Code...
    }
}
</script>


<style scoped>

</style>

