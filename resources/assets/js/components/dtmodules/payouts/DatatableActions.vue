<template>
    <div class="btn-group btn-group-sm d-flex align-items-center">
        <button @click="makePayout()"
                v-if="row.paypal_payment"
                :disabled="row.balance === '$0.00'"
                type="button"
                class="btn btn-success m-1"
        >
            Make&nbsp;Payout
        </button>
        <button @click="bankTransferred()"
                v-if="!row.paypal_payment"
                :disabled="row.balance === '$0.00'"
                type="button"
                class="btn btn-success m-1"
        >
            Bank&nbsp;Transferred
        </button>
        <button @click="$modal.show('payouts-modal', {row: row})"
                type="button"
                class="btn btn-default m-1"
        >
            Payouts
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
            makePayout() {
                this.$swal({
                    title: 'Are you sure you want to make payout to "' + this.row.display_name + '"',
                    text: '',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                    confirmButtonColor: '#dd4b39',
                    focusCancel: true,
                }).then(result => {
                    if (result.value) {
                        this.$store.dispatch(
                            this.xprops.module + '/makePayouts', [this.row.id]
                        ).then(result => {
                            this.$eventHub.$emit('payout-created')
                        })
                    }
                })
            },
            bankTransferred() {
                this.$swal({
                    title: 'Are you sure?',
                    text: '',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                    confirmButtonColor: '#dd4b39',
                    focusCancel: true,
                }).then(result => {
                    if (result.value) {
                        this.$store.dispatch(
                            this.xprops.module + '/makePayouts', [this.row.id]
                        ).then(result => {
                            this.$eventHub.$emit('payout-created')
                        })
                    }
                })
            }
        },
    }
</script>


<style scoped>

</style>
