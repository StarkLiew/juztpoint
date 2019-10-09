<template>
    <crud title="Customers" :headers="headers" :items='customers' sort-by="name" default-item="defaultItem" :options.sync="options" :server-items-length="customerCount" :loading="loading" loading-text="Loading...">
        <template v-slot:dialog="{ editedItem }">
            <v-container>
                <v-row>
                    <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.name" label="Name"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.status" label="Status"></v-text-field>
                    </v-col>
                </v-row>.
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
                sortBy: 'name',
                sortDesc: '', 
                page: 1,
                itemsPerPage: 2,
            },
            loading: true,
            defaultItem: {
                name: '',
                status: 'active',
            },
            headers: [
                { text: 'Name', value: 'name' },
                { text: 'Mobile #', value: 'properties.mobile' },
                { text: 'Active', value: 'status', filterable: false },
                { text: 'Actions', value: 'action', sortable: false },
            ]
        }
    },
    computed: mapGetters({
        customers: 'account/customers',
        customerCount: 'account/customerCount',
    }),
    watch: {
        options: {
            async handler() {
                alert('option')
                await this.retrieve()
            },
            deep: true,
        },
    },
    async mounted() {
        await this.retrieve()
    },
    methods: {
        async retrieve() {
            this.loading = true
            const { sortBy, sortDesc, page, itemsPerPage } = this.options
        
            await this.$store.dispatch('account/fetchCustomers', { limit: itemsPerPage, page })

            this.loading = false
        }
    },
}

</script>
