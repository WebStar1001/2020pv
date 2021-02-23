<template>
    <div>
        <span class="badge badge-pill badge-danger" v-if="value === $roles.SUPERADMIN">
            Super&nbsp;Admin
        </span>
        <span class="badge badge-pill badge-warning" v-if="value === $roles.ADMIN">
            Admin
        </span>
        <span class="badge badge-pill badge-success" v-if="value === $roles.CUSTOMER">
            Customer
        </span>
        <span class="badge badge-pill badge-info" v-if="value === $roles.ADVISOR">
            Advisor
        </span>
    </div>
</template>


<script>
export default {
    props: ['value', 'field'],
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

