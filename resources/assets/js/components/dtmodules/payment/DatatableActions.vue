<template>
    <div class="btn-group btn-group-sm" style="display:flex;">
        <button @click="showInvoice(row.id)" :disabled="loading" type="button" class="btn btn-default">
            Show&nbsp;Invoice
        </button>
   </div>
</template>


<script>
    import MyMixins from '../../../mixins';

    export default {
        mixins: [MyMixins],
        props: [
            'row',
            'xprops',
        ],
        data() {
            return {
                loading: false,
                invoice: {}
            }
        },
        created() {
        },
        methods: {
            showInvoice(id) {
                this.loading = true;

                axios.get('/payments/' + id + '/invoice')
                    .then(response => {
                        this.invoice = response.data;
                        this.invoice.amount = this.toFixed(parseFloat(this.invoice.amount), 2);

                        this.$modal.show('invoice-modal', {invoice: this.invoice});
                    }).finally(() => {
                        this.loading = false;
                    });
            },
        }
    }
</script>


<style scoped>

</style>
