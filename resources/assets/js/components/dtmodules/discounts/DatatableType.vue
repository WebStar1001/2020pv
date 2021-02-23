<template>
    <div>
        <span v-if="!value" class="badge badge-pill badge-primary">
            Old clients
        </span>

        <span v-if="value" class="badge badge-pill badge-info">
            New clients
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

