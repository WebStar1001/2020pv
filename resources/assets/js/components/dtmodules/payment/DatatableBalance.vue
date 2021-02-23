<template>
    <div class="btn">
        {{ row.balance }}
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


<style lang="scss" scoped>
    .btn {
        padding: 3px 26px !important;
        background: #f1f1f1;
        border-color: #f1f1f1;
        color: #a4aab3;
        font-size: 14px !important;
        text-align: left;
        cursor: default;
    }
</style>
