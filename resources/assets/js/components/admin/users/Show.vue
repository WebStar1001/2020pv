<template>
        <section class="content-box">
            <div class="row">
                <div class="col-md-12">
                    <div>
                        <div class="box-header">
                            <h3 class="box-title">View User - {{ item.display_name }}</h3>
                        </div>

                        <div class="mb-3">
                            <back-buttton></back-buttton>
                        </div>

                        <div v-if="item.role_id === $roles.ADVISOR">
                            <div class="row dashboard-boxes mb-4">
                                <div class="col-lg-6 col-xl-3">
                                    <div class="dashboard-box">
                                        <div class="d-flex h-100 justify-content-between align-items-center">
                                            <div>
                                                <div class="title">
                                                    Readings
                                                </div>
                                                <div class="value">
                                                    {{ item.readings_count }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xl-3">
                                    <div class="dashboard-box">
                                        <div class="d-flex h-100 justify-content-between align-items-center">
                                            <div>
                                                <div class="title">
                                                    My Clients
                                                </div>
                                                <div class="value">
                                                    {{ item.clients }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xl-3">
                                    <div class="dashboard-box">
                                        <div class="d-flex h-100 justify-content-between align-items-center">
                                            <div>
                                                <div class="title">
                                                    Lifetime Earnings
                                                </div>
                                                <div class="value">
                                                    ${{ toFixed(item.lifetime_earnings, 2) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xl-3">
                                    <div class="dashboard-box">
                                        <div class="d-flex h-100 justify-content-between align-items-center">
                                            <div>
                                                <div class="title">
                                                    Available Funds
                                                </div>
                                                <div class="value">
                                                    ${{ toFixed(item.balance, 2) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-20 pb-0">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="box-title box-title-sm mb-2">
                                            Earnings overview
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-20">
                                        <div class="dashboard-box">
                                            <div class="d-flex h-100 justify-content-between align-items-center">
                                                <div>
                                                    <div class="value">
                                                        ${{ toFixed(item.sales, 2) }}
                                                    </div>
                                                    <div class="title">
                                                        Sales
                                                    </div>
                                                </div>
                                                <div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-20">
                                        <div class="dashboard-box">
                                            <div class="d-flex h-100 justify-content-between align-items-center">
                                                <div>
                                                    <div class="value">
                                                        ${{ toFixed(item.daily_sales, 2) }}
                                                    </div>
                                                    <div class="title">
                                                        Net Sales
                                                    </div>
                                                </div>
                                                <div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-20">
                                        <div class="dashboard-box">
                                            <div class="d-flex h-100 justify-content-between align-items-center">
                                                <div>
                                                    <div class="value">
                                                        ${{ toFixed(item.withdrawn, 2) }}
                                                    </div>
                                                    <div class="title">
                                                        Withdrawn
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="item.role_id === $roles.CUSTOMER">
                            <div class="row">
                                <div class="col-md-4 mb-20">
                                    <div class="dashboard-box">
                                        <div class="d-flex h-100 justify-content-between align-items-center">
                                            <div>
                                                <div class="title">
                                                    Readings
                                                </div>
                                                <div class="value">
                                                    {{ item.readings_count }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-20">
                                    <div class="dashboard-box">
                                        <div class="d-flex h-100 justify-content-between align-items-center">
                                            <div>
                                                <div class="title">
                                                    Available Funds
                                                </div>
                                                <div class="value">
                                                    ${{ toFixed(item.balance, 2) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-20">
                                    <div class="dashboard-box">
                                        <div class="d-flex h-100 justify-content-between align-items-center">
                                            <div>
                                                <div class="title">
                                                    Added Funds
                                                </div>
                                                <div class="value">
                                                    ${{ item.added_funds }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                        <tr>
                                            <th v-if="$gate.user.role_id === $roles.SUPERADMIN">
                                                Login as
                                                <span v-if="item.role_id === $roles.ADVISOR">Advisor</span>
                                                <span v-if="item.role_id === $roles.CUSTOMER">Customer</span>
                                                <span v-if="item.role_id === $roles.ADMIN">Admin</span>
                                                <span v-if="item.role_id === $roles.SUPERADMIN">Superadmin</span>
                                            </th>
                                            <td>
                                                <button @click="login" class="btn btn-success">
                                                    Login as
                                                    <span v-if="item.role_id === $roles.ADVISOR">Advisor</span>
                                                    <span v-if="item.role_id === $roles.CUSTOMER">Customer</span>
                                                    <span v-if="item.role_id === $roles.ADMIN">Admin</span>
                                                    <span v-if="item.role_id === $roles.SUPERADMIN">Superadmin</span>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>#</th>
                                            <td>{{ item.id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Photo</th>
                                            <td>
                                                <img :src="item.avatar" width="150" alt="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Screen Name</th>
                                            <td>{{ item.display_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Slug</th>
                                            <td>{{ item.slug }}</td>
                                        </tr>
                                        <tr v-if="item.role_id === $roles.ADVISOR">
                                            <th>Full Name</th>
                                            <td>
                                                {{ item.full_name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>{{ item.email }}</td>
                                        </tr>
                                        <tr v-if="item.role_id === $roles.ADVISOR">
                                            <th>Country</th>
                                            <td>
                                                {{ item.country }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>IP</th>
                                            <td>
                                                <span v-if="item.ip">
                                                    <a :href="'https://geolocation-db.com/jsonp/' + item.ip" target="_blank">{{ item.ip }}</a>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr v-if="item.role_id === $roles.ADVISOR">
                                            <th>Resume</th>
                                            <td>
                                                <a :href="'/storage/' + item.resume" target="_blank">
                                                    Open in new tab
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Short Description</th>
                                            <td>{{ item.description }}</td>
                                        </tr>
                                        <tr v-if="$gate.user.role_id === $roles.SUPERADMIN">
                                            <th>Balance</th>
                                            <td>
                                                ${{ item.balance }}

                                                (<a @click.prevent="$modal.show('balance-modal')" href="#">Change Balance</a>)
                                            </td>
                                        </tr>
                                        <tr v-if="item.role_id === $roles.ADVISOR">
                                            <th>Price Per Minute</th>
                                            <td>${{ item.price_per_minute }}</td>
                                        </tr>
                                        <tr v-if="item.role_id === $roles.ADVISOR">
                                            <th>Commission Percentage</th>
                                            <td>{{ item.commission_percentage }}%</td>
                                        </tr>
                                        <tr v-if="item.role_id === $roles.ADVISOR">
                                            <th>Horoscope</th>
                                            <td>
                                                <span v-if="item.horoscope_id">
                                                    {{ item.horoscope.title }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr v-if="item.role_id === $roles.ADVISOR">
                                            <th>Master Service</th>
                                            <td>
                                                <span v-if="item.master_service_id">
                                                    {{ item.master_service.title }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr v-if="item.role_id === $roles.ADVISOR">
                                            <th>Experience & Qualifications</th>
                                            <td>
                                                {{ item.experience }}
                                            </td>
                                        </tr>
                                        <tr v-if="item.role_id === $roles.ADVISOR">
                                            <th>Other Qualifications</th>
                                            <td>
                                                {{ item.qualification }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Role</th>
                                            <td>
                                                <span class="btn label-super-admin" v-if="item.role_id === $roles.SUPERADMIN">
                                                    Super Admin
                                                </span>
                                                <span class="btn label-admin" v-if="item.role_id === $roles.ADMIN">
                                                    Admin
                                                </span>
                                                <span class="btn label-customer" v-if="item.role_id === $roles.CUSTOMER">
                                                    Customer
                                                </span>
                                                <span class="btn label-advisor" v-if="item.role_id === $roles.ADVISOR">
                                                    Advisor
                                                </span>
                                            </td>
                                        </tr>
                                        <tr v-if="item.role_id === $roles.ADVISOR">
                                            <th>Status</th>
                                            <td>
                                                <span v-if="item.blocked === 1" class="badge badge-pill badge-danger">
                                                    Blocked
                                                </span>

                                                <span v-if="item.blocked === 0" class="badge badge-pill badge-success">
                                                    Active
                                                </span>
                                            </td>
                                        </tr>
                                        <tr v-if="item.role_id === $roles.ADVISOR">
                                            <th>Approved</th>
                                            <td>
                                                <span v-if="item.approved === 1" class="badge badge-pill badge-success">
                                                    Approved
                                                </span>

                                                <div  v-if="item.approved === 0 && !item.decline_reason">
                                                    <span class="badge badge-pill badge-danger">
                                                        Not Approved
                                                    </span>

                                                    - <button @click="approve()" :disabled="loading" type="button" class="btn btn-success"><i class="fas fa-check"></i> Approve</button>
                                                    / <button @click="$modal.show('decline-modal')" :disabled="loading" type="button" class="btn btn-danger"><i class="fas fa-ban"></i> Decline</button>
                                                </div>

                                                <div v-if="item.approved === 0 && item.decline_reason">
                                                    <span class="badge badge-pill badge-warning">
                                                        Declined
                                                    </span>
                                                    <strong>Reason</strong>: {{ item.decline_reason }}
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="item.role_id === $roles.ADVISOR">
                                            <th>Free Minutes</th>
                                            <td>
                                                <span v-if="item.free_minutes_enabled === 1" class="badge badge-pill badge-success">
                                                    Enabled
                                                </span>

                                                <span v-if="item.free_minutes_enabled === 0" class="badge badge-pill badge-danger">
                                                    Disabled
                                                </span>
                                            </td>
                                        </tr>
                                        <tr v-if="item.role_id === $roles.ADVISOR">
                                            <th>Subscribed to Newsletter</th>
                                            <td>
                                                <span v-if="item.subscribed === 1" class="badge badge-pill badge-success">
                                                    Subscribed
                                                </span>

                                                <span v-if="item.subscribed === 0" class="badge badge-pill badge-danger">
                                                    Not Subscribed
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Created At</th>
                                            <td>{{ item.created_at }}</td>
                                        </tr>
                                        <tr>
                                            <th>Updated At</th>
                                            <td>{{ item.updated_at }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <div v-if="item.role_id === $roles.ADVISOR">
                                        <div class="box-header">
                                            <h3 class="box-title">Reviews</h3>
                                        </div>

                                        <div class="mb-5">
                                            <div v-if="loading" class="loading-box">
                                                <i class="fa fa-spin fa-refresh"></i> Loading
                                            </div>

                                            <datatable
                                                    v-if="!loading"
                                                    :columns="reviewsColumns"
                                                    :data="reviews"
                                                    :total="total"
                                                    :query="query"
                                                    :HeaderSettings="false"
                                            />
                                        </div>

                                        <div v-if="!item.paypal_payment">
                                            <div class="box-header">
                                                <h3 class="box-title">Bank Details</h3>
                                            </div>

                                            <div class="box-body">
                                                <div v-if="loading" class="loading-box">
                                                    <i class="fa fa-spin fa-refresh"></i> Loading
                                                </div>

                                                <div v-if="item.bank_detail">
                                                    <table class="table">
                                                        <tr>
                                                            <th>Full Name</th>
                                                            <td>{{ item.bank_detail.full_name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Address</th>
                                                            <td>{{ item.bank_detail.address }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Zipcode</th>
                                                            <td>{{ item.bank_detail.zipcode }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>IBAN</th>
                                                            <td>{{ item.bank_detail.iban }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>SWIFT</th>
                                                            <td>{{ item.bank_detail.swift }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Bank Name</th>
                                                            <td>{{ item.bank_detail.bank_name }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div v-else>
                                                    No Data
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <modal name="decline-modal" :clickToClose="false" :scrollable="true" :adaptive="true" height="auto">
                <ValidationObserver v-slot="{ handleSubmit }">
                    <form @submit.prevent="handleSubmit(decline)">
                        <div class="modal-body">
                            <ValidationProvider name="Decline Reason" rules="required" v-slot="{ errors }">
                                <div class="form-group">
                                    <label for="decline_reason">Decline Reason</label>
                                    <input
                                            type="text"
                                            :value="item.decline_reason"
                                            @input="updateDeclineReason"
                                            id="decline_reason"
                                            class="form-control"
                                            :class="{'is-invalid': errors[0] }"
                                    >
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
                                Submit
                            </vue-button-spinner>

                            <button @click="cancelDecline" type="button" class="btn btn-secondary">
                                Cancel
                            </button>
                        </div>
                    </form>
                </ValidationObserver>
            </modal>

            <modal name="balance-modal" :scrollable="true" :adaptive="true" height="auto">
                <div class="modal-header">
                    <div class="title">
                        Change Balance
                    </div>
                    <div>
                        <button @click="$modal.hide('balance-modal')" class="btn btn-link btn-close">
                            <i class="icomoon icomoon-cross"></i>
                        </button>
                    </div>
                </div>
                <ValidationObserver v-slot="{ handleSubmit }">
                    <form @submit.prevent="handleSubmit(submitBalanceForm)">
                        <div class="modal-body">
                            <div class="d-flex">
                                <ValidationProvider name="Operator" rules="required" v-slot="{ errors }">
                                    <div class="form-group mr-3">
                                        <label for="operator">Operator</label>
                                        <select
                                                :value="changeBalanceOperator"
                                                @input="updateChangeBalanceOperator"
                                                id="operator"
                                                class="form-control"
                                                :class="{'is-invalid': errors[0] }"
                                        >
                                            <option value="+" :selected="changeBalanceOperator === '+'">+</option>
                                            <option value="-" :selected="changeBalanceOperator === '-'">-</option>
                                        </select>

                                        <div class="invalid-feedback">{{ errors[0] }}</div>
                                    </div>
                                </ValidationProvider>

                                <ValidationProvider name="Amount" rules="required" v-slot="{ errors }">
                                    <div class="form-group">
                                        <label for="amount">Amount</label>
                                        <input
                                                type="text"
                                                :value="changeBalanceAmount"
                                                @input="updateChangeBalanceAmount"
                                                id="amount"
                                                class="form-control"
                                                :class="{'is-invalid': errors[0] }"
                                        >
                                        <div class="invalid-feedback">{{ errors[0] }}</div>
                                    </div>
                                </ValidationProvider>
                            </div>

                            <ValidationProvider name="Note" rules="required" v-slot="{ errors }">
                                <div class="form-group">
                                    <label for="note">Short Note</label>
                                    <input
                                            type="text"
                                            :value="changeBalanceNote"
                                            @input="updateChangeBalanceNote"
                                            id="note"
                                            class="form-control"
                                            :class="{'is-invalid': errors[0] }"
                                    >
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
                                Submit
                            </vue-button-spinner>

                            <button @click="$modal.hide('balance-modal')" type="button" class="btn btn-secondary">
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
import MyMixins from '../../../mixins'
import DatatableRating from '../../dtmodules/reviews/DatatableRating'
import DatatableCustomer from '../../dtmodules/reviews/DatatableCustomer'
import DatatableActions from '../../dtmodules/reviews/DatatableActions'

export default {
    mixins: [MyMixins],
    data() {
        return {
            reviewsColumns: [
                // { title: '#', field: 'id', sortable: true, colStyle: 'width: 50px;' },
                { title: 'Rating', field: 'rating', tdComp: DatatableRating, sortable: true },
                { title: 'Customer', tdComp: DatatableCustomer, sortable: false },
                { title: 'Text', field: 'text', sortable: true },
                { title: 'Actions', tdComp: DatatableActions, visible: true, thClass: 'text-right', tdClass: 'text-right' }
            ],
            query: { sort: 'id', order: 'desc', limit: 10, offset: 0},
        }
    },
    created() {
        this.setQuery(this.query);
        this.fetchData(this.$route.params.id);
        this.fetchReviews(this.$route.params.id);
    },
    destroyed() {
        this.resetState()
    },
    computed: {
        ...mapGetters('UsersSingle', [
            'item',
            'loading',
            'changeBalanceOperator',
            'changeBalanceAmount',
            'changeBalanceNote'
        ]),
        ...mapGetters('ReviewsIndex', [
            'reviews',
            'total'
        ])
    },
    watch: {
        "$route.params.id": function() {
            this.resetState();
        },
        query: {
            handler(query) {
                this.setQuery(query);
                this.fetchData(this.$route.params.id);
                this.fetchReviews(this.$route.params.id);
            },
            deep: true
        }
    },
    methods: {
        ...mapActions('UsersSingle', [
            'fetchData',
            'resetState',
            'approveAccount',
            'declineAccount',
            'changeBalance',
            'setApproved',
            'setDeclineReason',
            'setChangeBalanceOperator',
            'setChangeBalanceAmount',
            'setChangeBalanceNote',
            'loginAsUser'
        ]),
        ...mapActions('ReviewsIndex', [
            'fetchReviews',
            'setQuery',
            'deleteReview'
        ]),
        approve() {
            this.approveAccount().then(resp => {
                this.setApproved(1);
                this.$eventHub.$emit('account-approved');
            });
        },
        decline() {
            this.declineAccount().then(resp => {
                this.$modal.hide('decline-modal');
                this.$eventHub.$emit('account-declined');
            });
        },
        updateDeclineReason(e) {
            this.setDeclineReason(e.target.value);
        },
        cancelDecline() {
            this.setDeclineReason('');
            this.$modal.hide('decline-modal');
        },
        updateChangeBalanceOperator(e) {
            this.setChangeBalanceOperator(e.target.value);
        },
        updateChangeBalanceAmount(e) {
            this.setChangeBalanceAmount(e.target.value);
        },
        updateChangeBalanceNote(e) {
            this.setChangeBalanceNote(e.target.value);
        },
        submitBalanceForm() {
            this.changeBalance()
                .then(resp => {
                    if (resp.data === true) {
                        this.$modal.hide('balance-modal');
                        this.$eventHub.$emit('update-success');

                        this.fetchData(this.$route.params.id)
                    } else {
                        this.$eventHub.$emit('user-busy');
                    }
                })
                .catch((error) => {
                    console.error(error)
                })
        },
        login() {
            this.loginAsUser().then(resp => {
                sessionStorage.setItem('loggedAsUser', this.$gate.user.id);

                window.location.href = '/admin/dashboard';
                // this.$router.push({ name: 'admin.dashboard.index' });
            })
        }
    }
}
</script>


<style lang="scss" scoped>
    .dashboard-box {
        border-radius: 6px;
        background: #ffffff;
        padding: 1.5rem;
        .title {
            color: gray;
        }
        .value {
            font-size: 1.60rem;
            font-weight: 500;
        }
    }
</style>
