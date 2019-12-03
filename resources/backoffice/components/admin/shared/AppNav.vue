<template>
 <div>
        <v-list class="py-0">
            <v-list-item>
                <v-list-item-icon v-show="$vuetify.breakpoint.mdAndUp && mini">
                    <v-btn small icon @click.native.stop="navToggle" class="mx-0">
                        <v-icon>mdi-chevron-right</v-icon>
                    </v-btn>
                </v-list-item-icon>
                <v-list-item-content>
                </v-list-item-content>
                <v-list-item-icon v-show="$vuetify.breakpoint.mdAndUp && !mini">
                    <v-btn small icon @click.native.stop="navToggle" class="mx-0">
                        <v-icon>mdi-chevron-left</v-icon>
                    </v-btn>
                </v-list-item-icon>
            </v-list-item>
        </v-list>
        <v-list class="py-0" dense v-for="(group, index) in items" :key="index">
            <v-divider class="mb-2" :class="{ 'mt-0': !index, 'mt-2': index }" v-if="group.length"></v-divider>
            <template v-for="item in group">
                <v-list-group v-if="!!item.items" :prepend-icon="item.icon" @click.native="item.action && mini ? item.action() : false" :key="item.title">
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title>{{ item.title }}</v-list-item-title>
                        </v-list-item-content>
                    </template>
                    <v-list-item v-for="subItem in item.items" :key="subItem.title" @click="subItem.action ? subItem.action() : false" :to="subItem.to" ripple :exact="subItem.exact !== undefined ? subItem.exact : true" :disabled="subItem.disabled">
                        <v-list-item-content class="pl-2">
                            <v-list-item-title>{{ subItem.title }}</v-list-item-title>
                        </v-list-item-content>
                        <v-list-item-icon>
                            <v-icon>{{ subItem.icon }}</v-icon>
                        </v-list-item-icon>
                    </v-list-item>
                </v-list-group>
                <v-list-item v-else @click.native="item.action ? item.action() : false" :disabled="(item.role && auth && item.role !== auth.properties.role) || item.disabled" href="javascript:void(0)" :to="item.to" ripple :exact="item.exact !== undefined ? item.exact : true" :key="item.title">
                    <v-list-item-icon>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>{{ item.title}}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </template>
        </v-list>
   </div>
</template>
<script>
import { mapGetters } from 'vuex'

export default {
    data: () => ({
        items: [],
        name: null,
    }),
    props: ['mini'],
    computed: mapGetters({
        auth: 'auth/user',
    }),
    watch: {
        authName(val) {
            if (val) {
                this.name = val
            }
        },
    },
    mounted() {
        this.navigation()
        this.name = this.auth.name
    },
    methods: {
        navToggle() {
            this.$emit('navToggle')
        },

        async logout() {
            await this.$store.dispatch('auth/logout')
            this.$toast.info('You are logged out.')
            this.$router.push({ name: 'login' })
        },
        async refresh() {

            this.$emit('overlay', true)

            await setTimeout(async () => {

                this.sync(this.$store)



                this.$emit('overlay', false)
            }, 3000)



        },
        update() {
            window.location.reload()
        },

        navigation() {
            const inventory = [
                { title: 'Suppliers', icon: 'mdi-truck', to: { name: 'vendors' }, exact: true, disabled: false },
                { title: 'Stock Card', icon: 'mdi-clipboard-text', to: { name: 'stockcard' }, exact: true, disabled: true },
                { title: 'Stock Take', icon: 'mdi-format-list-checks', to: { name: 'stocktake' }, exact: true, disabled: true },
            ]
            const products = [
                { title: 'Standard Product', icon: 'mdi-package', to: { name: 'products' }, exact: true, disabled: false },
                { title: 'Variant Product', icon: 'mdi-package', to: { name: 'variants' }, exact: true, disabled: false },
                { title: 'Composite Product', icon: 'mdi-package', to: { name: 'composites' }, exact: true, disabled: false },
            ]
            const services = [
                { title: 'Standard Service', icon: 'mdi-face', to: { name: 'services.standard' }, exact: true, disabled: false },
                { title: 'Variant Service', icon: 'mdi-face', to: { name: 'services.variant' }, exact: true, disabled: false },
                { title: 'Composite Service', icon: 'mdi-face', to: { name: 'services.composite' }, exact: true, disabled: false },
            ]

            this.items = [
                [
                    { title: 'Home', icon: 'mdi-store', to: { name: 'home' }, exact: true, disabled: false }
                ],
                [
                    { title: 'Appointment', icon: 'mdi-calendar-today', to: { name: 'appointments' }, exact: true, disabled: true }
                ],
                [
                    { title: 'Transaction', icon: 'mdi-receipt', to: { name: 'sales' }, exact: true, disabled: false }
                ],
                [{
                    title: 'Inventory',
                    icon: 'mdi-package-down',
                    items: inventory,
                    action: () => {
                        this.$emit('status-changed', false)
                    },
                    exact: true, disabled: false
                }],
                [
                    { title: 'Customers', icon: 'mdi-account-box', to: { name: 'customers' }, role: 'MGR', exact: true, disabled: false }
                ],
                [{
                    title: 'Products',
                    icon: 'mdi-package',
                    items: products,
                    action: () => {
                        this.$emit('status-changed', false)
                    },
                    exact: true, disabled: false
                }],
                [{
                    title: 'Services',
                    icon: 'mdi-face',
                    items: services,
                    action: () => {
                        this.$emit('status-changed', false)
                    },
                    exact: true, disabled: false
                }],
                [
                    { title: 'Employees', icon: 'mdi-worker', to: { name: 'users' }, role: 'MGR', exact: true, disabled: false }
                ],
                [
                    { title: 'Reports', icon: 'mdi-file-chart', to: { name: 'reports' }, role: 'MGR', exact: true, disabled: false }
                ],
                [
                    { title: 'Setting', icon: 'mdi-settings', to: { name: 'setting' }, role: 'MGR', exact: true, disabled: false }
                ],
            ]
        }
    }
}

</script>
