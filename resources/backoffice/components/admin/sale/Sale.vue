<template>
    <crud title="Sale Receipts" :headers="headers" :items='items' sort-by="name" :refresh="retrieve" :default-item="defaultItem" :options.sync="options" :save-method="save" :remove-method="remove" :server-items-length="count" :loading="loading" loading-text="Loading..." :export-fields="exportFields">
        <template v-slot:filter="{ options, refresh }">
            <v-menu ref="menu" v-model="options.dateMenu" :close-on-content-click="false" :return-value.sync="options.dates" transition="scale-transition" offset-y min-width="290px">
                <template v-slot:activator="{ on }">
                    <v-text-field class="mt-5 ml-2 mr-2" v-model="options.dateRangeText" label="Date range" prepend-icon="event" readonly v-on="on"></v-text-field>
                </template>
                <v-date-picker v-model="options.dates" no-title range scrollable></v-date-picker>
                <v-spacer></v-spacer>
                <v-btn text color="primary" @click="options.dateMenu = false">Cancel</v-btn>
                <v-btn text color="primary" @click="refresh">OK</v-btn>
                </v-date-picker>
            </v-menu>
        </template>
        <template v-slot:item.avatar="{item}">
            <v-avatar size="164" v-if="!!item.avatar">
                <img :src="item.avatar" alt="Thumbnail">
            </v-avatar>
            <v-avatar v-if="!item.avatar" :color="'grey'">
            </v-avatar>
        </template>
        <template v-slot:item.status="{item}">
            <v-icon color="success" v-if="item.status === 'active'">check</v-icon>
            <v-icon color="danger" v-if="item.status === 'inactive'">time</v-icon>
        </template>
         <template v-slot:item.buttons="{ item }">
            <v-icon small class="mr-2">
                print
            </v-icon>
            <v-icon small>
                cancel
            </v-icon>
            <v-icon small>
                money_off
            </v-icon>
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
                dates: [],
                dateRangeText: '',
                dateMenu: false,
            },
            loading: true,
            defaultItem: {
                reference: '',

            },
            headers: [
                { text: 'Date', value: 'date' },
                { text: 'Reference', value: 'reference' },
                { text: 'Customer #', value: 'customer.name', sortable: false },
                { text: 'Charge', value: 'charge', sortable: false, align: 'end' },
                { text: 'Shift', value: 'shift_id', sortable: false },
                { text: 'Terminal', value: 'terminal_id', sortable: false },
                { text: 'Store', value: 'store_id', sortable: false },
                { text: 'Cashier', value: 'transact_by', sortable: false },
                { text: 'Actions', value: 'buttons', sortable: false },
            ],
            exportFields: {
                'reference': 'reference',

            },
        }
    },
    computed: mapGetters({
        items: 'receipt/items',
        count: 'receipt/count',
    }),
    async mounted() {

    },
    methods: {
        async retrieve(search, options, noCommit = false) {

            this.loading = true
            const { sortBy, sortDesc, page, itemsPerPage } = options


            const results = await this.$store.dispatch('receipt/fetch', { search, limit: itemsPerPage, page, sort: sortBy, desc: sortDesc, noCommit })

            this.loading = false

            if (noCommit) return results
        },
        async save(item) {

            this.loading = true

            this.loading = false
        },
        async remove(item) {

            this.loading = true

            await this.$store.dispatch('receipt/trash', item)

            this.loading = false



        }
    },
}

</script>
