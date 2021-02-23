<template>
    <div :class="$store.getters.layout">
        <event-hub></event-hub>
        <component v-bind:is="$store.getters.layout" :error="error"></component>

        <div v-if="$gate && $gate.user.role_id === $roles.ADVISOR" class="status-control-fixed d-none d-md-block">
            <div class="status-control" :class="$store.getters.user.status">
                <v-select
                        :options="['online', 'offline']"
                        :value="$store.getters.user.status"
                        @input="changeUserStatus"
                        :disabled="$store.getters.user.status === 'busy'"
                        :clearable="false"
                        :searchable="false"
                        append-to-body :calculate-position="withPopper"
                        label="label"
                        style="width: 90px"
                        inputId="status"
                >
                </v-select>
            </div>
        </div>

        <modal name="incoming-call-modal" :clickToClose="false" @before-close="beforeIncomingCallModalClose"
               :scrollable="true" :adaptive="true" height="auto" width="400" class="incoming-call-modal">
            <div class="modal-body text-center">
                <button @click="cancelIncomingCall()" :disabled="callAcceptedOrCancelled"
                        class="btn btn-link btn-close">
                    <i class="icomoon icomoon-cross"></i>
                </button>
                <div class="countdown mb-2">
                    <countdown
                            :startSeconds="incomingCallCountdownSeconds"
                            :startCountdown="true"
                            :timeFormat="true"
                            @onCountdownEnded="$modal.hide('incoming-call-modal')"
                            ref="incomingCallCountdown"
                    ></countdown>
                </div>
                <div class="avatar mb-3" :style="'background-image: url(' + customer.avatar + ')'"></div>
                <div class="description mb-1">
                    <strong>{{ customer.display_name }}</strong> wants to chat with you
                </div>
                <div class="price">${{ toFixed(getPricePerMinuteWithDiscount() / 100, 2) }}/min</div>
            </div>
            <div class="modal-footer">
                <button @click="acceptIncomingCall()" :disabled="callAcceptedOrCancelled" class="btn btn-success">
                    Accept
                </button>
                <button @click="cancelIncomingCall()" :disabled="callAcceptedOrCancelled" class="btn btn-secondary">
                    Reject
                </button>
            </div>
        </modal>

        <modal name="active-chat-modal" :scrollable="true" :adaptive="true" height="auto" width=500>
            <div class="modal-header">
                <div class="title">
                    You have an active Chat Session
                </div>
                <div>
                    <button @click="$modal.hide('active-chat-modal')" class="btn btn-link btn-close">
                        <i class="icomoon icomoon-cross"></i>
                    </button>
                </div>
            </div>
            <div v-if="$store.getters.activeChatSession" class="modal-footer">
                <router-link
                        :to="{name: 'chat.chat.show', params: { chatSessionId: $store.getters.activeChatSession.id } }"
                        class="btn btn-success"
                >
                    Go back to Chat
                </router-link>

                <button @click="$modal.hide('active-chat-modal')" type="button" class="btn btn-secondary">
                    Cancel
                </button>
            </div>
        </modal>

        <modal name="start-chat-modal" @before-open="beforeStartChatModalOpen"
               :scrollable="true" :adaptive="true" height="auto" width="500" class="start-chat-modal">
            <div class="modal-header">
                <div class="title">
                    You are connecting
                </div>
                <div>
                    <button @click="$modal.hide('start-chat-modal')" class="btn btn-link btn-close">
                        <i class="icomoon icomoon-cross"></i>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar" :style="'background-image: url(' + advisor.avatar + ')'"></div>
                    <div>
                        <div class="display-name mb-3">{{ advisor.display_name }}</div>
                        <div class="fee-label">Fee/minute</div>
                        <div class="row">
                            <div class="price" v-if="discount"><p class="font-weight-light"><s>${{
                                toFixed(advisor.price_per_minute / 100,
                                2)}}</s></p>
                            </div>
                            <div class="price">${{ toFixed(getPricePerMinuteWithDiscount() / 100, 2) }}</div>
                        </div>
                    </div>
                </div>
                <div class="description">
                    <div v-if="freeSeconds !== null">You have fund worth <strong>{{ getAvalilableMinutes() }}</strong>
                        with this psychic
                    </div>

                    <div v-if="$gate && $store.getters.user.balance + freeSeconds <= 0">
                        <div class="title mb-1 mt-3">Add funds</div>
                        <div class="description mb-2">
                            Low balance! Please add funds to use this offer. Funds left will be waiting for your next
                            sessions or can be redeemed later.
                        </div>

                        <div class="mb-3">
                            <ValidationObserver v-slot="{ handleSubmit }">
                                <form @submit.prevent="handleSubmit(submitPayForm)">
                                    <div>
                                        <div class="form-group">
                                            <select @change="updatePayAmount" :disabled="loading" class="form-control"
                                                    style="height: 44px">
                                                <option value="10">$10 / {{ getMinutesByPrice(10) }} minutes</option>
                                                <option value="25">$25 / {{ getMinutesByPrice(25) }} minutes</option>
                                                <option value="50">$50 / {{ getMinutesByPrice(50) }} minutes</option>
                                                <option value="100">$100 / {{ getMinutesByPrice(100) }} minutes</option>
                                                <option value="custom">Custom</option>
                                            </select>
                                        </div>

                                        <div v-if="showCustomField">
                                            <ValidationProvider name="Amount" rules="required|min_value:10|integer"
                                                                v-slot="{ errors }">
                                                <div class="form-group">
                                                    <label for="custom-amount">Amount</label>
                                                    <input
                                                            :value="payAmount"
                                                            @input="updateCustomPayAmount"
                                                            type="number"
                                                            :disabled="loading"
                                                            id="custom-amount"
                                                            class="form-control"
                                                            :class="{'is-invalid': errors[0] }"
                                                    >
                                                    <div class="invalid-feedback">{{ errors[0] }}</div>
                                                </div>
                                            </ValidationProvider>
                                        </div>
                                    </div>

                                    <vue-button-spinner
                                            class="btn btn-primary"
                                            :isLoading="loading"
                                            :disabled="loading"
                                    >
                                        Add&nbsp;More&nbsp;Minutes
                                    </vue-button-spinner>
                                </form>
                            </ValidationObserver>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button
                        v-if="$gate"
                        @click="chat(advisor)"
                        :disabled="loading || $store.getters.user.status === 'busy' || $store.getters.user.balance + freeSeconds <= 0"
                        class="btn btn-success"
                >
                    Start Chat Session
                </button>

                <button @click="$modal.hide('start-chat-modal')" class="btn btn-secondary">Cancel</button>
            </div>
        </modal>

        <modal name="outgoing-call-modal" @before-open="beforeOutgoingCallModalOpen"
               @before-close="beforeOutgoingCallModalClose" :clickToClose="false"
               :scrollable="true" :adaptive="true" height="auto" width="500" class="outgoing-call-modal"
        >
            <div class="modal-header">
                <div class="title">Connecting to...</div>
                <div>
                    <button @click="cancelCall()" :disabled="loading" class="btn btn-link btn-close">
                        <i class="icomoon icomoon-cross"></i>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column justify-content-center text-center h-100">
                    <div style="display: none">
                        <countdown
                                :startSeconds="outgoingCallCountdownSeconds"
                                :startCountdown="true"
                                :timeFormat="true"
                                @onCountdownEnded="outgoingCallEnded"
                                ref="countdown"
                        ></countdown>
                    </div>

                    <div class="avatar mb-4" :style="'background-image: url(' + advisor.avatar + ')'"></div>

                    <div class="display-name mb-1">{{ advisor.display_name }}</div>

                    <div class="price mb-4">${{ toFixed(getPricePerMinuteWithDiscount() / 100, 2) }}/min</div>

                    <div class="text-center mb-2">
                        This might take few moments
                    </div>
                </div>
            </div>
        </modal>

        <audio ref="soundRinging" preload="auto" style="display: none">
            <source src="/sounds/ringing.ogg" type="audio/ogg">
            <source src="/sounds/ringing.webm" type="audio/webm">
            <source src="/sounds/ringing.mp3" type="audio/mp3">
        </audio>
    </div>
