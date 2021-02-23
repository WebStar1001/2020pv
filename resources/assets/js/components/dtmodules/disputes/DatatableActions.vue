<template>
    <div class="btn-group btn-group-sm" style="display:flex;">
            <router-link
                    :to="{ name: 'admin.disputes.show', params: { id: row.id } }"
                    class="btn btn-success m-1"
            >
                Details
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
