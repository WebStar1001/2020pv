<template>
        <section class="content-box">
            <div class="box-header">
                <h3 class="box-title">Dispute Details</h3>
            </div>

            <div class="mb-3">
                <back-buttton></back-buttton>
            </div>

            <div v-if="loading" class="loading-box">
                <i class="fa fa-spin fa-refresh"></i> Loading
            </div>

<pre v-if="item">
{{ JSON.stringify(item, null, 4) }}
</pre>

        </section>
</template>


<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    data() {
        return {
            // Code...
        }
    },
    created() {
        this.fetchData(this.$route.params.id)
    },
    destroyed() {
        this.resetState()
    },
    computed: {
        ...mapGetters('DisputesSingle', ['item', 'loading'])
    },
    watch: {
        "$route.params.id": function() {
            this.resetState();
            this.fetchData(this.$route.params.id)
        }
    },
    methods: {
        ...mapActions('DisputesSingle', [
            'fetchData',
            'resetState',
        ]),
    }
}
</script>


<style scoped>

</style>
