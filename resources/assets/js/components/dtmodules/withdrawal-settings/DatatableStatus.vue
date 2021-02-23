<template>
    <div>
        <span :class="getClass()">
            {{ row.transaction_status }}
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
            getClass() {
                switch (this.row.transaction_status) {
                    case 'SUCCESS':
                        return 'text-success';
                    case 'PENDING':
                        return 'text-warning';
                    default:
                        return 'text-danger'
                }
            }
        }
    }
</script>


<style scoped>

</style>
