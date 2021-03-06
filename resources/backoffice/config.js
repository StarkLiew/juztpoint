const siteUrl = Laravel.siteUrl,
    apiUrl = Laravel.apiUrl

export const settings = {
    siteName: Laravel.siteName,
    siteUrl: Laravel.siteUrl,
}

export const swatches = [
        ['#F44336', '#E91E63', '#9C27B0'],
        ['#D32F2F', '#C62828', '#B71C1C'],
        ['#C2185B', '#AD1457', '#880E4F'],
        ['#7B1FA2', '#6A1B9A', '#4A148C'],
        ['#673AB7', '#3F51B5', '#2196F3'],
        ['#512DA8', '#4527A0', '#311B92'],
        ['#303F9F', '#283593', '#1A237E'],
        ['#1976D2', '#1565C0', '#0D47A1'],
        ['#03A9F4', '#00BCD4', '#009688'],
        ['#0288D1', '#0277BD', '#01579B'],
        ['#0097A7', '#00838F', '#00695C'],
        ['#01579B', '#006064', '#004D40'],
        ['#4CAF50', '#388E3C', '#2E7D32'],
        ['#1B5E20', '#33691E', '#827717'],
        ['#F57F17', '#FF6F00', '#E65100'],
        ['#FF5722', '#795548', '#607D8B'],
        ['#E64A19', '#D84315', '#BF360C'],
        ['#5D4037', '#4E342E', '#3E2723'],
        ['#455A64', '#37474F', '#263238'],

    ]

    export const durations = [{
            value: 10,
            name: "10min"
        },
        {
            value: 15,
            name: "15min"
        },
        {
            value: 20,
            name: "20min"
        },
        {
            value: 25,
            name: "25min"
        },
        {
            value: 30,
            name: "30min"
        },
        {
            value: 35,
            name: "35min"
        },
        {
            value: 40,
            name: "40min"
        },
        {
            value: 45,
            name: "45min"
        },
        {
            value: 50,
            name: "50min"
        },
        {
            value: 55,
            name: "55min"
        },
        {
            value: 60,
            name: "1h"
        },
        {
            value: 65,
            name: "1h 5min"
        },
        {
            value: 70,
            name: "1h 10min"
        },
        {
            value: 75,
            name: "1h 15min"
        },
        {
            value: 80,
            name: "1h 20min"
        },
        {
            value: 85,
            name: "1h 25min"
        },
        {
            value: 90,
            name: "1h 30min"
        },
        {
            value: 95,
            name: "1h 35min"
        },
        {
            value: 100,
            name: "1h 40min"
        },
        {
            value: 105,
            name: "1h 45min"
        },
        {
            value: 110,
            name: "1h 50min"
        },
        {
            value: 115,
            name: "1h 55min"
        },
        {
            value: 120,
            name: "2h"
        },
        {
            value: 135,
            name: "2h 15min"
        },
        {
            value: 150,
            name: "2h 30min"
        },
        {
            value: 165,
            name: "2h 45min"
        },
        {
            value: 180,
            name: "3h"
        },
        {
            value: 195,
            name: "3h 15min"
        },
        {
            value: 210,
            name: "3h 30min"
        },
        {
            value: 225,
            name: "3h 45min"
        },
        {
            value: 240,
            name: "4h"
        },
        {
            value: 270,
            name: "4h 30min"
        },
        {
            value: 300,
            name: "5h"
        },
        {
            value: 330,
            name: "5h 30min"
        },
        {
            value: 360,
            name: "6h"
        },
        {
            value: 390,
            name: "6h 30min"
        },
        {
            value: 420,
            name: "7h"
        },
        {
            value: 450,
            name: "7h 30min"
        },
        {
            value: 480,
            name: "8h"
        },
        {
            value: 540,
            name: "9h"
        },
        {
            value: 600,
            name: "10h"
        },
        {
            value: 660,
            name: "11h"
        },
        {
            value: 720,
            name: "12h"
        },

    ]

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
        url: 'auth/login',
        refresh: 'refresh'
    },
    receipt: 'receipt',

    logout: {
        url: 'auth/logout'
    },
    resend: {
        url: 'resend'
    },
    register: {
        url: 'auth/register',
        refresh: 'refresh'
    },
    forgot: {
        url: 'auth/password/email',
    },
    password: {
        url: 'auth/password',
        forgot: 'email',
        reset: 'reset',
        validate: 'validate',
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
