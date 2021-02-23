<template>
    <div>
        <router-link class="btn btn-mail"
                     :to="{ name: 'admin.messages.new_subject', params: {slug: row.customer.slug} }"
        >
            <img src="/images/admin/dashboard-mail.png" height="13" width="16" class="mr-1">
            Send Mail
        </router-link>
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
        }
    }
</script>


<style lang="scss" scoped>
    .btn-mail {
        background: white;
        color: #86909a;
        font-size: 10px;
        padding: 4px 10px;
        border-radius: 30px;
        border: 1px solid #cecece;
        transition: .5s;
        &:hover {
            background: #f2f4f6;
        }
    }
</style>
