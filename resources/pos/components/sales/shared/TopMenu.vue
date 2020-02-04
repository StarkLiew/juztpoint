<template>
    <v-app-bar dark dense flat color="secondary">
        <v-menu transition="slide-x-transition" bottom right>
            <template v-slot:activator="{ on }">
                <v-btn icon v-on="on">
                    <v-icon>mdi-dots-vertical</v-icon>
                </v-btn>
            </template>
            <v-list>
                 <v-list-item>
                    <v-list-item-icon>
                        <v-icon>mdi-account</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>{{ auth ? auth.name : ''}}</v-list-item-content>
                </v-list-item>     
                <v-divider></v-divider>
                <v-list-item @click="resetDialog = true">
                    <v-list-item-icon>
                        <v-icon>mdi-plus</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>New Sale</v-list-item-content>
                </v-list-item>
                <v-list-item to="{name: 'pos'}">
                    <v-list-item-icon>
                        <v-icon>mdi-close</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>Exit</v-list-item-content>
                </v-list-item>
            </v-list>
        </v-menu>
        <v-text-field hide-details v-model="searchText" prepend-icon="mdi-magnify" @change="search()" clear single-line clearable></v-text-field>

        <v-spacer></v-spacer>
        <v-dialog v-model="resetDialog" persistent max-width="290">
            <v-card>
                <v-card-title class="headline">Proceed?</v-card-title>
                <v-card-text>Are you sure to proceed with new transaction? Current entry will be lost.</v-card-text>
                <v-card-actions>
                    <div class="flex-grow-1"></div>
                    <v-btn color="green darken-1" text @click="resetDialog = false">No</v-btn>
                    <v-btn color="blue darken-1" text @click="resetData()">Yes</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-app-bar-nav-icon @click.stop="cartToggle"></v-app-bar-nav-icon>
    </v-app-bar>
</template>
<script>
import { mapGetters } from 'vuex'
import { settings } from '~/config'

export default {
    data: () => ({
        siteName: settings.siteName,
        overlay: false,
        resetDialog: false,
        searchText: '',
    }),
    computed: mapGetters({
        auth: 'auth/user'
    }),
    methods: {
        refresh() {
            this.$emit('overlay', true)

            setTimeout(() => {

                /* Fetch latest data */
                this.$store.dispatch('user/fetchUsers')
                this.$store.dispatch('product/fetchProducts')
                this.$store.dispatch('account/fetchCustomers')
                this.$emit('overlay', false)
            }, 3000)



        },
        resetData() {
            this.$emit('reset')
            this.searchText = ''
            this.resetDialog = false
        },
        search() {
            this.$emit('search', this.searchText)
        },
        cartToggle() {
            this.$emit('cart-toggle')
        }
    }
}

</script>
