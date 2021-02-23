<template>
    <div>
        <span :class="getClass()">
            {{ row.status }}
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
                    case 'RESOLVED':
                        return 'text-success';
                    case 'UNDER_REVIEW':
                    case 'OTHER':
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
