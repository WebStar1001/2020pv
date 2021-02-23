<template>
    <div>

    </div>
</template>


<script>
export default {
    data() {
        return {
            // Code...
        }
    },
    created() {
        this.$eventHub.$on('create-success', this.itemCreated);
        this.$eventHub.$on('update-success', this.itemUpdated);
        this.$eventHub.$on('delete-success', this.itemDeleted);
        this.$eventHub.$on('password-link-sent', this.passwordLinkSent);
        this.$eventHub.$on('password-updated', this.passwordUpdated);
        this.$eventHub.$on('customer-adding-funds', this.customerAddingFunds);
        this.$eventHub.$on('customer-added-funds', this.customerAddedFunds);
        this.$eventHub.$on('customer-cancelled-payment', this.customerCancelledPayment);
        this.$eventHub.$on('funds-added', this.fundsAdded);
        this.$eventHub.$on('message-required', this.messageRequired);
        this.$eventHub.$on('customer-registered', this.customerRegistered);
        this.$eventHub.$on('account-approved', this.accountApproved);
        this.$eventHub.$on('account-declined', this.accountDeclined);
        this.$eventHub.$on('incorrect-current-password', this.incorrentCurrentPassword);
        this.$eventHub.$on('account-deleted', this.accountDeleted);
        this.$eventHub.$on('payout-created', this.payoutCreated);
        this.$eventHub.$on('payout-cancelled', this.payoutCancelled);
        this.$eventHub.$on('rating-required', this.ratingRequired);
        this.$eventHub.$on('review-created', this.reviewCreated);
        this.$eventHub.$on('customer-is-out-of-money', this.customerIsOutOfMoney);
        this.$eventHub.$on('user-busy', this.userBusy);
        this.$eventHub.$on('comment-required', this.commentRequired);
        this.$eventHub.$on('report-created', this.reportCreated);
        this.$eventHub.$on('newsletter-submitted', this.newsletterSubmitted);
        this.$eventHub.$on('test-email-submitted', this.testEmailSubmitted);
        this.$eventHub.$on('insufficient-funds', this.insufficientFunds);
    },
    methods: {
        itemCreated() {
            this.$awn.success('Item has been successfully saved.')
        },
        itemUpdated() {
            this.$awn.success('Item has been successfully updated.')
        },
        itemDeleted() {
            this.$awn.success('Item has been successfully deleted.')
        },
        passwordLinkSent() {
            this.$awn.success('Reset password link has been successfully sent')
        },
        passwordUpdated() {
            this.$awn.success('New password has been successfully set')
        },
        customerAddingFunds(display_name) {
            this.$awn.info('Please wait. ' + display_name + ' is adding funds')
        },
        customerAddedFunds(display_name, amount) {
            this.$awn.info(display_name + ' added <strong>$' + amount + '</strong>')
        },
        customerCancelledPayment(display_name) {
            this.$awn.info(display_name + ' cancelled payment')
        },
        fundsAdded(amount) {
            this.$awn.success('Congrats! You have added <strong>$' + amount + '</strong> into your account.')
        },
        messageRequired() {
            this.$awn.alert('Message is required')
        },
        customerRegistered() {
            this.$awn.success('You have been successfully registered')
        },
        accountApproved() {
            this.$awn.success('Account is approved. An email with "Set Password" has been sent to user')
        },
        accountDeclined() {
            this.$awn.success('Account is declined. An email with decline reason has been sent to user')
        },
        incorrentCurrentPassword() {
            this.$awn.alert('Current Password is invalid')
        },
        accountDeleted() {
            this.$awn.success('Account is deleted')
        },
        payoutCreated() {
            this.$awn.success('Payout has been successfully created')
        },
        payoutCancelled() {
            this.$awn.success('Payout has been successfully cancelled')
        },
        ratingRequired() {
            this.$awn.alert('Rating is required')
        },
        reviewCreated() {
            this.$awn.success('Thank you for your review')
        },
        customerIsOutOfMoney(display_name) {
            this.$awn.info(display_name + ' is out of funds')
        },
        userBusy() {
            this.$awn.alert('User is busy')
        },
        commentRequired() {
            this.$awn.alert('Comment is required')
        },
        reportCreated() {
            this.$awn.success('Thank you for your report')
        },
        newsletterSubmitted() {
            this.$awn.success('Newsletter has been successfully created and will be sent in a few minutes')
        },
        testEmailSubmitted() {
            this.$awn.success('Test Email has been successfully submitted')
        },
        insufficientFunds() {
            this.$awn.warning('Insufficient funds in your account. Please add some funds')
        }
    }
}
</script>


<style scoped>

</style>
