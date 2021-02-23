export default class MenuPolicy
{
    static dashboard(user)
    {
        return true
    }

    static reading_history(user)
    {
        return this.isCustomer(user) || this.isAdvisor(user)
    }

    static payments(user)
    {
        return this.isCustomer(user) || this.isAdvisor(user)
    }

    static users(user)
    {
        return this.isSuperAdmin(user) || this.isAdmin(user)
    }

    static settings(user)
    {
        return true;
    }

    static settings_payment(user)
    {
        return this.isAdvisor(user)
    }

    static settings_withdrawals(user)
    {
        return this.isAdvisor(user)
    }

    static pending_payment_emails(user)
    {
        return this.isSuperAdmin(user) || this.isAdmin(user)
    }

    static payouts(user)
    {
        return this.isSuperAdmin(user)
    }

    static transactions(user)
    {
        return this.isSuperAdmin(user)
    }

    static disputes(user)
    {
        return this.isSuperAdmin(user)
    }

    static reports(user)
    {
        return this.isSuperAdmin(user) || this.isAdmin(user)
    }

    static my_clients(user)
    {
        return this.isAdvisor(user)
    }

    static my_psychics(user)
    {
        return this.isCustomer(user)
    }

    static chat_sessions(user)
    {
        return this.isSuperAdmin(user) || this.isAdmin(user)
    }

    static email_subjects(user)
    {
        return this.isSuperAdmin(user) || this.isAdmin(user)
    }

    static newsletter(user)
    {
        return this.isSuperAdmin(user) || this.isAdmin(user)
    }

    static discounts(user)
    {
        return this.isSuperAdmin(user) || this.isAdmin(user)
    }

    static global_settings(user)
    {
        return this.isSuperAdmin(user) || this.isAdmin(user)
    }

    static messages(user)
    {
        return this.isCustomer(user) || this.isAdvisor(user)
    }


    static isSuperAdmin(user)
    {
      return user.role_id === 1
    }

    static isAdmin(user)
    {
        return user.role_id === 2
    }

    static isCustomer(user)
    {
        return user.role_id === 3
    }

    static isAdvisor(user)
    {
        return user.role_id === 4
    }
}