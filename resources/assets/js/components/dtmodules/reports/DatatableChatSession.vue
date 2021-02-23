<template>
    <div>
        <router-link
                v-if="row.chat_session_id"
                :to="{ name: 'chat.chat.show', params: {chatSessionId: row.chat_session_id} }"
                target="_blank"
        >
            Chat History
        </router-link>

        <router-link
                v-if="row.email_subject_id"
                :to="{ name: 'admin.messages.show', params: {subjectId: row.email_subject_id} }"
                target="_blank"
        >
            Chat History
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
