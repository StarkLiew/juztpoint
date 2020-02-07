export default [
    ...applyRules(['guest'], [{
        path: '/',
        component: require('$noah/main/MainWrapper').default,
        redirect: { name: 'home' },
        children: [
            { path: '/', name: 'home', component: require('$noah/main/home/Home').default },
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
