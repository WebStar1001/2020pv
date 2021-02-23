<template>
    <div class="btn-group btn-group-sm" style="display:flex;">
        <button @click="approveEmail(row.user_id)"
                type="button"
                class="btn btn-success"
        >
            Approve
        </button>

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
        approveEmail(id) {
            this.$store.dispatch(
                this.xprops.module + '/approveEmail', id
            ).then(result => {
                this.$eventHub.$emit('update-success');

                this.$store.dispatch(
                    this.xprops.module + '/fetchData'
                ).then(result => {

                });
            });
        }
    }
}
</script>


<style scoped>

</style>
