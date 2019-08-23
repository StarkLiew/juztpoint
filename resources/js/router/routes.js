export default [
  ...applyRules(['guest'], [
    { path: '/pos', component: require('$comp/auth/AuthWrapper').default, redirect: { name: 'login' }, children:
      [
        { path: '/pos/login', name: 'login', component: require('$comp/auth/login/Login').default },
        { path: '/register', name: 'register', component: require('$comp/auth/register/Register').default },
        { path: '/password', component: require('$comp/auth/password/PasswordWrapper').default, children:
          [
            { path: '', name: 'forgot', component: require('$comp/auth/password/password-forgot/PasswordForgot').default },
            { path: 'reset/:token', name: 'reset', component: require('$comp/auth/password/password-reset/PasswordReset').default }
          ]
        }
      ]
    },
  ]),
  ...applyRules(['auth'], [
    { path: '/pos/sales',  name: 'sales', component: require('$comp/sales/SalesWrapper').default},
    { path: '/pos/receipt', name: 'receipts', component: require('$comp/admin/receipt/ReceiptWrapper').default },
    { path: '/pos', component: require('$comp/admin/AdminWrapper').default, children:
      [
        { path: '/pos', name: 'index', redirect: { name: 'profile' } },
        { path: '/pos/profile', component: require('$comp/admin/profile/ProfileWrapper').default, children:
          [
            { path: '/pos', name: 'profile', component: require('$comp/admin/profile/Profile').default },
            { path: '/pos/edit', name: 'profile-edit', component: require('$comp/admin/profile/edit/ProfileEdit').default }
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
