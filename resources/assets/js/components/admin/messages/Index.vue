<template>
    <section class="content-box">

        <section>
            <div class="box-header" style="height: 86px">
                <h3 class="box-title">Messages</h3>
            </div>

            <div v-if="subjects" class="d-flex flex-column flex-md-row chat-container">
                <div v-if="subjects.length" :class="{ 'messsage-show-page': $route.name === 'admin.messages.show' }">

                        <div class="left-col">
                            <div class="d-flex justify-content-between sort-container">
                                <div class="d-flex align-items-center">
                                    <label for="sort">Sort&nbsp;by:</label>
                                    <select @change="updateSort" id="sort" class="form-control">
                                        <option value="latest">Latest First</option>
                                        <option value="oldest">Oldest First</option>
                                    </select>
                                </div>
                                <button v-if="subjects.length && $gate.user.role_id === $roles.CUSTOMER" @click="newSubject()" :disabled="newSubjectLoading" class="btn p-0 btn-new-subject">
                                    <img src="/images/admin/new-subject.png">
                                </button>
                            </div>

                            <vue-custom-scrollbar class="scrollable-box">
                                <ul class="subjects">
                                <li v-for="subject in subjects">
                                    <router-link :to="{ name: 'admin.messages.show', params: { subjectId: subject.id } }">
                                        <div class="d-flex">
                                            <div class="avatar" :style="'background-image: url(' + getAvatar(subject) + ')'">
                                                <div
                                                        :class="{online: $gate.user.id === subject.customer.id ? subject.advisor.is_logged_in : subject.customer.is_logged_in}"
                                                        class="status"
                                                >
                                                    <span></span>
                                                </div>
                                            </div>
                                            <div class="w-100">
                                                <div class="d-flex justify-content-between">
                                                    <div class="display-name">
                                                        {{ $gate.user.id === subject.customer.id ? subject.advisor.display_name : subject.customer.display_name }}
                                                    </div>
                                                    <div class="time-ago">
                                                        <vue-moments-ago
                                                                prefix=""
                                                                suffix=" ago"
                                                                :date="getLocalDate(subject.messages[subject.messages.length - 1].created_at)"
                                                        ></vue-moments-ago>
                                                    </div>
                                                </div>

                                                <div class="subject">
                                                    Subject: {{ subject.subject }}
                                                </div>

                                                <div v-if="subject.messages[subject.messages.length - 1].message && subject.messages[subject.messages.length - 1].message.includes('email_chat_attachments')">
                                                    Image
                                                </div>
                                                <div v-else-if="!subject.messages[subject.messages.length - 1].message">
                                                    Invoice
                                                </div>
                                                <div class="last-message" v-else>
                                                    {{ subject.messages[subject.messages.length - 1].message }}
                                                </div>
                                                <div v-if="$route.params.subjectId !== subject.id && subject.unread_messages.length" class="unread-messages">
                                                    {{ subject.unread_messages.length }}
                                                </div>
                                            </div>
                                        </div>
                                    </router-link>
                                </li>
                            </ul>
                            </vue-custom-scrollbar>

                        </div>

                </div>
                <div class="w-100">
                    <div v-if="!subjects.length && ($gate.user.role_id === $roles.CUSTOMER || $gate.user.role_id === $roles.ADVISOR)" class="alert alert-info m-3">
                        You haven't had any conversation yet
                    </div>

                    <div style="margin-top: -86px">
                        <router-view @refreshData="fetchData"></router-view>
                    </div>
                </div>
            </div>

        </section>

        <modal name="new-subject-modal" :scrollable="true" :adaptive="true" width="500" height="auto">
            <div class="modal-header">
                <div class="title">
                    Send Message
                </div>
                <div>
                    <button @click="$modal.hide('new-subject-modal')" class="btn btn-link btn-close">
                        <i class="icomoon icomoon-cross"></i>
                    </button>
                </div>
            </div>
            <ValidationObserver ref="observer" v-slot="{ handleSubmit }">
                <form @submit.prevent="handleSubmit(submitForm)">
                    <div class="modal-body">
                                <div class="form-group">
                                    <label for="advisor">To</label>
                                    <v-select v-model="selectedAdvisor" inputId="advisor" :options="advisors" :clearable="false" label="display_name">
                                        <template slot="option" slot-scope="option">
                                            <img :src="option.avatar"
                                                 width="25"
                                                 height="25"
                                                 class="mr-1"
                                                 style="border-radius: 50%"
                                            >
                                            {{ option.display_name }}
                                        </template>
                                    </v-select>
                                </div>

                                <ValidationProvider name="Subject" rules="required" v-slot="{ errors }">
                                    <div class="form-group">
                                        <label for="subject">Subject</label>
                                        <input
                                                type="text"
                                                :value="subject"
                                                @input="updateSubject"
                                                id="subject"
                                                class="form-control"
                                                :class="{'is-invalid': errors[0] }"
                                                placeholder="Enter subject here"
                                        >
                                        <div class="invalid-feedback">{{ errors[0] }}</div>
                                    </div>
                                </ValidationProvider>

                                <ValidationProvider name="Message" rules="required" v-slot="{ errors }">
                                    <div class="form-group">
                                        <label for="message">Message</label>
                                        <textarea
                                                :value="message"
                                                @input="updateMessage"
                                                id="message"
                                                class="form-control"
                                                :class="{'is-invalid': errors[0] }"
                                                rows="10"
                                                placeholder="Enter message here"
                                        >
                                                </textarea>
                                        <div class="invalid-feedback">{{ errors[0] }}</div>
                                    </div>
                                </ValidationProvider>






                    </div>
                    <div class="modal-footer">
                        <vue-button-spinner
                                class="btn btn-success"
                                :isLoading="loading"
                                :disabled="loading"
                        >
                            Send
                        </vue-button-spinner>

                        <button @click="$modal.hide('new-subject-modal')" type="button" class="btn btn-secondary">
                            Cancel
                        </button>
                    </div>
                </form>
            </ValidationObserver>
        </modal>
    </section>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import VueMomentsAgo from 'vue-moments-ago'
    import MyMixins from '../../../mixins'
    import vueCustomScrollbar from 'vue-custom-scrollbar'

    export default {
        components: {
            VueMomentsAgo,
            vueCustomScrollbar
        },
        mixins: [MyMixins],
        data() {
            return {
                newSubjectLoading: false,
                query: {
                    sort: 'id',
                    order: 'desc'
                },
                fetchDataIntervalId: null,
                selectedAdvisor: null
            }
        },
        created() {
            this.fetchDataIntervalId = setInterval(function () {
                this.fetchData()
            }.bind(this), 5000);
        },
        beforeDestroy() {
            clearInterval(this.fetchDataIntervalId);
        },
        destroyed() {
            this.resetState()
        },
        computed: {
            ...mapGetters('MessagesIndex', [
                'subjects',
                'advisors',
            ]),
            ...mapGetters('MessagesNewSubject', [
                'loading',
                'subject',
                'message'
            ]),
        },
        watch: {
            query: {
                handler(query) {
                    this.setQuery({
                        query: query
                    });
                },
                deep: true,
                immediate: true
            },
        },
        methods: {
            ...mapActions('MessagesIndex', [
                'setQuery',
                'resetState',
                'fetchData',
                'fetchAdvisors'
            ]),
            ...mapActions('MessagesNewSubject', [
                'resetState',
                'createSubject',
                'setSubject',
                'setMessage'
            ]),
            ...mapActions('MessagesSingle', [
                'pay'
            ]),
            getLocalDate(date) {
                const UTC = moment.utc(date).toDate();
                return moment(UTC).local().toISOString();
            },
            updateSort(e) {
                switch (e.target.value) {
                    case 'latest':
                        this.query.order = 'desc';
                        break;
                    case 'oldest':
                        this.query.order = 'asc';
                        break;
                }
            },
            newSubject() {
                if (!this.advisors.length) {
                    this.newSubjectLoading = true;

                    this.fetchAdvisors().then(response => {
                        this.selectedAdvisor = this.advisors[0];
                        this.$modal.show('new-subject-modal');

                        this.newSubjectLoading = false;
                    });
                } else {
                    this.$modal.show('new-subject-modal');
                }
            },
            updateSubject(e) {
                this.setSubject(e.target.value)
            },
            updateMessage(e) {
                this.setMessage(e.target.value)
            },
            submitForm() {
                this.createSubject(this.selectedAdvisor.slug)
                    .then(resp => {
                        this.$router.push({ name: 'admin.messages.show', params: {subjectId: resp.data} });
                        this.$modal.hide('new-subject-modal');
                        this.resetNewSubjectForm();
                    });
            },
            resetNewSubjectForm() {
                this.setSubject('');
                this.setMessage('');
            },
            getAvatar(subject) {
                return this.$gate.user.id === subject.customer.id ? subject.advisor.avatar : subject.customer.avatar;
            },
            beforePayModalClose() {
                this.setPayAmount(10);
                this.showCustomField = false;
            },
        }
    }
