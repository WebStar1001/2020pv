<template>
    <div class="btn-group btn-group-sm" style="display:flex;">
        <router-link
                :to="{ name: 'admin.reviews.edit', params: { id: row.id } }"
               class="btn btn-primary m-1">
            <i class="fa fa-pencil"></i>
        </router-link>
        <button @click="destroyData(row.id)"
                type="button" class="btn btn-danger m-1">
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
                            'ReviewsIndex' + '/deleteReview',id
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
