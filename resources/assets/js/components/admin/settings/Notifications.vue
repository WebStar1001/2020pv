<template>
    <div>
        <div class="setting-title">Notifications</div>

        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div>
                    <strong>Notification of Discounts</strong>
                </div>
                You Will Be Notified When New Discount Is Started On Site.
            </div>
            <toggle-button
                    v-if="subscribed !== null"
                    :value="subscribed"
                    @change="updateSubscribed"
            />
        </div>
    </div>
</template>


<script>
    import { mapGetters, mapActions } from 'vuex';

    export default {
        data() {
            return {
            }
        },
        created() {
            this.setSubscribed(this.$gate.user.subscribed === 1);
        },
        destroyed() {
            this.resetState()
        },
        computed: {
            ...mapGetters('SettingsNotifications', [
                'loading',
                'subscribed'
            ]),
        },
        methods: {
            ...mapActions('SettingsNotifications', [
                'resetState',
                'setSubscribed',
                'updateNotifications'
            ]),
            updateSubscribed(e) {
                this.setSubscribed(e.value);

                this.updateNotifications()
                    .then(() => {

                    })
            }
        }
    }
</script>


<style scoped>

</style>
