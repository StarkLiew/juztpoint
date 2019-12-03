<template>
    <v-navigation-drawer fixed app :permanent="$vuetify.breakpoint.mdAndUp" light :mini-variant.sync="$vuetify.breakpoint.mdAndUp && mini" :clipped="$vuetify.breakpoint.mdAndUp" v-model="mini" width="300">
        <v-list class="py-0">
            <v-list-item>
                <v-list-item-icon v-show="$vuetify.breakpoint.mdAndUp && mini">
                    <v-btn small icon @click.native.stop="navToggle" class="mx-0">
                        <v-icon>mdi-chevron-right</v-icon>
                    </v-btn>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title>{{ store ? store.name : '' }}</v-list-item-title>
                    <v-list-item-title class="caption">{{ terminal ? terminal.name : ''}}</v-list-item-title>
                    <v-list-item-title class="caption">Version 1.0.1</v-list-item-title>
                </v-list-item-content>
                <v-list-item-icon>
                    <v-btn small icon @click.native.stop="navToggle" class="mx-0">
                        <v-icon>mdi-chevron-left</v-icon>
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
        mini: false,
    }),

    props: ['minified'],

    computed: mapGetters({
        auth: 'auth/user',
        store: 'auth/store',
        terminal: 'auth/terminal',
    }),

    watch: {
        authName(val) {
            if (val) {
                this.name = val
            }
        },
        mini(val) {

            this.$emit('nav-toggle-update', val)
        },
        minified(val) {

            this.mini = val
        },
    },

    mounted() {
        this.name = this.auth.name
        this.navigation()
    },

    methods: {
        navToggle() {
            this.$emit('nav-toggle')
        },

        async logout() {
            await this.$store.dispatch('auth/logout')
            this.$router.push({ name: 'pin' })
            this.$toast.info('You are logged out.')
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
            this.items = [
                [
                    { title: 'Sales', icon: 'mdi-cart', to: { name: 'sales' }, exact: true }
                ],
                [
                    { title: 'Receipts', icon: 'mdi-receipt', to: { name: 'receipts' }, role: 'MGR', exact: true }
                ],
                [
                    { title: 'Open/Close Shift', icon: 'mdi-store', to: { name: 'shift' }, role: 'MGR', exact: true }
                ],
                [
                    { title: 'Shift Report', icon: 'mdi-file-chart', to: { name: 'report' }, role: 'MGR', exact: true }
                ],
                [
                    { title: 'Settings', icon: 'mdi-settings', to: { name: 'settings' }, role: 'MGR', exact: true }
                ],
                [
                    //{ title: 'Profile', icon: 'person', to: {name: 'profile'}, exact: true }
                ],
                [
                    // { title: 'Update', icon: 'update', action: this.update, role:'MGR' }
                ],

                [
                    // { title: 'Refresh', icon: 'refresh', action: this.refresh, role:'MGR' }
                ],
                [
                    { title: 'Logout', icon: 'mdi-logout', action: this.logout }
                ]
            ]
        }
    }
}

</script>
