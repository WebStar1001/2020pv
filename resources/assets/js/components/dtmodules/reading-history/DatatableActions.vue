<template>
    <div>
        <div v-if="$gate.user.role_id === $roles.CUSTOMER">
            <button v-if="!row.deleted" @click="destroyData(row.id)" class="btn btn-default">
                Delete
            </button>
            <div v-if="row.deleted" class="text-danger">
                Deleted
            </div>
        </div>
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
                            this.xprops.module + '/destroyData', id
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
