<template>
    <v-dialog v-model="show" scrollable persistent max-width="600">
        <v-card class="mx-auto" style="height:90vh;">
            <v-toolbar flat dark color="primary">
                <v-btn icon dark @click="close()">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-spacer></v-spacer>
                <v-btn icon dark text @click="showAdd = true">
                    New
                </v-btn>
            </v-toolbar>
            <v-card-text style="">
                <v-list dense v-for="(a,i) in alphabets" :key="i">
                    <v-subheader>{{a}}</v-subheader>
                    <v-list-item-group v-model="customer" color="primary">
                        <v-list-item v-for="(customer, i) in mapCustomers(a)" :key="i" @click="selected(customer)">
                            <v-list-item-icon>
                                <v-icon>mdi-account</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>
                                <v-list-item-title>{{ customer.name }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider></v-divider>
                    </v-list-item-group>
                </v-list>
                <customer-add :show="showAdd" @close="showAdd = false" @save="saveNewCustomer"></customer-add>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script>
import { mapGetters } from 'vuex'
import CustomerAdd from './CustomerAdd'
export default {
    data: () => ({
        alphabets: ['#', 'A', 'B', 'C', 'D', 'E', 'F', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'],
        customer: null,
        showAdd: false,
    }),
    props: ['show'],
    components: {
        CustomerAdd,
    },
    computed: mapGetters({
        customers: 'account/customers',
        terminal: 'auth/terminal',
    }),
    methods: {
        mapCustomers(alpha) {
           /* let customers = this.$store.getters['account/customers']
            if (!customers) return []*/
                
            return this.customers.filter(row => row.name.toString().toLowerCase().startsWith(alpha.toLowerCase()))
        },
        selected(customer) {
            this.$emit('selected', customer)
        },
        close() {
            this.$emit('close')
        },
        async saveNewCustomer(customer) {
            customer.uid = this.terminal.id
            customer = await this.$store.dispatch('account/addCustomer', customer)
            this.$emit('selected', customer)
            this.showAdd = false
        }
    }
}

</script>