</template>

<script>
    import MyMixins from '../../mixins'
    import {createPopper} from '@popperjs/core';

    export default {
        mixins: [MyMixins],
        props: ['error'],
        data() {
            return {
                loading: false,
                callAcceptedOrCancelled: false,
                customer: {},
                advisor: {},
                incomingCallCountdownSeconds: 0,
                outgoingCallCountdownSeconds: 180, // 180
                payAmount: 10,
                showCustomField: false,
                modalPaidAmount: null,
                freeSeconds: 0,
                outgoingCallModalOpened: false,
                soundRingingInterval: null,
                placement: 'top',
                discount: 0
            }
        },
        created() {
            if (!this.isUsedDiscount()) {
                this.discount = this.$store.getters.discount;
            }

            this.checkActiveChat();

            this.checkActiveCall();

            if (this.$gate) {
                Echo.private('chat')
                    .listen('CallIncoming', (e) => {
                        if (e.advisor.id === this.$gate.user.id) {
                            // show incoming call modal only if advisor online and not already has incoming call
                            if (this.$store.getters.user.status === 'online' || e.customer.id === this.customer.id) {
                                this.customer = e.customer;
                                this.advisor = e.advisor;

                                if (!this.incomingCallCountdownSeconds) {
                                    this.incomingCallCountdownSeconds = e.countdown_seconds;
                                }

                                this.$store.commit('setUserStatus', 'busy');
                                this.callAcceptedOrCancelled = false;
                                this.$modal.show('incoming-call-modal');

                                this.$refs.soundRinging.play();

                                this.soundRingingInterval = setInterval(function () {
                                    this.$refs.soundRinging.play()
                                }.bind(this), 2000);
                            } else {
                                if (this.$store.getters.busyWithUserId !== e.customer.id) {
                                    // trigger event that advisor is busy
                                    axios.post('/chat/busy', {
                                        customer_id: e.customer.id,
                                        advisor_id: e.advisor.id
                                    }).then(resp => {

                                    });
                                } else {
                                    this.customer = e.customer;
                                    this.advisor = e.advisor;

                                    if (!this.incomingCallCountdownSeconds) {
                                        this.incomingCallCountdownSeconds = e.countdown_seconds;
                                    }

                                    this.$store.commit('setUserStatus', 'busy');
                                    this.$modal.show('incoming-call-modal');

                                    this.$refs.soundRinging.play();
                                }
                            }
                        }
                    })
                    .listen('CallCancelled', (e) => {
                        // advisor rejected call
                        if (e.customer.id === this.$gate.user.id) {
                            this.$modal.hide('outgoing-call-modal');
                            this.$store.commit('setUserStatus', 'online');

                            this.$swal({
                                title: e.advisor.display_name + ' rejected call',
                                type: 'warning',
                            }).then(result => {
                            })
                        }

                        // customer cancelled call
                        if (e.advisor.id === this.$gate.user.id) {
                            this.$modal.hide('incoming-call-modal');

                            if (e.advisor_not_answering) {
                                this.$store.commit('setUserStatus', 'offline');
                            } else {
                                this.$store.commit('setUserStatus', 'online');
                            }
                        }
                    })
                    .listen('AdvisorIsBusy', (e) => {
                        if (e.customer.id === this.$gate.user.id) {
                            this.$modal.hide('outgoing-call-modal');
                            this.$store.commit('setUserStatus', 'online');

                            this.$swal({
                                title: e.advisor.display_name + ' is busy',
                                type: 'warning',
                            }).then(result => {
                                this.$modal.hide('outgoing-call-modal');
                            })
                        }
                    })
                    .listen('CallAccepted', (e) => {
                        if (e.customer.id === this.$gate.user.id) {
                            if (this.outgoingCallModalOpened) {
                                this.$modal.hide('outgoing-call-modal');
                                window.location.href = '/admin/chat/' + e.chat_session_id
                            }
                        }

                        if (e.advisor.id === this.$gate.user.id) {
                            this.$modal.hide('incoming-call-modal');
                        }
                    });

                if (this.$store.getters.activeChatSession) {
                    Echo.private(`chat.${this.$store.getters.activeChatSession.id}`)
                        .listen('CallEnded', (e) => {
                            this.$modal.hide('active-chat-modal');

                            // set advisor online after cron ends chat session
                            if (e.user.id === this.$gate.user.id) {
                                this.$store.commit('setUserStatus', 'online');
                            }

                            if (this.$store.getters.activeChatSession && e.chat_session_id === this.$store.getters.activeChatSession.id) {
                                this.$store.commit('setUserStatus', 'online');
                            }
                        });
                }

                Echo.private(`App.User.${this.$gate.user.id}`)
                    .listen('BalanceUpdated', (e) => {
                        if (e.user.id === this.$gate.user.id) {
                            this.$store.commit('setUserBalance', e.user.balance);
                        }
                    })
                    .listen('DiscountUsed', (e) => {
                        if (e.customer_id === this.$gate.user.id) {
                            this.$store.commit('setDiscountHistory', e.advisor_id);
                        }
                    });

                setInterval(this.updateActivity, 5000);
            }
        },
        watch: {
            "$route": function () {
                this.checkActiveChat();

                this.checkActiveCall();
            }
        },
        methods: {
            checkActiveChat() {
                const chatSessionTimestamp = localStorage.getItem('chatSessionActivity');

                if (this.$store.getters.activeChatSession && this.$route.name !== 'chat.chat.show' && new Date() - chatSessionTimestamp > 4000) {
                    setTimeout(function () {
                        this.$modal.show('active-chat-modal')
                    }.bind(this), 100);
                }
            },
            checkActiveCall() {
                if (this.$store.getters.activeCall) {
                    this.customer = this.$store.getters.activeCall.customer;
                    this.advisor = this.$store.getters.activeCall.advisor;

                    if (this.$gate.user.role_id === this.$roles.CUSTOMER) {
                        this.outgoingCallCountdownSeconds = this.$store.getters.activeCall.seconds_remaining;

                        setTimeout(function () {
                            this.$modal.show('outgoing-call-modal', {advisor: this.$store.getters.activeCall.advisor});
                        }.bind(this), 100);
                    }

                    if (this.$gate.user.role_id === this.$roles.ADVISOR) {
                        this.incomingCallCountdownSeconds = this.$store.getters.activeCall.seconds_remaining;

                        setTimeout(function () {
                            this.$modal.show('incoming-call-modal');
                        }.bind(this), 100);
                    }
                }
            },
            changeUserStatus(e) {
                this.$store.dispatch('setUserStatus', e.target.value);
            },
            acceptIncomingCall() {
                this.callAcceptedOrCancelled = true;
                this.$store.commit('setUserStatus', 'busy');

                axios.post('/chat/accept', {
                    customer_id: this.customer.id,
                    advisor_id: this.advisor.id,
                }).then(resp => {
                    window.location.href = '/admin/chat/' + resp.data
                }).catch(error => {
                    this.$store.commit('setUserStatus', 'online');
                });
            },
            cancelIncomingCall() {
                this.callAcceptedOrCancelled = true;

                axios.post('/chat/cancel', {
                    customer_id: this.customer.id,
                    advisor_id: this.advisor.id
                }).then(resp => {
                    this.$modal.hide('incoming-call-modal');
                    this.$store.commit('setUserStatus', 'online');
                }).catch(error => {
                    this.$store.commit('setUserStatus', 'online');
                });
            },
            beforeIncomingCallModalClose() {
                this.customer = [];
                this.advisor = [];
                this.$store.commit('setActiveCall', null);
                clearInterval(this.soundRingingInterval);

                if (this.$refs.incomingCallCountdown.secondsLeft > 0) {
                    this.$store.commit('setUserStatus', 'online');
                } else {
                    this.$store.commit('setUserStatus', 'offline');
                }
            },
            beforeStartChatModalOpen(event) {
                this.advisor = event.params.advisor;
                this.freeSeconds = event.params.freeSeconds;
                this.modalPaidAmount = event.params.modalPaidAmount;
            },
            beforeOutgoingCallModalOpen(event) {
                this.outgoingCallModalOpened = true;
                this.advisor = event.params.advisor;
            },
            beforeOutgoingCallModalClose() {
                this.outgoingCallModalOpened = false;
                this.$store.commit('setActiveCall', null);
            },
            getMinutesByPrice(amount) {
                return Math.floor(amount / (this.getPricePerMinuteWithDiscount() / 100));
            },
            getAvalilableMinutes() {
                if (this.$gate) {
                    return this.getAvailableTime((this.$store.getters.user.balance / (this.getPricePerMinuteWithDiscount() / 100) + this.freeSeconds / 60) * 60);
                }
            },
            getAvailableTime(duration) {
                let sec_num = parseInt(duration, 10);
                let hours = Math.floor(sec_num / 3600);
                let minutes = Math.floor((sec_num - (hours * 3600)) / 60);
                let seconds = sec_num - (hours * 3600) - (minutes * 60);

                if (hours > 0) {
                    return hours + ' hours ' + minutes + ' min ' + seconds + ' sec';
                }

                if (minutes > 0) {
                    return minutes + ' min ' + seconds + ' sec';
                }

                return seconds + ' sec';
            },
            outgoingCallEnded() {
                this.cancelCall(true);
                this.$swal({
                    title: this.advisor.display_name + ' is not answering',
                    type: 'warning',
                }).then(result => {
                });
            },
            cancelCall(advisorNotAnswering = false) {
                this.cancelOutgoingCall({
                    customer_id: this.$gate.user.id,
                    advisor_id: this.advisor.id,
                    advisor_not_answering: advisorNotAnswering
                }).then(() => {
                    this.$modal.hide('outgoing-call-modal');
                    this.$store.commit('setUserStatus', 'online');
                }).catch((error) => {
                    this.$store.commit('setUserStatus', 'online');
                });
            },
            chat(advisor) {
                if (this.$store.getters.user.balance + this.freeSeconds > 0) {
                    this.$store.commit('setUserStatus', 'busy');

                    this.startChat({
                        advisor_id: advisor.id,
                        secondsLeft: this.outgoingCallCountdownSeconds,
                        firstRequest: true,
                    }).then(() => {
                        this.$modal.hide('start-chat-modal');
                        this.$modal.show('outgoing-call-modal', {advisor: advisor});
                    }).catch((error) => {
                        this.$store.commit('setUserStatus', 'online');
                    });
                }
            },
            startChat(data) {
                return new Promise((resolve, reject) => {
                    axios.post('/chat/' + data.advisor_id + '/start', {
                        countdown_seconds: data.secondsLeft
                    })
                        .then(response => {
                            resolve()
                        })
                })
            },
            cancelOutgoingCall(data) {
                this.loading = true;

                return new Promise((resolve, reject) => {
                    axios.post('/chat/cancel', {
                        customer_id: data.customer_id,
                        advisor_id: data.advisor_id,
                        advisor_not_answering: data.advisor_not_answering
                    })
                        .then(response => {
                            resolve()
                        })
                        .finally(() => {
                            this.loading = false;
                        })
                })
            },
            pay(advisor_id) {
                this.loading = true;

                axios.post('/payment', {
                    amount: this.payAmount,
                    redirect_url: '/profile/' + advisor_id
                }).then(resp => {
                    if (resp.data) {
                        window.location = resp.data;
                    } else {
                        console.log('Something went wrong!');
                    }
                })
                    .finally(() => {
                        this.loading = false;
                    })
            },
            updateActivity() {
                axios.post('/user/activity');
            },
            changeUserStatus(e) {
                if (e !== 'busy') {
                    this.$store.dispatch('setUserStatus', e);
                }
            },
            withPopper(dropdownList, component, {width}) {
                /**
                 * We need to explicitly define the dropdown width since
                 * it is usually inherited from the parent with CSS.
                 */
                dropdownList.style.width = width;

                /**
                 * Here we position the dropdownList relative to the $refs.toggle Element.
                 *
                 * The 'offset' modifier aligns the dropdown so that the $refs.toggle and
                 * the dropdownList overlap by 1 pixel.
                 *
                 * The 'toggleClass' modifier adds a 'drop-up' class to the Vue Select
                 * wrapper so that we can set some styles for when the dropdown is placed
                 * above.
                 */
                const popper = createPopper(component.$refs.toggle, dropdownList, {
                    placement: this.placement,
                    modifiers: [
                        {
                            name: 'offset', options: {
                                offset: [0, -1]
                            }
                        },
                        {
                            name: 'toggleClass',
                            enabled: true,
                            phase: 'write',
                            fn({state}) {
                                component.$el.classList.toggle('drop-up', state.placement === 'top')
                            },
                        }]
                });

                /**
                 * To prevent memory leaks Popper needs to be destroyed.
                 * If you return function, it will be called just before dropdown is removed from DOM.
                 */
                return () => popper.destroy();
            },
            updatePayAmount(e) {
                if (e.target.value === 'custom') {
                    this.showCustomField = true;
                    this.payAmount = null;
                } else {
                    this.showCustomField = false;
                    this.payAmount = e.target.value
                }
            },
            updateCustomPayAmount(e) {
                this.payAmount = e.target.value
            },
            submitPayForm() {
                this.pay(this.advisor.slug);
            },
            getPricePerMinuteWithDiscount() {
                if (this.discount && !this.isUsedDiscount()) {
                    return this.advisor.price_per_minute - (this.advisor.price_per_minute / 100) * this.discount;
                }

                return this.advisor.price_per_minute;
            },
            isUsedDiscount() {
                let used_discount = false;

                for (let advisor_id of this.$store.getters.discountHistory) {
                    if (this.advisor.id === advisor_id) {
                        used_discount = true;
                    }
                }

                return used_discount;
            }
        }
    }
