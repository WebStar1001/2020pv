export default class UserPolicy
{
    static view(user, boolean)
    {
        return boolean
    }

    static delete(user, boolean)
    {
        return boolean
    }

    static update(user, boolean)
    {
        return boolean
    }

    static superAdmin(user)
    {
      return user.role_id === 1
    }
}