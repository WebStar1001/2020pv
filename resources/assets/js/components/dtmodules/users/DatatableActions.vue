<template>
    <div class="btn-group btn-group-sm" style="display:flex;">
        <router-link
                :to="{ name: 'admin.users.show', params: { id: row.id } }"
                v-if="!xprops.gate_name || $gate.allow('view', xprops.gate_name, row.rights.view)" class="btn btn-default m-1 ">
            <i class="fa fa-eye"></i>
        </router-link>
        <router-link
                :class="row.role_id === $roles.CUSTOMER || row.role_id === $roles.ADVISOR ? '' : 'disabled'"
                :to="{ name: 'admin.users.free_minutes', params: { id: row.id } }"
                v-if="!xprops.gate_name || $gate.allow('update', xprops.gate_name, row.rights.update)" class="btn btn-default m-1">
            Free&nbsp;Minutes
        </router-link>
        <router-link
                :to="{ name: 'admin.users.change_password', params: { id: row.id } }"
                v-if="!xprops.gate_name || $gate.allow('update', xprops.gate_name, row.rights.update)" class="btn btn-default m-1">
            <i class="fa fa-lock"></i>
        </router-link>
        <router-link
                :to="{ name: 'admin.users.edit', params: { id: row.id } }"
                v-if="!xprops.gate_name || $gate.allow('update',  xprops.gate_name, row.rights.update)" class="btn btn-primary m-1">
            <i class="fa fa-pencil"></i>
        </router-link>
        <button @click="destroyData(row.id)"
                type="button" class="btn btn-danger m-1"
                v-if="!xprops.gate_name || $gate.allow('delete',  xprops.gate_name, row.rights.delete)">
            <i class="fa fa-trash"></i>
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
        destroyData(id) {
            this.$swal({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                confirmButtonColor: '#dd4b39',
                focusCancel: true,
                reverseButtons: true
            }).then(result => {
                if (result.value) {
                    this.$store.dispatch(
                        this.xprops.module + '/destroyData',id
                    ).then(result => {
                        this.$eventHub.$emit('delete-success')
                    })
                }
            })
        }
    }
}
</script>


<style scoped>

</style>
