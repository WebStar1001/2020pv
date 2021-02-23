<template>
    <div class="content-box">
        <superadmin-dashboard v-if="$gate.user.role_id === $roles.SUPERADMIN" />
        <advisor-dashboard v-if="$gate.user.role_id === $roles.ADVISOR" />
        <customer-dashboard v-if="$gate.user.role_id === $roles.CUSTOMER" />
    </div>
</template>


<script>
    export default {
    }
</script>


<style scoped>

</style>
