const siteUrl = Laravel.siteUrl,
    apiUrl = Laravel.apiUrl

export const settings = {
    siteName: Laravel.siteName
}

class URL {
    constructor(base) {
        this.base = base
    }

    path(path, args) {
        path = path.split('.')
        let obj = this,
            url = this.base

        for (let i = 0; i < path.length && obj; i++) {
            if (obj.url) {
                url += '/' + obj.url
            }

            obj = obj[path[i]]
        }
        if (obj) {
            url = url + '/' + (typeof obj === 'string' ? obj : obj.url)
        }

        if (args) {
            for (let key in args) {
                url = url.replace(':' + key, args[key])
            }
        }
      
        return url
    }
}
export const graphql = Object.assign(new URL(''), {
    query: 'graphql',
})

export const api = Object.assign(new URL(apiUrl), {
    url: '',

    login: {
        url: 'auth/pos/register',
        refresh: 'refresh'
    },
    receipt: 'receipt',
    logout: 'auth/logout',

    register: 'register',

    password: {
        url: 'password',
        forgot: 'email',
        reset: 'reset'
    },
    me: 'me',
    users: {
        url: 'users',

        activate: ':id/activate',
        single: ':id',
        restore: ':id/restore'
    },

    profile: {
        url: 'profile'
    }
})
