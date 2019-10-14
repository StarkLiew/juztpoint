<template>
    <v-navigation-drawer fixed app  :value.sync="mini" :permanent="$vuetify.breakpoint.mdAndUp" light :mini-variant.sync="$vuetify.breakpoint.mdAndUp && mini" :clipped="$vuetify.breakpoint.mdAndUp" width="300">
        <v-list class="py-0">
            <v-list-item>
                <v-list-item-icon v-show="$vuetify.breakpoint.mdAndUp && mini">
                    <v-btn small icon @click.native.stop="navToggle" class="mx-0">
                        <v-icon>chevron_right</v-icon>
                    </v-btn>
                </v-list-item-icon>
                <v-list-item-content>
                </v-list-item-content>
                <v-list-item-icon>
                    <v-btn small icon @click.native.stop="navToggle" class="mx-0">
                        <v-icon>chevron_left</v-icon>
                    </v-btn>
                </v-list-item-icon>
            </v-list-item>
        </v-list>
        <v-list class="py-0" dense v-for="(group, index) in items" :key="index">
            <v-divider class="mb-2" :class="{ 'mt-0': !index, 'mt-2': index }" v-if="group.length"></v-divider>
            <template v-for="item in group">
                <v-list-group v-if="!!item.items" :prepend-icon="item.icon" no-action :key="item.title">
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title>{{ item.title }}</v-list-item-title>
                        </v-list-item-content>
                    </template>
                    <v-list-item v-for="subItem in item.items" :key="subItem.title" @click="subItem.action ? subItem.action() : false" :to="subItem.to" ripple :exact="subItem.exact !== undefined ? subItem.exact : true">
                        <v-list-item-content class="pl-2">
                            <v-list-item-title>{{ subItem.title }}</v-list-item-title>
                        </v-list-item-content>
                        <v-list-item-icon>
                            <v-icon>{{ subItem.icon }}</v-icon>
                        </v-list-item-icon>
                    </v-list-item>
                </v-list-group>
                <v-list-item v-else @click.native="item.action ? item.action() : false" :disabled="item.role && auth && item.role !== auth.properties.role" href="javascript:void(0)" :to="item.to" ripple :exact="item.exact !== undefined ? item.exact : true" :key="item.title">
                    <v-list-item-icon>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>{{ item.title }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </template>
        </v-list>
    </v-navigation-drawer>
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
            this.$emit('nav-toggle')
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
                { title: 'Vendors', icon: 'shopping_cart', to: { name: 'vendors' }, exact: true }
            ]

            this.items = [
                [
                    { title: 'Dashboard', icon: 'store', to: { name: 'sales' }, exact: true }
                ],
                [
                    { title: 'Transaction', icon: 'shopping_cart', to: { name: 'sales' }, exact: true }
                ],
                [{
                    title: 'Inventory',
                    icon: 'assignment_turned_in',
                    items: inventory,
                    action: () => {
                        alert('me')
                    },
                    exact: true
                }],
                [
                    { title: 'Customers', icon: 'account_box', to: { name: 'customers' }, role: 'MGR', exact: true }
                ],
                [
                    { title: 'Products', icon: 'view_array', to: { name: 'shift' }, role: 'MGR', exact: true }
                ],
                [
                    { title: 'Services', icon: 'face', to: { name: 'shift' }, role: 'MGR', exact: true }
                ],
                [
                    { title: 'Employees', icon: 'person', to: { name: 'users' }, role: 'MGR', exact: true }
                ],
                [
                    { title: 'Settings', icon: 'settings', to: { name: 'settings' }, role: 'MGR', exact: true }
                ],
            ]
        }
    }
}

</script>
