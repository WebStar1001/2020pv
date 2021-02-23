<template>
    <div class="btn-group btn-group-sm d-flex align-items-center">
        <span :class="getClassName(row)">
            <span v-if="+row.amount > 0">
                +${{ toFixed(row.amount, 2) }}
            </span>

            <span v-if="+row.amount < 0">
                  -${{ toFixed(row.amount, 2) }}
            </span>

            <span v-if="+row.amount === 0">
                <span v-if="$gate.user.role_id === $roles.CUSTOMER">-</span>
                <span v-else>+</span>$0.00
            </span>
        </span>
    </div>
</template>


<script>
    import MyMixins from '../../mixins';

    export default {
        mixins: [MyMixins],
        props: [
            'row',
            'xprops',
        ],
        data() {
            return {
                // Code...
            }
        },
        created() {
        },
        methods: {
            getClassName(row) {
                if (row.amount < 0) {
                    return 'text-danger'
                } else if (row.amount > 0) {
                    return 'text-success';
                } else {
                    if (this.$gate.user.role_id === this.$roles.CUSTOMER) {
                        return 'text-danger';
                    } else {
                        return 'text-success';
                    }
                }
            },
        }
    }
</script>


<style scoped>

</style>
