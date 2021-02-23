<template>
        <section class="content-box">
            <div>
                <div class="box-header">
                    <h3 class="box-title">Add Free Minutes</h3>
                </div>

                <div class="mb-3">
                    <back-buttton></back-buttton>
                </div>

                <div class="box">
                    <div v-if="item.role_id === $roles.CUSTOMER" class="form-group">
                        <label>Advisor</label>
                        <model-list-select
                                           :list="advisors"
                                           v-model="selectedUser"
                                           option-value="id"
                                           option-text="display_name"
                                           placeholder="Select Advisor">
                        </model-list-select>
                    </div>

                    <div v-if="item.role_id === $roles.ADVISOR" class="form-group">
                        <label>Customer</label>
                        <model-list-select
                                           :list="customers"
                                           v-model="selectedUser"
                                           option-value="id"
                                           option-text="display_name"
                                           placeholder="Select Customer">
                        </model-list-select>
                    </div>

                    <div v-if="freeMinutes !== null" class="form-group">
                        <label for="free-minutes">Free Minutes</label>
                        <input v-model="freeMinutes" type="number" id="free-minutes" class="form-control">
                    </div>

                    <div v-if="freeMinutes !== null" class="form-group">
                        <button @click="submitForm()" type="button" class="btn btn-success">Update</button>
                    </div>
                </div>
            </div>
        </section>
</template>


<script>
    import { mapGetters, mapActions } from 'vuex';
    import { ModelListSelect } from 'vue-search-select'
    import 'vue-search-select/dist/VueSearchSelect.css'

    export default {
        components: {
            ModelListSelect
        },
        data() {
            return {
                selectedUser: {},
                freeMinutes: null
            }
        },
        computed: {
            ...mapGetters('FreeMinutes', ['advisors', 'customers', 'loading']),
            ...mapGetters('UsersSingle', ['item'])
        },
        created() {
            this.fetchData(this.$route.params.id).then(resp => {
                if (this.item.role_id === this.$roles.CUSTOMER) {
                    this.fetchAdvisors();
                } else if (this.item.role_id === this.$roles.ADVISOR) {
                    this.fetchCustomers();
                }
            });
        },
        watch: {
            selectedUser(e) {
                axios.get('/users/' + this.item.id + '/free-minutes/' + e.id)
                    .then(resp => {
                        this.freeMinutes = resp.data;
                    });
            }
        },
        methods: {
            ...mapActions('FreeMinutes', [
                'fetchAdvisors',
                'fetchCustomers',
                'resetState',
            ]),
            ...mapActions('UsersSingle', [
                'fetchData'
            ]),
            submitForm() {
                axios.post('/users/' + this.$route.params.id + '/free-minutes/' + this.selectedUser.id, {
                    minutes: this.freeMinutes
                }).then(resp => {
                        this.$router.push({ name: 'admin.users.index' });
                        this.$eventHub.$emit('update-success')
                    });
            }
        }
    }
</script>


<style scoped>

</style>