</script>


<style lang="scss" scoped>
    .content-box {
        padding-bottom: 54px !important;
    }
    .chat-container {
        background: #ffffff;
        border-bottom: 1px solid #d8d8d8;
    }

    .left-col {
        width: 320px;
        height: 100%;
        border-right: 1px solid #d8d8d8;
    }

    .scrollable-box {
        position: relative;
        height: calc(100vh - 273px);
        width: 100%;
        max-width: 320px;
    }

    ul.subjects {
        margin: 0;
        padding: 0;
        border-top: 1px solid #eeeeee;
        li {
            position: relative;
            list-style: none;
            border-bottom: 1px solid #eeeeee;
            a {
                display: block;
                padding: 12px 20px;
                color: #aebac3;
                &:hover {
                    background: #f7f7f7;
                }
                &.router-link-active {
                    background: #f7f7f7;
                    .time-ago {
                        color: #000000;
                    }
                }
            }
            .avatar {
                position: relative;
                margin-right: 20px;
                width: 38px;
                height: 38px;
                border-radius: 50%;
                background-size: cover !important;
                background-position: center center !important;
                .status {
                    position: absolute;
                    right: 0;
                    top: 28px;
                    width: 14px;
                    height: 14px;
                    background: #f7657f;
                    border: 2px solid #ffffff;
                    border-radius: 50%;
                    &.online {
                        background: #9ad82b;
                    }
                    span {
                        height: 6px;
                        width: 6px;
                        position: absolute;
                        top: 2px;
                        left: 2px;
                        background: white;
                        border-radius: 50%;
                        display: inline;
                    }
                }

            }
            .display-name {
                text-overflow: ellipsis;
                overflow: hidden;
                white-space: nowrap;
                max-width: 150px;
                color: #212529;
            }
            .subject {
                font-size: 12px;
            }
            .time-ago {
                text-align: right;
                font-size: 11px;
            }
            .last-message {
                text-overflow: ellipsis;
                overflow: hidden;
                white-space: nowrap;
                max-width: 210px;
                color: #aebac3;
                font-size: 12px;
            }
            .unread-messages {
                position: absolute;
                top: 46px;
                right: 20px;
                background: #6dd230;
                color: #ffffff;
                border-radius: 50%;
                font-size: 12px;
                font-weight: 600;
                min-width: 18px;
                height: 18px;
                line-height: 18px;
                text-align: center;
            }
        }
    }

    .messsage-show-page {
        @media (max-width: 767.98px) {
            display: none;
        }
    }

    .sort-container {
        padding: 5px 15px;
        label {
            margin: 0;
            font-size: 12px;
            color: gray;
        }
        select.form-control {
            border: 0;
            font-size: 12px;
            option {
                font-size: 14px;
            }
        }
    }

    .btn-new-subject {
        &:focus,
        &:hover {
            box-shadow: none;
        }
    }
</style>
