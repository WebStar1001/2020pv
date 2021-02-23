<template>
    <div class="d-flex align-items-center">
        <div class="table-avatar" :style="'background: url(' + row.customer.avatar + ')'"></div>
        {{ row.customer.display_name }}
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
