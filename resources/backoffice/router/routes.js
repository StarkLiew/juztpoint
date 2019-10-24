export default [
    ...applyRules(['guest'], [{
        path: '/',
        component: require('$backoffice/auth/AuthWrapper').default,
        redirect: { name: 'login' },
        children: [
            { path: '/login', name: 'login', component: require('$backoffice/auth/login/Login').default },
            { path: '/register', name: 'register', component: require('$backoffice/auth/register/Register').default },
            {
                path: '/password',
                component: require('$backoffice/auth/password/PasswordWrapper').default,
                children: [
                    { path: '', name: 'forgot', component: require('$backoffice/auth/password/password-forgot/PasswordForgot').default },
                    { path: 'reset/:token', name: 'reset', component: require('$backoffice/auth/password/password-reset/PasswordReset').default }
                ]
            }
        ]
    }, ]),
    ...applyRules(['auth'], [{
        path: '/',
        component: require('$backoffice/admin/AdminWrapper').default,
        children: [
            { path: '', name: 'index', redirect: { name: 'home' } },
            {
                path: '/home',
                component: require('$backoffice/admin/home/HomeWrapper').default,
                children: [
                    { path: '', name: 'home', component: require('$backoffice/admin/home/Home').default },
                ]
            },
            {
                path: '/profile',
                component: require('$backoffice/admin/profile/ProfileWrapper').default,
                children: [
                    { path: '', name: 'profile', component: require('$backoffice/admin/profile/Profile').default },
                    { path: '/edit', name: 'profile-edit', component: require('$backoffice/admin/profile/edit/ProfileEdit').default }
                ]
            },
            {
                path: '/customers',
                component: require('$backoffice/admin/customer/CustomerWrapper').default,
                children: [
                    { path: '', name: 'customers', component: require('$backoffice/admin/customer/Customer').default },

                ]
            },
            {
                path: '/vendors',
                component: require('$backoffice/admin/vendor/VendorWrapper').default,
                children: [
                    { path: '', name: 'vendors', component: require('$backoffice/admin/vendor/Vendor').default },

                ]
            },
            {
                path: '/products',
                component: require('$backoffice/admin/product/ProductWrapper').default,
                children: [
                    { path: '/standard', name: 'products', component: require('$backoffice/admin/product/product').default },
                    { path: '/variant', name: 'variants', component: require('$backoffice/admin/product/variant').default },
                    { path: '/composite', name: 'composites', component: require('$backoffice/admin/product/composite').default },
                ]
            },
            {
                path: '/services',
                component: require('$backoffice/admin/service/ServiceWrapper').default,
                children: [
                    { path: '', name: 'services', component: require('$backoffice/admin/service/service').default },
                ]
            },
            {
                path: '/users',
                component: require('$backoffice/admin/user/UserWrapper').default,
                children: [
                    { path: '', name: 'users', component: require('$backoffice/admin/user/User').default },
                ]
            },
            {
                path: '/reports',
                component: require('$backoffice/admin/report/ReportWrapper').default,
                children: [
                    { path: '', name: 'reports', component: require('$backoffice/admin/report/report').default },
                ]
            },
        ]
    }, ]),
    { path: '*', redirect: { name: 'index' } }
]

function applyRules(rules, routes) {
    for (let i in routes) {
        routes[i].meta = routes[i].meta || {}

        if (!routes[i].meta.rules) {
            routes[i].meta.rules = []
        }
        routes[i].meta.rules.unshift(...rules)

        if (routes[i].children) {
            routes[i].children = applyRules(rules, routes[i].children)
        }
    }

    return routes
}
