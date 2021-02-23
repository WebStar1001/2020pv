<template>
    <div class="btn-group btn-group-sm" style="display:flex;">
        <a @click.prevent="showChatDetails(row)" href="#">Online Chat</a>
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
