<template>
    <crud title="Customers" :headers="headers" :items.sync='customers' sort-by="name" :refresh="retrieve" :default-item="defaultItem" :options.sync="options" :save-method="save" :remove-method="remove"  :server-items-length="customerCount" :loading="loading" loading-text="Loading...">
        <template v-slot:dialog="{ valid, editedItem }">
            <v-container>
                <v-row>
                    <v-col cols="12" sm="12" md="6" lg="6">
                        <v-text-field v-model="editedItem.name" :rules="[v => !!v || 'Name is required',]" required label="Name"></v-text-field>
                        <v-text-field v-model="editedItem.properties.mobile" label="Mobile"></v-text-field>
                        <v-text-field v-model="editedItem.properties.email" label="Email"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="12" md="6" lg="6">
                        <v-switch :value="editedItem.status" :true-value="'active'" :false-value="'inactive'" v-model="editedItem.status" inset :label="`Status: ${editedItem.status}`"></v-switch>
                    </v-col>
                </v-row>
            </v-container>
        </template>
    </crud>
</template>
<script>
import { mapGetters } from 'vuex'
import Crud from '../shared/Crud'
export default {
    components: {
        Crud,
    },
    data() {
        return {
            options: {
                sortBy: [],
                sortDesc: [],
                page: 1,
                itemsPerPage: 10,
            },
            loading: true,
            defaultItem: {
                name: '',
                status: 'active',
                properties: {
                    mobile: '',
                    email: '',
                }
            },
            headers: [
                { text: 'Name', value: 'name'  },
                { text: 'Mobile #', value: 'properties.mobile', sortable: false },
                { text: 'Active', value: 'status', filterable: false, sortable: false },
                { text: 'Actions', value: 'action', sortable: false },
            ]
        }
    },
    computed: mapGetters({
        customers: 'account/customers',
        customerCount: 'account/customerCount',
    }),
    async mounted() {
        //  await this.retrieve(this.options)
    },
    methods: {
        async retrieve(search, options) {

            this.loading = true
            const { sortBy, sortDesc, page, itemsPerPage } = options


            await this.$store.dispatch('account/fetchCustomers', {search, limit: itemsPerPage, page, sort: sortBy, desc: sortDesc })

            this.loading = false
        },
        async save(customer) {
            this.loading = true

            await this.$store.dispatch('account/addCustomer', customer)

            this.loading = false
        },
        async remove(customer) {

            this.loading = true

            await this.$store.dispatch('account/trashCustomer', customer)

            this.loading = false
        }
    },
}

</script>
