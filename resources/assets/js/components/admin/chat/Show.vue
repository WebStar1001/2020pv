<template>
    <div class="container-fluid">
        <div class="row py-2 align-items-center">
            <div class="col-6">
                <router-link :to="{ name: 'admin.dashboard.index' }">
                    <img src="/images/logo.png" height="67" alt="" class="logo img-fluid">
                </router-link>
            </div>
            <div class="col-6 text-right">
                <a v-if="$gate.user.role_id === $roles.SUPERADMIN || $gate.user.role_id === $roles.ADMIN || loggedAsUser" :href="'/chat/'+$route.params.chatSessionId+'/messages/pdf'" target="_blank" class="btn btn-success mr-2">
                    <i class="fa fa-download mr-2"></i>
                    Download History
                </a>
                <a v-if="activeSession" @click.prevent="endSession()" href="#" class="text-danger">
                    <img src="/images/admin/session-end.png" width="27">
                    End Session
                </a>
                <router-link v-if="!activeSession" :to="{ name: 'admin.dashboard.index' }">
                    <i class="icomoon icomoon-dashboard mr-2 align-middle"></i> Go to Dashboard
                </router-link>
            </div>
        </div>

        <section v-if="!duplicatedChat">
            <div class="row flex-column flex-md-row chat-row">
                <div class="col-md-4 col-12 text-center">
                    <div class="pb-0 pt-4 pb-md-4 px-3 bg-white h-100 left-col">
                        <div v-if="$gate.user.role_id !== $roles.ADVISOR && customer">

                            <div v-show="chatFreeSeconds" class="mb-0 mb-md-4">
                                <div v-if="activeSession" class="timer-title mb-2">
                                    Free Minutes
                                </div>

                                <circular-count-down-timer
                                        v-show="activeSession"
                                        :initial-value="chatFreeSeconds"
                                        :stroke-width="timerStrokeWidth"
                                        :size="timerSize"
                                        :padding="4"
                                        :hour-label="timerHourLabel"
                                        :minute-label="timerMinuteLabel"
                                        :second-label="timerSecondLabel"
                                        :show-second="true"
                                        :show-minute="true"
                                        :show-hour="true"
                                        :show-negatives="false"
                                        :paused="!chatStarted"
                                        @finish="freeSecondsIsOver"
                                        class="countdown"
                                        ref="freeCountdown"
                                ></circular-count-down-timer>

                            </div>

                            <div v-show="!chatFreeSeconds" class="mb-0 mb-md-4">
                                <div v-if="activeSession" class="timer-title mb-2 d-none d-md-block">
                                    Time Remaining
                                </div>

                                <circular-count-down-timer
                                        v-show="activeSession"
                                        :initial-value="chatPaidSeconds"
                                        :stroke-width="timerStrokeWidth"
                                        :size="timerSize"
                                        :padding="4"
                                        :hour-label="timerHourLabel"
                                        :minute-label="timerMinuteLabel"
                                        :second-label="timerSecondLabel"
                                        :show-second="true"
                                        :show-minute="true"
                                        :show-hour="true"
                                        :show-negatives="false"
                                        :paused="!chatStarted && !chatFreeSeconds"
                                        @finish="paidSecondsIsOver"
                                        class="countdown"
                                        ref="paidCountdown"
                                ></circular-count-down-timer>
                            </div>

                            <div class="d-none d-md-block">
                                <div class="mb-lg-4 mb-3">
                                    <div class="avatar" :style="'background: url(' + advisor.avatar + ')'"></div>
                                </div>

                                <div class="display_name">{{ advisor.display_name }}</div>
                                <div class="master-service">{{ advisor.master_service.title }}</div>

                                <div class="fee-title">Fee/minute</div>
                                <div class="price" v-if="discount"><s>${{ toFixed(advisor.price_per_minute / 100,
                                    2)}}</s></div>
                                <div class="price">${{ toFixed(pricePerMinute / 100, 2) }}</div>

                                <button class="btn btn-success btn-sm pl-3 pr-3 mt-3"
                                        style="height: 40px"
                                        v-if="!isBlockedAdvisor(advisor.id) && !activeSession && $gate.user.role_id !== $roles.SUPERADMIN && $gate.user.role_id !== $roles.ADMIN"
                                        @click="openStartChatModal(advisor)"
                                        :disabled="advisor.status !== 'online' || $store.getters.user.status === 'busy'"
                                >
                                    <img src="/images/profile-card/meassage.png" alt="">
                                    Start Chat Session
                                </button>
                            </div>
                        </div>

                        <div v-if="$gate.user.role_id === $roles.ADVISOR && advisor">
                            <div v-if="activeSession" class="timer-title d-none d-md-block">
                                Timer
                            </div>

                            <h3 class="stopwatch mb-0 mb-md-4">
                                <stopwatch
                                        v-if="activeSession"
                                        :startSeconds="duration"
                                        :startWatch="chatStarted"
                                ></stopwatch>
                            </h3>

                            <circular-count-down-timer
                                    v-show="false"
                                    :initial-value="chatFreeSeconds"
                                    :stroke-width="5"
                                    :size="70"
                                    :padding="4"
                                    :hour-label="'hours'"
                                    :minute-label="'minutes'"
                                    :second-label="'seconds'"
                                    :show-second="true"
                                    :show-minute="true"
                                    :show-hour="true"
                                    :show-negatives="false"
                                    :paused="!chatStarted"
                                    @finish="freeSecondsIsOver"
                                    class="countdown"
                                    ref="freeCountdown"
                            ></circular-count-down-timer>

                            <circular-count-down-timer
                                    v-show="false"
                                    :initial-value="chatPaidSeconds"
                                    :stroke-width="5"
                                    :size="70"
                                    :padding="4"
                                    :hour-label="'hours'"
                                    :minute-label="'minutes'"
                                    :second-label="'seconds'"
                                    :show-second="true"
                                    :show-minute="true"
                                    :show-hour="true"
                                    :show-negatives="false"
                                    :paused="!chatStarted && !chatFreeSeconds"
                                    @finish="paidSecondsIsOver"
                                    class="countdown"
                                    ref="paidCountdown"
                            ></circular-count-down-timer>

                            <div class="d-none d-md-block">
                                <div class="mb-lg-4 mb-3">
                                    <div class="avatar" :style="'background: url(' + customer.avatar + ')'"></div>
                                </div>

                                <div class="display_name">{{ customer.display_name }}</div>

                                <div class="fee-title">Fee/minute</div>
                                <div class="price">${{ toFixed(pricePerMinute / 100, 2) }}</div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-8 col-12">
                    <div class="right-col bg-white p-3 pt-2">
                        <div class="d-flex flex-column flex-lg-row align-items-center chat-header">
                            <div class="text-center mb-3 mb-lg-0 w-100">
                                <div v-if="activeSession">
                                    &nbsp;{{ getHeaderMessage() }}&nbsp;
                                </div>

                                <div v-if="activeSession === false" class="text-danger">
                                    {{ chatEndedMessage }}
                                </div>
                            </div>
                            <div class="d-flex chat-buttons">
                                <button
                                        v-if="$gate.user.role_id === $roles.CUSTOMER"
                                        @click="$modal.show('pay-modal')"
                                        type="button"
                                        class="btn btn-default d-flex align-items-center"
                                >
                                    <i class="fa fa-credit-card mr-2"></i>Add&nbsp;Funds
                                </button>

                                <router-link
                                        v-if="customer && $gate.user.role_id === $roles.ADVISOR"
                                        :to="{ name: 'admin.chat_history.index', params: {id: customer.id} }"
                                        class="btn btn-default d-flex align-items-center"
                                        target="_blank"
                                >
                                    <img src="/images/admin/history.png" class="mr-1">History
                                </router-link>
                            </div>
                        </div>

                        <div
                                class="chat-panel-body"
                                ref="chatContainer"
                                @mousedown="mouseDown = true"
                                @mouseup="mouseDown = false"
                        >
                            <div v-if="$gate.user.role_id === $roles.CUSTOMER">
                                <b-alert variant="warning" show dismissible>
                                    <strong>Important Safety Notice:</strong> If an advisor asks you to pay them off-site or asks for your credit card number or your contact information (e.g. your email, phone no.) please refuse and
                                    <a @click.prevent="$modal.show('report-modal')" href="#">report to us</a>.
                                </b-alert>
                            </div>

                            <div v-if="$gate.user.role_id === $roles.ADVISOR">
                                <b-alert variant="warning" show dismissible>
                                    <strong>Important Safety Notice:</strong> If you asks a customer to pay you off-site or asks the credit card number or the contact information (e.g. your email, phone no.) your account will be suspended permanently.
                                </b-alert>
                            </div>

                            <ul class="chat">
                                <li v-if="chatDeleted" class="text-danger">
                                    Chat history is deleted
                                </li>
                                <li v-for="message in messages">
                                    <div v-if="!isUserMessage(message.user)">
                                        <div class="d-flex">
                                            <div class="avatar" :style="'background: url(' + message.user.avatar + ')'"></div>
                                            <div>
                                                <div class="message" v-html="message.message"></div>
                                                <div class="time-ago">
                                                    <vue-moments-ago
                                                            prefix=""
                                                            suffix=" ago"
                                                            :date="getLocalDate(message.created_at)"
                                                    ></vue-moments-ago>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="isUserMessage(message.user)">
                                        <div class="d-flex justify-content-end">
                                            <div>
                                                <div class="message primary" v-html="message.message"></div>
                                                <div class="time-ago text-right">
                                                    <vue-moments-ago
                                                            prefix=""
                                                            suffix=" ago"
                                                            :date="getLocalDate(message.created_at)"
                                                    ></vue-moments-ago>
                                                </div>
                                            </div>
                                            <div class="avatar" :style="'background: url(' + message.user.avatar + ')'"></div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div v-if="activeSession" class="panel-footer">
                            <div style="font-style: italic; height: 18px; font-size: 12px">
                                <div v-show="typing">
                                   <span v-if="$gate.user.role_id === $roles.CUSTOMER">{{ advisor.display_name }}</span>
                                    <span v-else>{{ customer.display_name }}</span>
                                    is typing...
                                </div>
                            </div>
                            <div class="form-group d-flex mt-2 mb-0">
                                <div class="d-flex w-100 text-input-container">
                                    <input @input="updateNewMessage"
                                           :value="newMessage"
                                           :disabled="chatPaused"
                                           @keyup.enter="sendChatMessage()"
                                           @keydown="isTyping"
                                           type="text"
                                           class="form-control w-100"
                                           :class="{'has-error': errorMessage}"
                                           placeholder="Your message"
                                    >

                                    <button v-if="!fileLoading" @click="$refs.file.click()" type="button" class="btn btn-link btn-attachment">
                                        <i class="fa fa-link"></i>
                                    </button>
                                    <img v-if="fileLoading" src="/images/admin/spinner.svg" class="file-loading">
                                    <input @change="handleFileUpload()"
                                           type="file"
                                           accept="image/png, image/jpeg, image/gif"
                                           ref="file"
                                           style="display: none"
                                    >

                                    <button @click="sendChatMessage()" :disabled="chatPaused" class="btn btn-sent-message">
                                        <img src="/images/admin/chat-send.png">
                                    </button>
                                </div>
                            </div>

                            <div v-if="filePath" class="file-container">
                                <img :src="'/storage/' + filePath" height="50" alt="">
                                <div @click="removeFile()" class="remove-file">
                                    <i class="fa fa-times"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <modal name="pay-modal" @before-close="beforePayModalClose" :scrollable="true" :adaptive="true" width="500" height="auto">
                <div class="modal-header">
                    <div class="title">
                        Add Funds
                    </div>
                    <div>
                        <button @click="$modal.hide('pay-modal')" class="btn btn-link btn-close">
                            <i class="icomoon icomoon-cross"></i>
                        </button>
                    </div>
                </div>
                <ValidationObserver v-slot="{ handleSubmit }">
                    <form @submit.prevent="handleSubmit(pay)">
                        <div v-if="advisor" class="modal-body pay-modal">
                            <div class="form-group">
                                <label for="pay-amount">Funds</label>
                                <select @change="updatePayAmount" :disabled="loading" id="pay-amount" class="form-control">
                                    <option value="10">$10 / {{ getMinutesByPrice(10) }} minutes</option>
                                    <option value="25">$25 / {{ getMinutesByPrice(25) }} minutes</option>
                                    <option value="50">$50 / {{ getMinutesByPrice(50) }} minutes</option>
                                    <option value="100">$100 / {{ getMinutesByPrice(100) }} minutes</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>

                            <div v-if="showCustomField">
                                <ValidationProvider name="Amount" rules="required|min_value:10|integer" v-slot="{ errors }">
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
                        <div class="modal-footer">
                            <vue-button-spinner
                                    class="btn btn-success"
                                    :isLoading="loading"
                                    :disabled="loading"
                            >
                                Add More Minutes
                            </vue-button-spinner>

                            <button @click="$modal.hide('pay-modal')" type="button" class="btn btn-secondary">
                                Cancel
                            </button>
                        </div>
                    </form>
                </ValidationObserver>
            </modal>

            <modal name="review-modal" :clickToClose="false" :scrollable="true" :adaptive="true" width="500" height="auto">
                <div class="modal-header">
                    <div class="title">
                        How was your session?
                    </div>
                    <div>
                        <button @click="$modal.hide('review-modal')" class="btn btn-link btn-close">
                            <i class="icomoon icomoon-cross"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Rate your session</label>
                        <div style="font-size: 34px" class="d-table m-auto">
                            <vue-stars
                                    name="rating"
                                    :max="5"
                                    :value="rating"
                                    @input="updateRating"
                                    :readonly="false"
                            />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="review">Share your experience</label>
                        <textarea
                                class="form-control"
                                id="review"
                                name="review"
                                placeholder="Your message"
                                :value="reviewText"
                                @input="updateReviewText"
                                rows="5"
                        ></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button @click="submitReviewForm" :disabled="loading" type="button" class="btn btn-success">Submit</button>

                    <button @click="$modal.hide('review-modal')" type="button" class="btn btn-secondary">
                        Cancel
                    </button>
                </div>
            </modal>

            <modal name="report-modal" :scrollable="true" :adaptive="true" width="500" height="auto">
                <div class="modal-header">
                    <div class="title">
                        Report on <span v-if="advisor">{{ advisor.display_name }}</span>
                    </div>
                    <div>
                        <button @click="$modal.hide('report-modal')" class="btn btn-link btn-close">
                            <i class="icomoon icomoon-cross"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="report-comment">Comment</label>
                        <textarea
                                class="form-control"
                                id="report-comment"
                                name="report-comment"
                                placeholder="Comment"
                                :value="reportComment"
                                @input="updateReportComment"
                                rows="5"
                        ></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button @click="submitReportForm" :disabled="loading" type="button" class="btn btn-success">Submit</button>
                    <button @click="$modal.hide('report-modal')" type="button" class="btn btn-secondary">
                        Cancel
                    </button>
                </div>
            </modal>

            <audio ref="soundPing" preload="auto" style="display: none">
                <source src="/sounds/ping.ogg" type="audio/ogg">
                <source src="/sounds/ping.webm" type="audio/webm">
                <source src="/sounds/ping.mp3" type="audio/mp3">
            </audio>
        </section>

        <div class="disclaimer">
            Disclaimer: Psychics are not emploees of GoPsys.
        </div>

        <div v-if="duplicatedChat" class="alert alert-danger">
            Chat was already opened in another tab. You cannot open two chat instances.<br>
            If we are mistaken try to refresh the page
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import VueMomentsAgo from 'vue-moments-ago'
    import MyMixins from '../../../mixins'

    export default {
        components: {
            VueMomentsAgo
        },
        mixins: [MyMixins],
        data() {
            return {
                chatOpenedAt: moment.utc(),
                chatEndedMessage: 'Chat Session is ended',
                inactivityLimit: 660, // 660 seconds
                inactivityModalLimit: 600, // 600 seconds
                inactivityModalShowed: false,
                checkActivityIntervalId: null,
                fetchDataIntervalId: null,
                typing: false,
                typingDate: moment(),
                metyping: false,
                addingFunds: false,
                outOffFunds: false,
                mouseDown: false,
                duplicatedChat: false,
                errorMessage: false,
                insufficientFundsModalShown: false,
                customerOutOfMoneyShown: false,
                showCustomField: false,
                fileLoading: false,
                timerSize: 70,
                timerStrokeWidth: 5,
                timerHourLabel: 'hours',
                timerMinuteLabel: 'minutes',
                timerSecondLabel: 'seconds',
                loggedAsUser: false
            }
        },
        created() {
            this.loggedAsUser = this.$gate.user.role_id === this.$roles.SUPERADMIN ? false : sessionStorage.getItem('loggedAsUser');

            if (window.innerWidth < 768) {
                this.timerSize = 50;
                this.timerStrokeWidth = 3;
                this.timerMinuteLabel = 'mins';
                this.timerSecondLabel = 'secs';
            }

            this.$modal.hide('active-chat-modal');
            this.setChatSessionId(this.$route.params.chatSessionId);

            this.fetchData(1).then(response => {
                if (this.activeSession) {
                    // this.checkDuplicatedChat();

                    this.scrollToEnd();

                    this.checkActivityIntervalId = setInterval(this.checkActivity, 2000);

                    if (!this.chatFreeSeconds && !this.chatPaidSeconds) {
                        this.showInsufficientFundsModal();
                    }

                    Echo.private(`chat.${this.$route.params.chatSessionId}`)
                        .listen('ChatStarted', (e) => {
                            if (e.chat_session.id === +this.$route.params.chatSessionId) {
                                this.setChatStarted(true);

                                if (!this.chatFreeSeconds && this.$gate.user.role_id === this.$roles.CUSTOMER) {
                                    this.$refs.soundPing.play();
                                }
                            }
                        })
                        .listen('MessageSent', (e) => {
                            if (e.chat_session_id === +this.$route.params.chatSessionId) {
                                this.pushMessage({
                                    chat_session_id: e.chat_session_id,
                                    message: e.message.message,
                                    user: e.user,
                                    created_at: e.message.created_at
                                });

                                setTimeout(this.scrollToEnd, 10)
                            }
                        })
                        .listen('CallEnded', (e) => {
                            if (e.chat_session_id === +this.$route.params.chatSessionId) {
                                switch (e.reason) {
                                    case 'customer_inactivity':
                                        if (this.$gate.user.role_id === this.$roles.CUSTOMER) {
                                            this.$store.commit('setUserStatus', 'offline');
                                        } else {
                                            this.$store.commit('setUserStatus', 'online');
                                        }

                                        this.chatEndedMessage = 'Chat Session is ended due to customer inactivity';
                                        break;
                                    case 'advisor_inactivity':
                                        if (this.$gate.user.role_id === this.$roles.ADVISOR) {
                                            this.$store.commit('setUserStatus', 'offline');
                                        } else {
                                            this.$store.commit('setUserStatus', 'online');
                                        }

                                        this.chatEndedMessage = 'Chat Session is ended due to advisor inactivity';
                                        break;
                                    case 'inactivity_money_back':
                                        if (this.$gate.user.role_id === this.$roles.ADVISOR) {
                                            this.$store.commit('setUserStatus', 'offline');
                                        } else {
                                            this.$store.commit('setUserStatus', 'online');
                                        }

                                        this.chatEndedMessage = 'Chat Session is ended due to inactivity';
                                        break;
                                    case 'manually':
                                        if (e.user.role_id !== this.$gate.user.role_id) {
                                            this.$store.commit('setUserStatus', 'online');
                                            this.chatEndedMessage = 'Chat Session is ended by ' + e.user.display_name;
                                        }
                                        break;
                                }

                                this.setActiveSession(false);
                                this.outOffFunds = false;
                                Echo.leave(`chat.${this.chatSessionId}`);

                                if (this.$gate.user.role_id === this.$roles.CUSTOMER && e.duration >= 300) {
                                    this.$modal.show('review-modal');
                                }

                                Echo.leave(`chat.${this.chatSessionId}`);

                                clearInterval(this.checkActivityIntervalId);
                                clearInterval(this.fetchDataIntervalId);
                                this.fetchData();
                            }
                        })
                        .listen('CustomerIsOutOfMoney', (e) => {
                            if (this.$gate.user.role_id === this.$roles.ADVISOR && this.customer.id === e.customer.id) {
                                if (!this.customerOutOfMoneyShown) {
                                    this.customerOutOfMoneyShown = true;
                                    this.$eventHub.$emit('customer-is-out-of-money', e.customer.display_name);
                                }

                                setTimeout(function () {
                                    this.customerOutOfMoneyShown = false;
                                }.bind(this), 10000);

                                this.outOffFunds = true;
                                this.pauseChat();
                            }
                        })
                        .listen('PaymentCreating', (e) => {
                            if (this.customer.id === e.user.id) {
                                if (this.$gate.user.role_id === this.$roles.ADVISOR) {
                                    this.$eventHub.$emit('customer-adding-funds', e.user.display_name);
                                    this.outOffFunds = false;
                                    this.addingFunds = true;
                                }

                                this.pauseChat();
                            }
                        })
                        .listen('PaymentApproved', (e) => {
                            if (this.customer.id === e.user.id && this.advisor.id === this.$gate.user.id) {
                                this.$eventHub.$emit('customer-added-funds', e.user.display_name, e.amount);
                                this.outOffFunds = false;
                                this.addingFunds = false;

                                this.fetchData().then(response => {
                                    if (this.activeSession) {
                                        this.continueChat()
                                    }

                                    this.scrollToEnd();
                                });
                            }
                        })
                        .listen('PaymentCancelled', (e) => {
                            if (this.customer.id === e.user.id && this.advisor.id === this.$gate.user.id) {
                                this.$eventHub.$emit('customer-cancelled-payment', e.user.display_name);
                                this.outOffFunds = false;
                                this.addingFunds = false;

                                if (this.chatFreeSeconds || this.chatPaidSeconds) {
                                    this.continueChat();
                                } else {
                                    this.setPauseReason('');
                                }
                            }
                        })
                        .listenForWhisper('typing', (e) => {
                            if (e.user.id === this.$gate.user.id) {
                                this.typing = true;
                                this.typingDate = moment();

                                // remove is typing indicator
                                setTimeout(function () {
                                    if (moment().diff(this.typingDate) > 1000) {
                                        this.typing = false;
                                    }
                                }.bind(this), 1600);
                            }
                        });

                    if (this.$route.query.amount) {
                        this.$eventHub.$emit('funds-added', this.$route.query.amount)
                    }
                }
            });

            this.fetchDataIntervalId = setInterval(function () {
                if (this.activeSession) {
                    this.fetchData().then(response => {
                        if (this.activeSession) {
                            this.$refs.freeCountdown.updateTime(this.chatFreeSeconds);
                            this.$refs.paidCountdown.updateTime(this.chatPaidSeconds);

                            if (!this.chatFreeSeconds && !this.chatPaidSeconds) {
                                this.showInsufficientFundsModal();
                            }
                        }
                    });
                }
            }.bind(this), 5000);
        },
        beforeDestroy() {
            clearInterval(this.checkActivityIntervalId);
            clearInterval(this.fetchDataIntervalId);

            if (this.duplicatedChat) {
                window.location.reload();
            }

            Echo.leave(`chat.${this.chatSessionId}`);

            // duplicated code from SharedLayout
            if (this.$store.getters.activeChatSession) {
                Echo.private(`chat.${this.$store.getters.activeChatSession.id}`)
                    .listen('CallEnded', (e) => {
                        Echo.leave(`chat.${this.chatSessionId}`);

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
        },
        destroyed() {
            this.resetState()
        },
        computed: {
            ...mapGetters('ChatSingle', [
                'chatSessionId',
                'messages',
                'activeSession',
                'newMessage',
                'chatPaidSeconds',
                'chatFreeSeconds',
                'duration',
                'loading',
                'advisor',
                'customer',
                'filePath',
                'rating',
                'reviewText',
                'reportComment',
                'pricePerMinute',
                'chatStarted',
                'chatPaused',
                'chatDeleted',
                'pauseReason',
                'payAmount',
                'discount'
            ]),
            ...mapGetters('CategoryIndex', [
                'freeSeconds'
            ]),
        },
        methods: {
            ...mapActions('ChatSingle', [
                'setChatSessionId',
                'setActiveSession',
                'setChatFreeSeconds',
                'setChatPaidSeconds',
                'fetchData',
                'sendMessage',
                'pushMessage',
                'setNewMessage',
                'resetState',
                'endChat',
                'setPayAmount',
                'pay',
                'setFilePath',
                'setRating',
                'setReviewText',
                'submitReview',
                'setReportComment',
                'submitReport',
                'setChatStarted',
                'setChatPaused',
                'moneyIsOver',
                'advisorIsInactive',
                'setPauseReason'
            ]),
            ...mapActions('CategoryIndex', [
                'getFreeMinutes'
            ]),
            sendChatMessage() {
                if (this.newMessage) {
                    this.sendMessage(this.$gate.user);

                    setTimeout(this.scrollToEnd, 10);
                } else {
                    this.errorMessage = true;
                }
            },
            updateNewMessage(e) {
                this.setNewMessage(e.target.value);

                if (this.newMessage) {
                    this.errorMessage = false;
                }
            },
            endSession() {
                this.$swal({
                    title: 'Are you sure?',
                    text: '',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    confirmButtonColor: '#dd4b39',
                    focusCancel: true,
                    reverseButtons: false
                }).then(result => {
                    if (result.value) {
                        this.endChat('manually');
                    }
                })
            },
            checkActivity() {
                if (this.activeSession) {
                    let customer_last_activity = '';
                    let advisor_last_activity = '';

                    let messages = this.messages.map(x => Object.assign({}, x));

                    for (let message of messages.reverse()) {
                        if (customer_last_activity && advisor_last_activity) {
                            break;
                        }

                        if (!customer_last_activity && message.user.role_id === this.$roles.CUSTOMER) {
                            customer_last_activity = moment.utc(message.created_at)
                        }

                        if (!advisor_last_activity && message.user.role_id === this.$roles.ADVISOR) {
                            advisor_last_activity = moment.utc(message.created_at)
                        }
                    }

                    // check customer activity
                    if (customer_last_activity) {
                        if (moment.utc().diff(customer_last_activity, 'seconds') > this.inactivityLimit) {
                            this.endChat('customer_inactivity');
                        }
                    } else {
                        if (moment.utc().diff(this.chatOpenedAt, 'seconds') > this.inactivityLimit) {
                            this.endChat('customer_inactivity');
                        }
                    }

                    // check advisor activity
                    if (advisor_last_activity) {
                        if (moment.utc().diff(advisor_last_activity, 'seconds') > this.inactivityModalLimit && !this.inactivityModalShowed) {
                            if (this.$gate.user.role_id === this.$roles.CUSTOMER) {
                                this.showInactivityModal();
                            }
                        }

                        if (moment.utc().diff(advisor_last_activity, 'seconds') > this.inactivityLimit) {
                            this.endChat('advisor_inactivity');
                        }
                    } else {
                        if (moment.utc().diff(this.chatOpenedAt, 'seconds') > this.inactivityModalLimit && !this.inactivityModalShowed) {
                            if (this.$gate.user.role_id === this.$roles.CUSTOMER) {
                                this.showInactivityModal();
                            }
                        }

                        if (moment.utc().diff(this.chatOpenedAt, 'seconds') > this.inactivityLimit) {
                            this.endChat('advisor_inactivity');
                        }
                    }

                    if (!this.duplicatedChat) {
                        localStorage.setItem('chatSessionActivity', Date.now());
                    }
                }
            },
            showInactivityModal() {
                this.inactivityModalShowed = true;

                this.pauseChat();

                this.advisorIsInactive();

                this.$swal({
                    title: 'Advisor is inactive',
                    text: 'Chat is going to end within 1 minutes. Would you like to carry on?',
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonText: 'End Session',
                    confirmButtonText: 'Carry On',
                    confirmButtonColor: '#dd4b39',
                    focusCancel: false,
                    reverseButtons: false,
                    allowOutsideClick: false
                }).then(result => {
                    if (result.dismiss && result.dismiss === 'cancel') {
                        this.endChat('inactivity_money_back');
                    } else {
                        this.setChatPaused(false);
                        this.advisorIsInactive(true);
                    }
                })
            },
            getMinutesByPrice(amount) {
                if (this.advisor) {
                    return Math.floor(amount / (this.pricePerMinute / 100));
                }
            },
            updatePayAmount(e) {
                if (e.target.value === 'custom') {
                    this.showCustomField = true;
                    this.setPayAmount(null)
                } else {
                    this.showCustomField = false;
                    this.setPayAmount(e.target.value)
                }
            },
            updateCustomPayAmount(e) {
                this.setPayAmount(e.target.value);
            },
            getLocalDate(date) {
                const UTC = moment.utc(date).toDate();

                return moment(UTC).local().toISOString();
            },
            pauseChat() {
                this.setChatStarted(false);
                this.setChatPaused(true);
                this.setPauseReason('');
            },
            continueChat() {
                this.setChatStarted(true);
                this.setChatPaused(false);
                this.setPauseReason('');

            },
            isTyping() {
                if (!this.metyping) {
                    this.metyping = true;

                    Echo.private(`chat.${this.$route.params.chatSessionId}`).whisper('typing', {
                        user: this.$gate.user.role_id === this.$roles.CUSTOMER ? this.advisor : this.customer
                    });

                    setTimeout(function () {
                        this.metyping = false;
                    }.bind(this), 1000);
                }
            },
            handleFileUpload() {
                this.fileLoading = true;
                let formData = new FormData();
                formData.append('file', this.$refs.file.files[0]);

                axios.post( '/chat/' + this.$route.params.chatSessionId + '/upload-file', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(resp => {
                    let img = new Image();

                    img.onload = () => {
                        this.setFilePath(resp.data);
                    };

                    img.src = '/storage/' + resp.data;
                }).finally(() => {
                    this.$refs.file.value = '';
                    this.fileLoading = false;
                });
            },
            removeFile() {
                axios.post( '/chat/' + this.$route.params.chatSessionId + '/remove-file', {
                    file: this.filePath
                }).then(resp => {
                    this.setFilePath('')
                });
            },
            updateRating(e) {
                this.setRating(e);
            },
            updateReviewText(e) {
                this.setReviewText(e.target.value);
            },
            submitReviewForm() {
                if (this.rating === 0) {
                    this.$eventHub.$emit('rating-required')
                } else {
                    this.submitReview().then(resp => {
                        this.$modal.hide('review-modal');
                        this.$eventHub.$emit('review-created')
                    });
                }
            },
            updateReportComment(e) {
                this.setReportComment(e.target.value);
            },
            submitReportForm() {
                if (!this.reportComment) {
                    this.$eventHub.$emit('comment-required')
                } else {
                    this.submitReport().then(resp => {
                        this.$modal.hide('report-modal');
                        this.$eventHub.$emit('report-created');
                        this.setReportComment('');
                    });
                }
            },
            scrollToEnd() {
                if (!this.mouseDown && this.$refs.chatContainer) {
                    let content = this.$refs.chatContainer;
                    content.scrollTop = content.scrollHeight;
                }
            },
            checkDuplicatedChat() {
                const isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);

                if (isSafari) {
                    const chatSessionTimestamp = localStorage.getItem('chatSessionActivity');

                    if (chatSessionTimestamp) {
                        const diff = new Date() - chatSessionTimestamp;

                        if (diff < 1900) {
                            this.duplicatedChat = true;
                        }
                    }
                } else {
                    // SOLUTION #1
                    // doesn't work on Safari (Version 13.1 (15609.1.20.111.8))

                    window.localStorage.setItem('chatSession', Date.now());

                    let onLocalStorageEvent = function (e) {
                        if (e.key === "chatSession") {
                            // Listen if anybody else opening the same page!
                            window.localStorage.setItem('chatSessionDuplicate', Date.now());
                        }

                        if (e.key === "chatSessionDuplicate") {
                            this.duplicatedChat = true;
                            this.setActiveSession(false);
                        }
                    }.bind(this);

                    window.addEventListener('storage', onLocalStorageEvent, true);
                }

                // SOLUTION #2
                // doesn't work on Safari (Version 13.1 (15609.1.20.111.8))

                ////Private variables
                // let _LOCALSTORAGE_KEY = 'WINDOW_VALIDATION';
                // let RECHECK_WINDOW_DELAY_MS = 100;
                // let _initialized = false;
                // let _isMainWindow = false;
                // let _unloaded = false;
                // let _windowArray;
                // let _windowId;
                // let _isNewWindowPromotedToMain = false;
                // let _onWindowUpdated;
                //
                // function WindowStateManager(isNewWindowPromotedToMain, onWindowUpdated) {
                //     //this.resetWindows();
                //     _onWindowUpdated = onWindowUpdated;
                //     _isNewWindowPromotedToMain = isNewWindowPromotedToMain;
                //     _windowId = Date.now().toString();
                //
                //     bindUnload();
                //
                //     determineWindowState.call(this);
                //
                //     _initialized = true;
                //
                //     _onWindowUpdated.call(this);
                // }
                //
                // const _this = this;
                //
                // //Determine the state of the window
                // //If its a main or child window
                // function determineWindowState() {
                //     let self = this;
                //     let _previousState = _isMainWindow;
                //
                //     _windowArray = localStorage.getItem(_LOCALSTORAGE_KEY);
                //
                //     if (_windowArray === null || _windowArray === "NaN") {
                //         _windowArray = [];
                //     } else {
                //         _windowArray = JSON.parse(_windowArray);
                //     }
                //
                //     if (_initialized) {
                //         //Determine if this window should be promoted
                //         _isMainWindow = _windowArray.length <= 1 ||
                //             (_isNewWindowPromotedToMain ? _windowArray[_windowArray.length - 1] : _windowArray[0]) === _windowId;
                //     } else {
                //         if (_windowArray.length === 0) {
                //             _isMainWindow = true;
                //             _windowArray[0] = _windowId;
                //             localStorage.setItem(_LOCALSTORAGE_KEY, JSON.stringify(_windowArray));
                //         }
                //         else {
                //             _this.duplicatedChat = true;
                //             _this.setActiveSession(false);
                //             _isMainWindow = false;
                //             _windowArray.push(_windowId);
                //             localStorage.setItem(_LOCALSTORAGE_KEY, JSON.stringify(_windowArray));
                //         }
                //     }
                //
                //     //If the window state has been updated invoke callback
                //     if (_previousState !== _isMainWindow) {
                //         _onWindowUpdated.call(this);
                //     }
                //
                //     //Perform a recheck of the window on a delay
                //     setTimeout(function() {
                //         determineWindowState.call(self);
                //     }, RECHECK_WINDOW_DELAY_MS);
                // }
                //
                // //Remove the window from the global count
                // function removeWindow() {
                //     let __windowArray = JSON.parse(localStorage.getItem(_LOCALSTORAGE_KEY));
                //
                //     for (let i = 0, length = __windowArray.length; i < length; i++) {
                //         if (__windowArray[i] === _windowId) {
                //             __windowArray.splice(i, 1);
                //             break;
                //         }
                //     }
                //
                //     //Update the local storage with the new array
                //     localStorage.setItem(_LOCALSTORAGE_KEY, JSON.stringify(__windowArray));
                // }
                //
                // //Bind unloading events
                // function bindUnload() {
                //     window.addEventListener('beforeunload', function () {
                //         if (!_unloaded) {
                //             removeWindow();
                //         }
                //     });
                //
                //     window.addEventListener('unload', function () {
                //         if (!_unloaded) {
                //             removeWindow();
                //         }
                //     });
                // }
                //
                // WindowStateManager.prototype.isMainWindow = function () {
                //     return _isMainWindow;
                // };
                //
                // WindowStateManager.prototype.resetWindows = function () {
                //     localStorage.removeItem(_LOCALSTORAGE_KEY);
                // };
                //
                // window.WindowStateManager = WindowStateManager;
                //
                // // init
                // new WindowStateManager(false, windowUpdated);
                //
                // function windowUpdated() {
                //     (this.isMainWindow());
                // }
            },
            getHeaderMessage() {
                let message = '';

                if (this.activeSession) {
                    if (this.chatStarted) {
                        if (this.chatFreeSeconds) {
                            if (this.$gate.user.role_id === this.$roles.CUSTOMER) {
                                message = 'You are in free session'
                            }

                            if (this.$gate.user.role_id === this.$roles.ADVISOR) {
                                message = 'Client is in free session'
                            }
                        } else {
                            if (this.$gate.user.role_id === this.$roles.CUSTOMER) {
                                message = 'You are in paid session'
                            }

                            if (this.$gate.user.role_id === this.$roles.ADVISOR) {
                                message = 'Client is in paid session'
                            }
                        }
                    }

                    if (this.$gate.user.role_id === this.$roles.ADVISOR && this.addingFunds) {
                        message = 'Client is adding funds'
                    }

                    if (this.$gate.user.role_id === this.$roles.ADVISOR && this.outOffFunds) {
                        message = 'Client is out off funds'
                    }

                    if (this.pauseReason) {
                        message = this.pauseReason;

                        if (this.$gate.user.role_id === this.$roles.ADVISOR) {
                            switch (this.pauseReason) {
                                case 'Adding Funds':
                                    message = 'Client is adding funds';
                                    break;
                                case 'Insufficient funds':
                                    message = 'Client is out off funds';
                                    break;
                                case 'Advisor is inactive':
                                    message = 'You are inactive';
                                    break;
                            }
                        }
                    }
                }

                return message;
            },
            freeSecondsIsOver() {
                this.setChatFreeSeconds(0);

                if (this.chatPaidSeconds) {
                    if (this.$gate.user.role_id === this.$roles.CUSTOMER) {
                        this.$refs.soundPing.play();
                    }
                } else {
                    this.outOffFunds = true;

                    // create pause
                    setTimeout(function () {
                        this.pauseChat();

                        if (!this.insufficientFundsModalShown) {
                            this.moneyIsOver();
                        }

                        this.showInsufficientFundsModal();
                    }.bind(this), 1000);
                }
            },
            paidSecondsIsOver() {
                this.setChatPaidSeconds(0);

                this.outOffFunds = true;

                // create pause
                setTimeout(function () {
                    this.pauseChat();

                    if (!this.insufficientFundsModalShown) {
                        this.moneyIsOver();
                    }

                    this.showInsufficientFundsModal();
                }.bind(this), 1000);

            },
            showInsufficientFundsModal(force = false) {
                if (this.activeSession && !this.insufficientFundsModalShown || this.activeSession && force) {
                    if (this.$gate.user.role_id === this.$roles.CUSTOMER) {
                        this.insufficientFundsModalShown = true;

                        this.$swal({
                            title: 'Add funds to carry on the chat',
                            text: '',
                            type: 'warning',
                            showCancelButton: true,
                            cancelButtonText: 'End Session',
                            confirmButtonText: 'Add Funds',
                            confirmButtonColor: '#dd4b39',
                            allowOutsideClick: false,
                        }).then(result => {
                            if (result.value) {
                                this.$modal.show('pay-modal');
                            } else if (result.dismiss && result.dismiss === 'cancel') {
                                this.endChat('manually');
                            }
                        })
                    }
                }
            },
            openStartChatModal(advisor) {
                this.getFreeMinutes(advisor.id).then(() => {
                    this.$modal.show('start-chat-modal', {
                        advisor: advisor,
                        freeSeconds: this.freeSeconds
                    });
                })
            },
            isBlockedAdvisor(advisor_id) {
                return this.$store.getters.blockedAdvisors.indexOf(advisor_id) !== -1;
            },
            beforePayModalClose() {
                this.setPayAmount(10);
                this.showCustomField = false;

                if (!this.chatFreeSeconds && !this.chatPaidSeconds) {
                    this.showInsufficientFundsModal(true);
                }
            },
            isUserMessage(messageUser) {
                return messageUser.id === this.$gate.user.id && (this.$gate.user.role_id !== this.$roles.SUPERADMIN && this.$gate.user.role_id !== this.$roles.ADMIN) || (this.$gate.user.role_id === this.$roles.SUPERADMIN || this.$gate.user.role_id === this.$roles.ADMIN) && messageUser.role_id === this.$roles.CUSTOMER;
            },
        }
    }
</script>


<style lang="scss" scoped>
    .container-fluid {
        padding-left: 40px;
        padding-right: 40px;
        @media (max-width: 767.98px) {
            padding-left: 15px;
            padding-right: 15px;
        }
    }

    .logo {
        height: 60px;
        @media (max-width: 767.98px) {
            height: 40px;
        }
    }

    .chat-row {
        margin-left: -2px;
        margin-right: -2px;
        .col-md-4,
        .col-12 {
            padding-left: 2px;
            padding-right: 2px;
        }
    }

    .left-col {
        .avatar {
            display: table;
            margin: 0 auto;
            width: 160px;
            height: 160px;
            background-size: cover !important;
            background-position: center center !important;
            border-radius: 50%;
        }
        .display_name {
            font-size: 1.61rem;
            font-weight: 300;
        }
        .master-service {
            margin-top: 6px;
        }
        .fee-title {
            font-size: 80%;
            margin-top: 50px;
        }
        .price {
            color: #17a2b8;
            font-size: 2.5rem;
            font-weight: 300;
            line-height: normal;
        }
    }

    .right-col {
        height: calc(100vh - 134px);
        min-height: 540px;
        @media (max-width: 767.98px) {
            height: calc(100vh - 234px);
            min-height: 295px;
        }
    }

    .chat-panel-body {
        overflow-y: scroll;
        height: calc(100vh - 292px);
        padding-right: 15px;
        margin-top: 15px;
        min-height: 380px;
        @media (max-width: 767.98px) {
            height: calc(100vh - 430px);
            min-height: 100px;
        }
    }

    .pay-modal {
        .avatar {
            margin-right: 20px;
            img {
                border-radius: 50%;
            }
        }
        .display-name {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 20px;
            line-height: 24px;
            margin-bottom: 5px;
        }
        .fee-label {
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            font-size: 14px;
            line-height: 17px;
            color: #EC7A4C;
            margin-bottom: 5px;
        }
        .price {
            background: linear-gradient(270deg, #F58233 0%, #C457B7 100%);
            background-clip: border-box;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 20px;
            line-height: 24px;
        }
    }

    .stopwatch {
        font-size: 2.5rem;
        font-weight: 300;
        @media (max-width: 767.98px) {
            font-size: 16px;
            margin-bottom: 0 !important;
        }
    }

    .disclaimer {
        padding-top: 20px;
        padding-bottom: 20px;
        font-size: 12px;
    }

    .timer-title {
        color: #17a2b8;
    }

    .chat-header {
        border-bottom: 1px solid rgba(0, 0 ,0, .1);
        padding-bottom: 10px;
    }

    .chat-buttons {
        .btn {
            padding: 0;
            border: 0;
            color: #212529;
            &:hover {
                color: #17a2b8;
            }
        }
    }
</style>
