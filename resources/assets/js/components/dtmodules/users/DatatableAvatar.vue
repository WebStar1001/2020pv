<template>
    <div class="btn-group btn-group-sm d-flex align-items-center">
        <img
                :src="getAvatar(row)"
                width="40"
                height="40"
                class="mr-2"
                style="border-radius: 50%">

         {{ row.display_name }}

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
            // Code...
        }
    },
    created() {
    },
    methods: {
        getAvatar(row) {
            return this.$gate.user.role_id === this.$roles.CUSTOMER ? row.advisor.avatar : row.customer.avatar;
        },
    }
}
</script>


<style scoped>

</style>
