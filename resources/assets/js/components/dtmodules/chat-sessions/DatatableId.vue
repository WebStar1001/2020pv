<template>
    <div>
        <router-link
                :to="{ name: 'chat.chat.show', params: {chatSessionId: row.id } }"
                target="_blank"
        >
            {{ row.id }}
        </router-link>
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
