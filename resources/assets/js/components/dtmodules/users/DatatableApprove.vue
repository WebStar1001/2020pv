<template>
    <div>
        <div v-if="row.role_id === $roles.ADVISOR">
            <span v-if="value === 1" class="badge badge-pill badge-light">
                Approved
            </span>

            <span v-if="value === 0" class="badge badge-pill badge-danger">
                Not Approved
            </span>
        </div>
    </div>
</template>


<script>
export default {
    props: [
        'row',
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

