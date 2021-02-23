<template>
    <div class="btn-group btn-group-sm d-flex align-items-center">
        <p-check @change="checkAdvisor" :disabled="row.balance === '$0.00'" :value="row.id" name="checkbox" color="info">
        </p-check>
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
                // Code...
            }
        },
        created() {
        },
        methods: {
            checkAdvisor(e) {
                if (e) {
                    this.$store.dispatch(
                        this.xprops.module + '/setCheckedAdvisor', this.row.id
                    );
                } else {
                    this.$store.dispatch(
                        this.xprops.module + '/setUncheckedAdvisor', this.row.id
                    );
                }
            }
        }
    }
</script>


<style scoped>

</style>
