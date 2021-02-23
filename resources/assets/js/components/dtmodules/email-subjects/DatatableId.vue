<template>
    <div>
        <router-link
                :to="{ name: 'admin.messages.show', params: {subjectId: row.id } }"
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
