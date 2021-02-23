<template>
    <div>
        <img src="/images/icon/spinner.svg" style="display: none">

        <div class="d-flex justify-content-end align-items-center">
            <div class="mr-3">
                <a v-if="$gate.user.role_id === $roles.SUPERADMIN || $gate.user.role_id === $roles.ADMIN || loggedAsUser" :href="'/subjects/'+$route.params.subjectId+'/messages/pdf'" target="_blank" class="btn btn-success">
                    <i class="fa fa-download mr-2"></i>
                    Download History
                </a>
            </div>
            <div class="dash-br bg-white pl-3 pr-4 py-2 mb-3 d-flex align-items-center">
                <div class="dash-icon-c-chat-1">
                    <img src="/images/admin/messages-funds.png">
                </div>
                <div class="dash-text-c-chat">
                    <h3 class="mb-0">${{ toFixed($gate.user.balance, 2) }}</h3>
                    <a v-if="$gate.user.role_id === $roles.CUSTOMER" @click.prevent="$modal.show('pay-modal')" href="#">
                        <span class="b-text">Add funds</span>
                    </a>

                    <a v-if="$gate.user.role_id === $roles.ADVISOR" @click.prevent="$modal.show('invoice-modal')" href="#">
                        <span class="b-text">Make invoice</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="d-flex">
            <div class="center-col d-flex flex-column justify-content-between w-100">
                <div
                        class="chat-container"
                        ref="chatContainer"
                        @mousedown="mouseDown = true"
                        @mouseup="mouseDown = false"
                >
                    <h5>{{ subject }}</h5>

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
                        <li v-for="message in messages">
                            <div v-if="!isUserMessage(message.sender)">
                                <div class="d-flex">
                                    <div class="avatar" :style="'background-image: url(' + getAvatar() + ')'"></div>
                                    <div>
                                        <div class="message" v-if="!message.invoice_amount" v-html="message.message"></div>

                                        <div class="message invoice" v-if="message.invoice_amount">
                                            <div class="d-flex align-items-center">
                                                <div class="amount mr-3">${{ message.invoice_amount }}</div>
                                                <div>
                                                    <div class="title">Offer Mode</div>
                                                    <div v-if="!message.invoice_cancelled && !message.invoice_rejected && !message.invoice_accepted">
                                                        <button
                                                                @click.prevent="acceptBill(message.id)"
                                                                class="btn btn-link btn-accept mr-3"
                                                                :disabled="acceptLoading"
                                                        >
                                                            <img v-if="acceptLoading" src="/images/icon/spinner.svg" class="spinner" width="20" height="20">
                                                            <i v-if="!acceptLoading" class="fa fa-check"></i>Accept
                                                        </button>

                                                        <button
                                                                @click.prevent="rejectBill(message.id)"
                                                                class="btn btn-link btn-cancel"
                                                                :disabled="rejectLoading"
                                                        >
                                                            <img v-if="rejectLoading" src="/images/icon/spinner.svg" class="spinner" width="20" height="20">
                                                            <i v-if="!rejectLoading" class="fa fa-close"></i>Reject
                                                        </button>
                                                    </div>

                                                    <span v-if="message.invoice_accepted" class="text-success">
                                                        Accepted
                                                        <span v-if="!message.review_left">
                                                            -
                                                            <a @click.prevent="$modal.show('review-modal', {message_id: message.id})" href="#">
                                                                Leave Review
                                                            </a>
                                                        </span>
                                                    </span>
                                                    <span v-if="message.invoice_cancelled" class="text-danger">Cancelled</span>
                                                    <span v-if="message.invoice_rejected" class="text-danger">Rejected</span>
                                                </div>
                                            </div>
                                        </div>

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

                            <div v-if="isUserMessage(message.sender)">
                                <div class="d-flex justify-content-end">
                                    <div>
                                        <div class="message primary" v-if="!message.invoice_amount" v-html="message.message"></div>

                                        <div class="message primary invoice" v-if="message.invoice_amount">
                                            <div class="d-flex align-items-center">
                                                <div class="amount mr-3">${{ message.invoice_amount }}</div>
                                                <div>
                                                    <div class="title">Offer Mode</div>

                                                    <button
                                                            v-if="!message.invoice_cancelled && !message.invoice_rejected && !message.invoice_accepted"
                                                            @click.prevent="cancelBill(message.id)"
                                                            class="btn btn-link btn-cancel"
                                                            :disabled="rejectLoading"
                                                    >
                                                        <img v-if="rejectLoading" src="/images/admin/spinner.svg" class="spinner" width="20" height="20">
                                                        Cancel
                                                    </button>

                                                    <span v-if="message.invoice_accepted" class="">Accepted</span>
                                                    <span v-if="message.invoice_cancelled" class="text-danger">Cancelled</span>
                                                    <span v-if="message.invoice_rejected" class="text-danger">Rejected</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="time-ago text-right">
                                            <vue-moments-ago
                                                    prefix=""
                                                    suffix=" ago"
                                                    :date="getLocalDate(message.created_at)"
                                            ></vue-moments-ago>
                                        </div>
                                    </div>
                                    <div class="avatar" :style="'background-image: url(' + $gate.user.avatar + ')'"></div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="panel-footer">
                    <div v-if="advisor" class="typing-indicator">
                        <div v-show="typing">
                            <span v-if="$gate.user.role_id === $roles.CUSTOMER">{{ advisor.display_name }}</span>
                            <span v-else>{{ customer.display_name }}</span>
                            is typing...
                        </div>
                    </div>
                    <div class="form-group d-flex mb-0">
                        <div class="d-flex w-100 text-input-container">
                            <input @input="updateNewMessage"
                                   :value="newMessage"
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

                            <button @click="sendChatMessage()" type="submit" class="btn btn-sent-message">
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

            <vue-custom-scrollbar v-if="showProfileInfo" class="scrollable-box">
                <div v-if="$gate.user.role_id === $roles.CUSTOMER && advisor" class="profile-info">
                    <div class="avatar-container">
                        <img :src="advisor.avatar" class="w-100">
                        <button @click="showProfileInfo = false" type="button" class=" btn close-cross">×</button>
                        <div class="status" :class="advisor.status">
                            <span></span>
                            {{ advisor.status }}
                        </div>
                    </div>
                    <div class="name-container p-3">
                        <div class="name">{{ advisor.display_name }}</div>
                        <div class="service">{{ advisor.master_service.title }}</div>
                    </div>
                    <div class="dates p-3">
                        <p><strong>User since:</strong> {{ advisor.created_at | moment('DD MMMM YYYY') }}</p>
                        <p><strong>My Psychic since:</strong> {{ advisor.since }}</p>
                        <p><strong>Last Contacted:</strong> {{ advisor.last_contacted }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <router-link :to="{name: 'profile.profile.index', params: { masterService: advisor.master_service.slug, slug: advisor.slug }}" class="view-profile-link">
                                    View profile
                                </router-link>
                            </div>
                            <div class="price">
                                ${{ toFixed(getPricePerMinuteWithDiscount() / 100, 2) }}/min
                            </div>
                        </div>
                    </div>
                    <div class="stats p-3">
                        <div class="row">
                            <div class="col-6">
                                <div class="number">
                                    {{ toFixed(advisor.rating, 1) }}
                                </div>
                                <div class="mt-1">
                                    <stars-rating :config="{rating: advisor.rating, style: {starWidth: 16, starHeight: 16}}"></stars-rating>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="number">
                                    {{ advisor.reviews_total }}
                                </div>
                                <div class="title">
                                    Reviews
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="number">
                                    {{ advisor.readings_count }}
                                </div>
                                <div class="title">
                                    Readings
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="number">
                                    {{ advisor.experience_years }}<small>yrs</small>
                                </div>
                                <div class="title">
                                    Experience
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="!isBlockedAdvisor(advisor.id)" class="px-3 py-1 text-center">
                        <button class="btn btn-success"
                                @click="openStartChatModal(advisor)"
                                :disabled="advisor.status !== 'online' || $store.getters.user.status === 'busy' || $gate.user.role_id !== $roles.CUSTOMER"
                        >
                            <img src="/images/profile-card/meassage.png" alt="">
                            Start a paid chat session
                        </button>
                    </div>
                </div>
                <div v-if="$gate.user.role_id === $roles.ADVISOR && customer" class="profile-info">
                    <div class="avatar-container">
                        <img :src="customer.avatar" class="w-100">
                        <button @click="showProfileInfo = false" type="button" class=" btn close-cross">×</button>
                        <div class="status" :class="customer.status">
                            <span></span>
                            {{ customer.status }}
                        </div>
                    </div>
                    <div class="name-container p-3">
                        <div class="name mb-0">{{ customer.display_name }}</div>
                    </div>
                    <div class="dates p-3">
                        <p><strong>User since:</strong> {{ customer.created_at | moment('DD MMMM YYYY') }}</p>
                        <p><strong>My Client since:</strong> {{ customer.since }}</p>
                        <p class="mb-0"><strong>Last Contacted:</strong> {{ customer.last_contacted }}</p>
                    </div>
                </div>
            </vue-custom-scrollbar>
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
                    <div class="modal-body pay-modal">
                        <div class="form-group">
                            <label for="pay-amount">Funds</label>
                            <select @change="updatePayAmount" :disabled="loading" id="pay-amount" class="form-control">
                                <option value="10">$10</option>
                                <option value="25">$25</option>
                                <option value="50">$50</option>
                                <option value="100">$100</option>
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
                            Add Funds
                        </vue-button-spinner>

                        <button @click="$modal.hide('pay-modal')" type="button" class="btn btn-secondary">
                            Cancel
                        </button>
                    </div>
                </form>
            </ValidationObserver>
        </modal>

        <modal name="invoice-modal" @before-close="beforeInvoiceModalClose" :scrollable="true" :adaptive="true" width="500" height="auto">
            <div class="modal-header">
                <div class="title">
                    Make Invoice
                </div>
                <div>
                    <button @click="$modal.hide('invoice-modal')" class="btn btn-link btn-close">
                        <i class="icomoon icomoon-cross"></i>
                    </button>
                </div>
            </div>
            <ValidationObserver v-slot="{ handleSubmit }">
                <form @submit.prevent="handleSubmit(sendBill)">
                    <div class="modal-body pay-modal">

                        <div class="form-group">
                            <select @change="updateInvoiceAmount" :disabled="loading" id="invoice-amount" class="form-control">
                                <option value="10">$10</option>
                                <option value="25">$25</option>
                                <option value="50">$50</option>
                                <option value="100">$100</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>

                        <div v-if="showCustomField">
                            <ValidationProvider name="Amount" rules="required|min_value:10|integer" v-slot="{ errors }">
                                <div class="form-group">
                                    <label for="custom-invoice-amount">Amount</label>
                                    <input
                                            :value="invoiceAmount"
                                            @input="updateCustomInvoiceAmount"
                                            type="number"
                                            :disabled="loading"
                                            id="custom-invoice-amount"
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
                            Send Invoice
                        </vue-button-spinner>

                        <button @click="$modal.hide('invoice-modal')" type="button" class="btn btn-secondary">
                            Cancel
                        </button>
                    </div>
                </form>
            </ValidationObserver>
        </modal>

        <modal name="review-modal" @before-open="beforeReviewModalOpen" :clickToClose="false" :scrollable="true" :adaptive="true" width="500" height="auto">
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
    </div>

</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import VueMomentsAgo from 'vue-moments-ago'
    import MyMixins from '../../../mixins'
    import starsRating from "../../common/StarRating.vue";
    import vueCustomScrollbar from 'vue-custom-scrollbar'

    export default {
        components: {
            VueMomentsAgo,
            vueCustomScrollbar,
            starsRating: starsRating,
        },
        mixins: [MyMixins],
        data() {
            return {
                fetchDataIntervalId: null,
                typing: false,
                typingDate: moment(),
                metyping: false,
                mouseDown: false,
                errorMessage: false,
                showCustomField: false,
                fileLoading: false,
                reviewMessageId: null,
                showProfileInfo: true,
                discount: 0,
                loggedAsUser: false
            }
        },
        watch: {
            "$route.params.subjectId": function() {
                this.setSubjectId(this.$route.params.subjectId);
                this.setMessages([]);

                this.fetchData().then(response => {
                    this.scrollToEnd();

                    if (this.$route.query.amount) {
                        this.$eventHub.$emit('funds-added', this.$route.query.amount)
                    }
                });
            }
        },
        created() {
            this.loggedAsUser = this.$gate.user.role_id === this.$roles.SUPERADMIN ? false : sessionStorage.getItem('loggedAsUser');

            this.setSubjectId(this.$route.params.subjectId);

            this.fetchData().then(response => {
                this.scrollToEnd();

                if (this.$route.query.amount) {
                    this.$eventHub.$emit('funds-added', this.$route.query.amount)
                }

                Echo.private(`email_chat.${this.$route.params.subjectId}`)
                    .listen('EmailMessageSent', (e) => {
                        if (e.email_subject_id === +this.$route.params.subjectId) {
                            this.pushMessage({
                                id: e.message.id,
                                email_subject_id: e.email_subject_id,
                                message: e.message.message,
                                sender_id: e.user.id,
                                sender: e.user,
                                created_at: e.message.created_at,
                                invoice_amount: e.message.invoice_amount
                            });

                            setTimeout(this.scrollToEnd, 10)
                        }
                    })
                    .listen('InvoiceCancelled', (e) => {
                        if (e.email_subject_id === +this.$route.params.subjectId) {
                            this.fetchData().then(response => {
                                this.scrollToEnd();
                            });
                        }
                    })
                    .listen('InvoiceRejected', (e) => {
                        if (e.email_subject_id === +this.$route.params.subjectId) {
                            this.fetchData().then(response => {
                                this.scrollToEnd();
                            });
                        }
                    })
                    .listen('InvoiceAccepted', (e) => {
                        if (e.email_subject_id === +this.$route.params.subjectId) {
                            this.fetchData().then(response => {
                                this.scrollToEnd();
                            });
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

                // if (this.$gate || sessionStorage.getItem('specialoffer')) {
                    if (!this.isUsedDiscount()) {
                        this.discount = this.$store.getters.discount;
                    }
                // }
            });

            this.fetchDataIntervalId = setInterval(function () {
                this.fetchData().then(response => {
                    // this.scrollToEnd(false);
                });
            }.bind(this), 5000);
        },
        beforeDestroy() {
            clearInterval(this.fetchDataIntervalId);

            Echo.leave(`email_chat.${this.$route.params.subjectId}`);
        },
        destroyed() {
            this.resetState()
        },
        computed: {
            ...mapGetters('MessagesSingle', [
                'loading',
                'acceptLoading',
                'rejectLoading',
                'subject',
                'messages',
                'newMessage',
                'customer',
                'advisor',
                'payAmount',
                'invoiceAmount',
                'filePath',
                'reportComment',
                'rating',
                'reviewText',
            ]),
            ...mapGetters('CategoryIndex', [
                'freeSeconds'
            ]),
        },
        methods: {
            ...mapActions('MessagesSingle', [
                'resetState',
                'setSubjectId',
                'fetchData',
                'sendMessage',
                'pushMessage',
                'setNewMessage',
                'setPayAmount',
                'setInvoiceAmount',
                'pay',
                'setFilePath',
                'setReportComment',
                'submitReport',
                'sendInvoice',
                'cancelInvoice',
                'rejectInvoice',
                'acceptInvoice',
                'updateReadStatus',
                'setMessages',
                'setRating',
                'setReviewText',
                'submitReview'
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
            updatePayAmount(e) {
                if (e.target.value === 'custom') {
                    this.showCustomField = true;
                    this.setPayAmount(null)
                } else {
                    this.showCustomField = false;
                    this.setPayAmount(e.target.value)
                }
            },
            updateInvoiceAmount(e) {
                if (e.target.value === 'custom') {
                    this.showCustomField = true;
                    this.setInvoiceAmount(null)
                } else {
                    this.showCustomField = false;
                    this.setInvoiceAmount(e.target.value)
                }
            },
            updateCustomPayAmount(e) {
                this.setPayAmount(e.target.value);
            },
            updateCustomInvoiceAmount(e) {
                this.setInvoiceAmount(e.target.value);
            },
            getLocalDate(date) {
                const UTC = moment.utc(date).toDate();

                return moment(UTC).local().toISOString();
            },
            isTyping() {
                if (!this.metyping) {
                    this.metyping = true;

                    Echo.private(`email_chat.${this.$route.params.subjectId}`).whisper('typing', {
                        user: this.$gate.user.role_id === this.$roles.CUSTOMER ? this.advisor : this.customer
                    });

                    setTimeout(function () {
                        this.metyping = false;
                    }.bind(this), 1000);
                }
            },
            handleFileUpload() {
                // max filesize = 10MB
                if (this.$refs.file.files[0].aize > 10240000) {
                    alert('File is too large');
                } else {
                    this.fileLoading = true;
                    let formData = new FormData();
                    formData.append('file', this.$refs.file.files[0]);

                    axios.post('/subjects/' + this.$route.params.subjectId + '/upload-file', formData, {
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
                }
            },
            removeFile() {
                axios.post( '/subjects/' + this.$route.params.subjectId + '/remove-file', {
                    file: this.filePath
                }).then(resp => {
                    this.setFilePath('')
                });
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
            scrollToEnd(update = true) {
                if (update) {
                    this.updateReadStatus();
                }

                this.$emit('refreshData');

                if (!this.mouseDown && this.$refs.chatContainer) {
                    let content = this.$refs.chatContainer;
                    content.scrollTop = content.scrollHeight;
                }
            },
            isBlockedAdvisor(advisor_id) {
                return this.$store.getters.blockedAdvisors.indexOf(advisor_id) !== -1;
            },
            beforePayModalClose() {
                this.setPayAmount(10);
                this.showCustomField = false;
            },
            beforeInvoiceModalClose() {
                this.setInvoiceAmount(10);
                this.showCustomField = false;
            },
            sendBill() {
                this.sendInvoice().then(resp => {
                    this.fetchData().then(response => {
                        this.scrollToEnd();
                        this.$modal.hide('invoice-modal');
                    });
                });
            },
            cancelBill(id) {
                this.cancelInvoice(id).then(resp => {
                    this.fetchData().then(response => {
                        this.scrollToEnd();
                    });
                });
            },
            rejectBill(id) {
                this.rejectInvoice(id).then(resp => {
                    this.fetchData().then(response => {
                        this.scrollToEnd();
                    });
                });
            },
            acceptBill(id) {
                this.acceptInvoice(id).then(resp => {
                    if (resp.data) {
                        this.fetchData().then(response => {
                            this.scrollToEnd();
                        });
                    } else {
                        this.$eventHub.$emit('insufficient-funds');
                        this.$modal.show('pay-modal');
                    }
                });
            },
            beforeReviewModalOpen(event) {
                this.reviewMessageId = event.params.message_id
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
                    this.submitReview(this.reviewMessageId).then(resp => {
                        this.fetchData();
                        this.$modal.hide('review-modal');
                        this.$eventHub.$emit('review-created')
                    });
                }
            },
            isUserMessage(messageUser) {
                return messageUser.id === this.$gate.user.id && (this.$gate.user.role_id !== this.$roles.SUPERADMIN && this.$gate.user.role_id !== this.$roles.ADMIN) || (this.$gate.user.role_id === this.$roles.SUPERADMIN || this.$gate.user.role_id === this.$roles.ADMIN) && messageUser.role_id === this.$roles.CUSTOMER;
            },
            getPricePerMinuteWithDiscount() {
                if (this.discount) {
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
            },
            openStartChatModal(advisor, modalPaidAmount = null) {
                this.getFreeMinutes(advisor.id).then(() => {
                    this.$modal.show('start-chat-modal', {
                        advisor: advisor,
                        freeSeconds: this.freeSeconds,
                        modalPaidAmount: modalPaidAmount
                    });
                })
            },
            getAvatar() {
                return this.$gate.user.id === this.advisor.id ? this.customer.avatar : this.advisor.avatar
            }
        }
    }
</script>


<style lang="scss" scoped>
    .left-col {
        min-width: 310px;
        width: 310px;
        @media (max-width: 991.98px) {
            margin-bottom: 20px;
            width: 100%;
        }
    }

    .avatar {
        img {
            width: 150px;
            border-radius: 50%;
            @media (max-width: 991.98px) {
                width: 100px;
                height: 100px;
            }
        }
    }

    .center-col {
        height: calc(100vh - 225px);
        min-height: 200px;
        border-right: 1px solid #d8d8d8;
    }

    .chat-container {
        height: 100%;
        overflow-y: scroll;
        padding-right: 15px;
        padding-left: 15px;
        padding-top: 15px;
        @media (max-width: 991.98px) {
            flex-direction: column;
        }
    }

    .display_name {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        font-size: 20px;
        line-height: 24px;
        margin-bottom: 40px;
        @media (max-width: 991.98px) {
            margin-bottom: 20px;
        }
    }

    .invoice {
        &.primary {
            .title {
                color: #ffffff;
            }
        }
        .amount {
            width: 50px;
            height: 50px;
            background: #6dd230;
            text-align: center;
            border-radius: 50%;
            font-size: 1rem;
            line-height: 50px;
            box-shadow: 0 0 1px 5px #6dd2303d;
            color: #ffffff;
            margin-top: 5px;
            margin-bottom: 5px;
        }
        .title {
            color: #28a745;
            font-weight: 500;
        }
        .btn-accept {
            color: #01b001;
            padding: 0;
            font-weight: 500;
            vertical-align: middle;
            &:disabled {
                opacity: 0.5;
            }
            &:hover {
                opacity: 0.6;
                text-decoration: none;
            }
            .fa {
                font-size: 12px;
                margin-right: 5px;
                vertical-align: 1px;
            }
        }
        .btn-cancel {
            color: #dc3545;
            padding: 0;
            background: none;
            font-weight: 500;
            vertical-align: middle;
            img {
                vertical-align: top;
            }
            &:disabled {
                opacity: 0.5;
            }
            &:hover {
                opacity: 0.6;
                text-decoration: none;
            }
            .fa {
                font-size: 12px;
                margin-right: 5px;
                vertical-align: 1px;
            }
        }
        .btn-bg {
            background: #ffffff;
            border-radius: 20px;
            padding: 5px 12px;
        }
        .spinner {
            vertical-align: top;
        }
    }

    .balance {
        font-weight: 400;
        span {
            font-weight: 600;
        }
    }

    .dash-icon-c-chat-1 {
        width: 35px;
        height: 35px;
        background: #6dd230;
        text-align: center;
        border-radius: 50%;
        line-height: 32px;
        box-shadow: 0 0 1px 5px #6dd2302b;
    }

    .dash-text-c-chat {
        padding-left: 20px;
        h3 {
            font-size: 1.55rem;
            font-weight: 400;
        }
    }

    .b-text {
        font-size: 12px;
        color: #aebac3;
    }

    .scrollable-box {
        width: 260px;
        min-width: 260px;
        max-height: calc(100vh - 225px);
        @media (max-width: 1300px) {
            display: none;
        }
    }

    .profile-info {
        max-height: calc(100vh - 225px);
        /*overflow-x: auto;*/
        width: 260px;
        min-width: 260px;
        @media (max-width: 1300px) {
            display: none;
        }
        .avatar-container {
            .close-cross {
                padding: 0;
                font-size: 46px;
                line-height: 46px;
                background: transparent;
                position: absolute;
                top: -2px;
                left: 5px;
                font-weight: 400;
                color: #9c9c9c;
                &:hover,
                &:focus {
                    box-shadow: none;
                }
            }
            .status {
                position: absolute;
                right: 15px;
                top: 15px;
                background: #ffffff;
                color: #6b6b6b;
                border: 1px solid white;
                padding: 4px 10px;
                font-size: 13px;
                border-radius: .25rem;
                text-transform: capitalize;
                span {
                    display: inline-block;
                    width: 7px;
                    height: 7px;
                    border-radius: 50%;
                    margin-right: 3px;
                }
                &.online span {
                    background: #86cd03;
                }
                &.offline span {
                    background: #bfbfbf;
                }
                &.busy span {
                    background: #f5a623;
                }
            }
        }
        .name-container {
            border-bottom: 1px solid #cacaca;
            .name {
                font-size: 1.25rem;
                margin-bottom: .3rem;
            }
            .service {
                font-size: 12px;
                color: #aebac3;
            }
        }
        .dates {
            border-bottom: 1px solid #cacaca;
        }
        .view-profile-link {
            color: #28a745;
            &:hover {
                color: #1e7e34
            }
        }
        .price {
            color: #80bcfb;
            font-size: 17px;
        }
        .stats {
            border-bottom: 1px solid #cacaca;
            .row {
                margin-bottom: 10px;
                &:last-child {
                    margin-bottom: 0;
                }
            }
            .number {
                font-size: 1.75rem;
                margin-bottom: .2rem;
                font-weight: 400;
                small {
                    font-size: 61%;
                }
            }
        }
        .btn-success {
            font-size: 12px;
            padding: 7px 20px;
        }
    }
</style>
