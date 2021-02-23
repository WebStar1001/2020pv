<template>
    <div>
        <div v-if="row.paypal_payment">
            Paypal
        </div>
        <div v-else>
            <router-link :to="{ name: 'admin.users.show', params: { id: row.id } }" target="_blank">
                Bank
            </router-link>
        </div>
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
