export default [
    ...applyRules(['guest'], [{
        path: '/',
        component: require('$pos/auth/AuthWrapper').default,
        redirect: { name: 'login' },
        children: [
            { path: '/login', name: 'login', component: require('$pos/auth/login/Login').default },
            { path: '/register', name: 'register', component: require('$pos/auth/register/Register').default },
            {
                path: '/password',
                component: require('$pos/auth/password/PasswordWrapper').default,
                children: [
                    { path: '', name: 'forgot', component: require('$pos/auth/password/password-forgot/PasswordForgot').default },
                    { path: 'reset/:token', name: 'reset', component: require('$pos/auth/password/password-reset/PasswordReset').default }
                ]
            }
        ]
    }, ]),
    ...applyRules(['auth'], [{
        path: '/',
        component: require('$pos/auth/AuthWrapper').default,
        redirect: { name: 'pin' },
        children: [
            { path: '/pin', name: 'pin', component: require('$pos/auth/login/Pin').default },
        ]
    }, ]),

    ...applyRules(['auth', 'pin'], [
        { path: '/sales', name: 'sales', component: require('$pos/sales/SalesWrapper').default },
        { path: '/trxn', name: 'trxn', component: require('$pos/trxn/TrxnWrapper').default },
        { path: '/receipt', name: 'receipts', component: require('$pos/admin/receipt/ReceiptWrapper').default },

        {
            path: '/',
            component: require('$pos/admin/AdminWrapper').default,
            children: [
                { path: '/', name: 'index', redirect: { name: 'shift' } },
                { path: '/shift', name: 'shift', component: require('$pos/admin/shift/ShiftWrapper').default },
                { path: '/report', name: 'report', component: require('$pos/admin/shift/ShiftReport').default },
                { path: '/setting', name: 'settings', component: require('$pos/admin/setting/SettingWrapper').default },
                {
                    path: '/profile',
                    component: require('$pos/admin/profile/ProfileWrapper').default,
                    children: [
                        { path: '/', name: 'profile', component: require('$pos/admin/profile/Profile').default },
                        { path: '/edit', name: 'profile-edit', component: require('$pos/admin/profile/edit/ProfileEdit').default }
                    ]
                },

            ]
        },
    ]),
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
