<template>
        <section class="content-box">
          <div class="">
              <div class="box-header">
                  <h3 class="box-title">Users List</h3>
              </div>
              <div>
                  <div class="mb-3">
                      <div class="d-flex justify-content-between">
                          <div class="d-flex">
                              <div class="mr-3">
                                  <button type="button" class="btn btn-default" @click="fetchData">
                                      <i class="fa fa-refresh mr-1" :class="{'fa-spin': loading}"></i>Refresh
                                  </button>
                              </div>
                              <div>
                                  <select v-model="query.role_id" title="Filter by role" class="form-control">
                                      <option value="">All</option>
                                      <option :value="$roles.CUSTOMER">Customers</option>
                                      <option :value="$roles.ADVISOR">Advisors</option>
                                      <option :value="$roles.ADMIN">Admins</option>
                                      <option :value="$roles.SUPERADMIN">Super admins</option>
                                  </select>
                              </div>
                          </div>


                          <div class="d-flex">
                              <div class="mr-3">
                                  <input
                                          type="search"
                                          :value="query.search"
                                          @input="search"
                                          id="search"
                                          class="form-control"
                                          placeholder="Search..."
                                  >
                              </div>

                              <div>
                                  <router-link :to="{ name: 'admin.users.create' }" class="btn btn-success">
                                      <i class="fa fa-plus mr-1"></i> Add new
                                  </router-link>
                              </div>
                          </div>
                      </div>
                  </div>

                  <div class="box-body">
                      <div v-if="loading" class="loading-box">
                          <i class="fa fa-spin fa-refresh"></i> Loading
                      </div>

                      <datatable
                              v-if="!loading"
                              :columns="columns"
                              :data="data"
                              :total="total"
                              :query="query"
                              :xprops="xprops"
                              :HeaderSettings="false"
                      />
                  </div>
              </div>
          </div>
        </section>
    <!--</section>-->
</template>


<script>
import { mapGetters, mapActions } from 'vuex'
import DatatableActions from '../../dtmodules/users/DatatableActions'
import DatatableRoles from '../../dtmodules/users/DatatableRoles'
import DatatableApprove from '../../dtmodules/users/DatatableApprove'
import DatatableStatus from '../../dtmodules/users/DatatableStatus'

export default {
    data() {
        return {
            columns: [
                { title: '#', field: 'id', sortable: true, colStyle: 'width: 50px;' },
                { title: 'Name', field: 'display_name', sortable: true },
                { title: 'Email', field: 'email', sortable: true },
                { title: 'Balance', field: 'balance', sortable: true },
                { title: 'Role', field: 'role_id', sortable: true, tdComp: DatatableRoles },
                { title: 'Approved', field: 'approved', sortable: true, tdComp: DatatableApprove },
                { title: 'Status', field: 'blocked', sortable: true, tdComp: DatatableStatus },
                { title: 'IP', field: 'ip', sortable: true },
                { title: 'Actions', tdComp: DatatableActions, visible: true, thClass: 'text-right', tdClass: 'text-right', colStyle: 'width: 130px;' }
            ],
            query: { sort: 'id', order: 'desc', search: '', role_id: '' },
            xprops: {
                module: 'UsersIndex',
                route: 'users',
                gate_name: 'user',
            }
        }
    },
    created() {
        this.$root.relationships = this.relationships
    },
    destroyed() {
        this.resetState()
    },
    computed: {
        ...mapGetters('UsersIndex', ['data', 'total', 'loading', 'relationships']),
    },
    watch: {
        query: {
            handler(query) {
                this.setQuery(query)
            },
            deep: true
        }
    },
    methods: {
        ...mapActions('UsersIndex', ['fetchData', 'setQuery', 'resetState']),
        search(e) {
            this.query.offset = 0;
            this.query.search = e.target.value;
        }
    }
}
</script>


<style scoped>

</style>
