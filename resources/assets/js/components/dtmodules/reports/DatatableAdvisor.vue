<template>
    <div>
        <span v-if="row.chat_session_id">
            {{ row.chat_session.advisor.display_name }}
        </span>

        <span v-if="row.email_subject_id">
            {{ row.email_subject.advisor.display_name }}
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
