<template>
    <div>
        <div v-if="row.user_id">
            <router-link :to="{ name: 'admin.users.show', params: { id: row.user_id } }">
                {{ row.user.display_name }}
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
