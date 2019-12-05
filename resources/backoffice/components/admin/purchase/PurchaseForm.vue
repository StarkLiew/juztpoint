<template>
    <v-container>
        <v-row>
            <v-col cols="12" sm="12" md="4" lg="4">
                <v-autocomplete v-model="editedItem.account_id" :items="vendors" :loading="loading" item-text="name" item-value="id" label="Supplier" placeholder="Choose" prepend-icon="mdi-truck"></v-autocomplete>
                <v-menu ref="menu" v-model="showDatePicker" :close-on-content-click="false" :return-value.sync="editedItem.date" transition="scale-transition" offset-y min-width="290px">
                    <template v-slot:activator="{ on }">
                        <v-text-field class="" v-model="editedItem.date" label="Date" prepend-icon="mdi-calendar" readonly v-on="on"></v-text-field>
                    </template>
                    <v-date-picker @click:date="showDatePicker = false" v-model="editedItem.date" no-title scrollable></v-date-picker>
                    <v-spacer></v-spacer>
                    <v-btn text color="primary" @click="showDatePicker = false">Cancel</v-btn>
                </v-menu>
            </v-col>
            <v-col cols="12" sm="12" md="4" lg="4">
                <v-text-field label="PO #" v-model="editedItem.reference"></v-text-field>
                <v-text-field label="SO #" v-model="editedItem.properties.so"></v-text-field>
            </v-col>
            <v-col cols="12" sm="12" md="4" lg="4">
                <v-textarea solo name="input-7-4" v-model="editedItem.note" label="Note"></v-textarea>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" sm="12" md="12" lg="12">
                <v-data-table hide-default-footer disable-pagination :headers="itemHeaders" :items="editedItem.items" class="elevation-1">
                    <template v-slot:footer="{pagination}">
                        <v-toolbar>
                            <v-dialog v-model="editItemDialog" persistent max-width="600px">
                                <template v-slot:activator="{ on }">
                                    <v-btn text v-on="on" small color="primary">
                                        <v-icon>mdi-plus-circle-outline</v-icon>
                                        Add a new item
                                    </v-btn>
                                </template>
                                <v-card>
                                    <v-card-title>
                                        <span class="headline">
                                            New Item
                                        </span>
                                        <v-spacer></v-spacer>
                                        <v-btn fab light small top right @click="editItemDialog = false">
                                            <v-icon>mdi-close</v-icon>
                                        </v-btn>
                                    </v-card-title>
                                    <v-card-text>
                                        <v-container>
                                            <v-row>
                                                <v-col cols="12" sm="12" md="12" lg="12">
                                                    <v-autocomplete v-model="editLine.item_id" :items="supplierItems" :loading="loading" :search-input.sync="searchSuppliers" hide-selected item-text="name" item-value="id" label="Item" placeholder="Choose" prepend-icon="mdi-database-search" return-object></v-autocomplete>
                                                    <v-text-field label="Description" v-model="editLine.note"></v-text-field>
                                                    <v-text-field label="Qty" v-model="editLine.qty"></v-text-field>
                                                    <v-text-field label="Price" v-model="editLine.price"></v-text-field>
                                                    <v-text-field label="Discount" v-model="editLine.discount"></v-text-field>
                                                    <v-text-field label="Tax" v-model="editLine.tax_id"></v-text-field>
                                                </v-col>
                                            </v-row>
                                        </v-container>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="primary" @click="editItemDialog = false">
                                            Save
                                        </v-btn>
                                        <v-btn color="warning" @click="editItemDialog = false">
                                            Cancel
                                        </v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                            <v-spacer></v-spacer>
                            <v-toolbar-title>Total </v-toolbar-title>
                            <v-toolbar-title>0.00</v-toolbar-title>
                        </v-toolbar>
                    </template>
                </v-data-table>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
import { mapGetters } from 'vuex'

export default {
    computed: mapGetters({
        vendors: 'account/items',
    }),
    data: () => {
        return {
            showDatePicker: false,

            editedItem: {
                account_id: '',
                note: '',
                reference: '',
                properties: {
                    so: '',
                },
                items: [],
                formData: null,
            },
            editLine: {
                item_id: '',
                line: 1,
                type: 'po',
                trxn_id: '',
                item: {},
                note: '',
                qty: 0,
                price: 0,
                discount: {},
                discount_amount: 0,
                tax_id: 0,
                tax_amount: {},
                total_amount: 0,
            },
            searchSuppliers: null,
            supplierItems: [],
            loading: false,
            itemHeaders: [
                { text: 'Item', value: 'item' },
                { text: 'Description value', value: 'note' },
                { text: 'Price', value: 'price' },
                { text: 'Discount', value: 'discount' },
                { text: 'Tax', value: 'tax' },
                { text: 'Amount', value: 'total_amount' },
                { text: 'Actions', value: 'action', sortable: false },
            ],
            editItemDialog: false,

        }
    },
    async mounted() {
           this.initialize()
    },
    methods: {
        async initialize() {

            this.loading = true

            await this.$store.dispatch('account/fetch', { type: 'vendor', search: '', limit: 0, page: 1, sort: [], desc: [], noCommit: false, })
     

            this.loading = false
        },
    }

}

</script>
