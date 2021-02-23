<template>
    <modal name="chat-details-modal" @before-open="beforeModalOpen" :scrollable="true" :adaptive="false" width="350" height="auto">
        <div class="modal-header">
            <div class="title">
                Chat Details
            </div>
            <div>
                <button @click="$modal.hide('chat-details-modal')" class="btn btn-link btn-close">
                    <i class="icomoon icomoon-cross"></i>
                </button>
            </div>
        </div>
        <div class="modal-body">
            <div class="mb-20">
                <strong>Session ID:</strong> {{ chatSession.id }}
            </div>

            <div class="mb-20">
                <strong>Start time:</strong> {{ chatSession.created_at }}
            </div>

            <div class="mb-20">
                <strong>End time:</strong> {{ chatSession.ended_at }}
            </div>

            <div class="mb-20">
                <strong>Session length:</strong> {{ getChatDuration(chatSession.created_at, chatSession.ended_at)}}
            </div>

            <div class="mb-20">
                <strong>Free time:</strong> {{ getDurationTime(chatSession.free_chat_duration) }}
            </div>

            <div class="mb-20">
                <strong>Paid time:</strong> {{ getDurationTime(chatSession.duration) }}
            </div>

            <div v-if="$gate.user.role_id === $roles.ADVISOR">
                <div class="mb-2">
                    <strong>Earnings:</strong>
                </div>

                <div class="row mb-2">
                    <div class="col-8">
                        Total revenue:
                    </div>
                    <div class="col-4 text-right">
                        ${{ toFixed(getTotalRevenue(), 2) }}
                    </div>
                </div>
                <div v-if="chatSession.discount" class="row mb-2">
                    <div class="col-8">
                        Discount ({{ chatSession.discount }}%):
                    </div>
                    <div class="col-4 text-right">
                        -${{ toFixed(getDiscountAmount(), 2) }}
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-8">
                        Commission ({{ chatSession.commission_percentage }}%)
                    </div>
                    <div class="col-4 text-right">
                        -${{ toFixed(getCommission(), 2) }}
                    </div>
                </div>
                <div v-if="chatSession.moneyback_amount !== 0" class="row mb-2">
                    <div class="col-8">
                        Inactive chat:
                    </div>
                    <div class="col-4 text-right">
                        -${{ toFixed(Math.abs(chatSession.moneyback_amount), 2) }}
                    </div>
                </div>
                <hr class="my-2">
                <div class="row mb-2">
                    <div class="col-8">
                        <strong>Your Earnings:</strong>
                    </div>
                    <div class="col-4 text-right">
                        <strong>${{ toFixed(getAdvisorRevenueWithMoneyback(), 2) }}</strong>
                    </div>
                </div>
            </div>

            <div v-if="$gate.user.role_id === $roles.CUSTOMER">
                <div class="mb-2">
                    <strong>Summary:</strong>
                </div>

                <div class="row mb-2">
                    <div class="col-8">
                        Total Cost:
                    </div>
                    <div class="col-4 text-right">
                        -${{ toFixed(getTotalRevenue(), 2) }}
                    </div>
                </div>
                <div v-if="chatSession.discount" class="row mb-2">
                    <div class="col-8">
                        Discount ({{ chatSession.discount }}%):
                    </div>
                    <div class="col-4 text-right">
                        -${{ toFixed(getDiscountAmount(), 2) }}
                    </div>
                </div>
                <div v-if="chatSession.moneyback_amount !== 0" class="row mb-2">
                    <div class="col-8">
                        Inactive Chat:
                    </div>
                    <div class="col-4 text-right">
                        +${{ toFixed(Math.abs(chatSession.moneyback_amount), 2) }}
                    </div>
                </div>
                <hr class="my-2">
                <div class="row mb-2">
                    <div class="col-8">
                        <strong>Total Spent:</strong>
                    </div>
                    <div class="col-4 text-right">
                        <strong>${{ toFixed(chatSession.customer_balance_before - chatSession.customer_balance_after - chatSession.moneyback_amount, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <router-link
                    :to="{ name: 'chat.chat.show', params: {chatSessionId: chatSession.id} }"
                    target="_blank"
                    class="btn btn-success"
            >
                Chat History
            </router-link>

            <button @click="$modal.hide('chat-details-modal')" type="button" class="btn btn-secondary">
                Cancel
            </button>
        </div>
    </modal>
</template>

<script>
    import MyMixins from '../../../mixins'

    export default {
        mixins: [MyMixins],
        data() {
            return {
                chatSession: {},
            }
        },
        created() {

        },
        methods: {
            beforeModalOpen(event) {
                this.chatSession = event.params.chatSession;
            },
            getChatDuration(startDate, endDate) {
                return this.getDurationTime( (moment(endDate)).diff(moment(startDate), 'seconds') );
            },
            getTotalRevenue() {
                const revenue = this.chatSession.customer_balance_before - this.chatSession.customer_balance_after;

                if (this.chatSession.discount) {
                    return revenue * 100 / (100 - this.chatSession.discount) ;
                }

                return revenue;
            },
            getDiscountAmount() {
                if (this.chatSession.discount) {
                    return this.getTotalRevenue() - (this.chatSession.customer_balance_before - this.chatSession.customer_balance_after);
                }

                return 0;
            },
            getCommission() {
                return (this.getTotalRevenue() - this.getDiscountAmount() - this.getAdvisorRevenue());
            },
            getAdvisorRevenue() {
                return (this.getTotalRevenue() - this.getDiscountAmount()) * (100 - this.chatSession.commission_percentage) / 100;
            },
            getAdvisorRevenueWithMoneyback() {
                return (this.getTotalRevenue() - this.getDiscountAmount()) * (100 - this.chatSession.commission_percentage) / 100 + +this.chatSession.moneyback_amount;
            },
            getDurationTime(duration) {
                let sec_num = parseInt(duration, 10);
                let hours   = Math.floor(sec_num / 3600);
                let minutes = Math.floor((sec_num - (hours * 3600)) / 60);
                let seconds = sec_num - (hours * 3600) - (minutes * 60);

                if (hours   < 10) {hours   = "0" + hours;}
                if (minutes < 10) {minutes = "0" + minutes;}
                if (seconds < 10) {seconds = "0" + seconds;}

                return hours + ':' + minutes + ':' + seconds;
            },
        }
    }
</script>

<style lang="scss" scoped>
    .become-section {
        background: #FDFDFD;
    }
</style>
