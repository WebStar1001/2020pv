import UserPolicy from './UserPolicy';
import PaymentPolicy from './PaymentPolicy';
import MenuPolicy from "./MenuPolicy";

export default class Gate
{
    constructor(user)
    {
        this.user = user;

        this.policies = {
            menu: MenuPolicy,
            user: UserPolicy,
            payment: PaymentPolicy
        };
    }

    before()
    {
    }

    allow(action, type, model = null)
    {
        if (this.before()) {
            return true;
        }

        return this.policies[type][action](this.user, model);
    }

    deny(action, type, model = null)
    {
        return ! this.allow(action, type, model);
    }
}