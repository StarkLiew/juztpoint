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
  ...applyRules(['auth'], [
    { path: '/', component: require('$backoffice/admin/AdminWrapper').default, children:
      [
        { path: '', name: 'index', redirect: { name: 'profile' } },
        { path: '/profile', component: require('$backoffice/admin/profile/ProfileWrapper').default, children:
          [
            { path: '', name: 'profile', component: require('$backoffice/admin/profile/Profile').default },
            { path: '/edit', name: 'profile-edit', component: require('$backoffice/admin/profile/edit/ProfileEdit').default }
          ]
        },
        { path: '/customers', component: require('$backoffice/admin/customer/CustomerWrapper').default, children:
          [
            { path: '', name: 'customers', component: require('$backoffice/admin/customer/Customer').default },
         
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