</script>

<style lang="scss">
    .start-chat-modal {
        .avatar {
            width: 110px;
            height: 110px;
            background-size: cover !important;
            background-position: center center;
            margin-right: 20px;
            border-radius: 50%;
        }

        .display-name {
            font-size: 1.25rem;
        }

        .fee-label {
            font-size: 12px;
        }

        .price {
            color: #00bff0;
            font-size: 20px;
            font-weight: 700;
        }
    }

    .outgoing-call-modal {
        .avatar {
            display: table;
            margin: 0 auto;
            width: 110px;
            height: 110px;
            background-size: cover !important;
            background-position: center center !important;
            border-radius: 50%;
        }

        .display-name {
            font-size: 1.25rem;
        }

        .price {
            color: #00bff0;
            font-size: 20px;
            font-weight: 700;
        }
    }

    .incoming-call-modal {
        .modal-body {
            position: relative;
        }

        .avatar {
            display: table;
            margin: 0 auto;
            width: 110px;
            height: 110px;
            background-size: cover !important;
            background-position: center center !important;
            border-radius: 50%;
        }

        .price {
            color: #00bff0;
            font-size: 20px;
            font-weight: 700;
        }

        .countdown {
            display: table;
            margin: 0 auto;
            font-size: 30px;
            color: #dc3545;
            width: 130px;
            text-align: left;
        }

        .btn-close {
            position: absolute;
            right: 6px;
            top: 10px;
            color: #bcc6d1;
            font-size: 18px;
            text-decoration: none;

            &:hover {
                color: #9babbb;
            }
        }
    }
</style>