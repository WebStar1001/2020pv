<template>
    <div class="btn-group btn-group-sm" style="display:flex;">
        <a v-if="row.session_id" @click.prevent="showChatDetails(row)" href="#">Online Chat</a>
        <router-link v-if="row.email_subject_id" :to="{ name: 'admin.messages.show', params: { subjectId: row.email_subject_id } }">
            Messages
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
            chatDetails: {}
        }
    },
    created() {
    },
    methods: {
        showChatDetails(row) {
            this.$modal.show('chat-details-modal', {chatSession: row});
        },
    }
}
</script>


<style scoped>

</style>
