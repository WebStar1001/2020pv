<template>
    <div>
        <span v-if="row.chat_session_id">
            {{ row.chat_session.customer.display_name }}
        </span>

        <span v-if="row.email_subject_id">
            {{ row.email_subject.customer.display_name }}
        </span>
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
