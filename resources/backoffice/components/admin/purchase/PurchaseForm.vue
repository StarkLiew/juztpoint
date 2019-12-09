<template>
    <v-container>
        <v-row>
            <v-col cols="12" sm="12" md="4" lg="4">
                <v-autocomplete v-model="editedItem.account" :items="vendors" :rules="[v => !!v || 'Account is required',]" required :loading="loading" item-text="name" label="Supplier" placeholder="Choose" prepend-icon="mdi-truck" return-object></v-autocomplete>
                <v-menu ref="menu" v-model="showDatePicker" :close-on-content-click="false" transition="scale-transition" offset-y min-width="290px">
                    <template v-slot:activator="{ on }">
                        <v-text-field class="" v-model="editedItem.date" label="Date" prepend-icon="mdi-calendar" v-on="on" readonly :rules="[v => !!v || 'Date is required',]" required></v-text-field>
                    </template>
                    <v-date-picker @input="showDatePicker = false" v-model="editedItem.date"></v-date-picker>
                </v-menu>
            </v-col>
            <v-col cols="12" sm="12" md="4" lg="4">
                <v-text-field label="PO #" v-model="editedItem.reference" :rules="[v => !!v || 'PO number is required',]" required></v-text-field>
                <v-text-field label="SO #" v-model="editedItem.properties.so"></v-text-field>
            </v-col>
            <v-col cols="12" sm="12" md="4" lg="4">
                <v-textarea solo name="input-7-4" v-model="editedItem.note" label="Note"></v-textarea>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" sm="12" md="12" lg="12">
                <v-data-table hide-default-footer disable-pagination :headers="itemHeaders" :items="editedItem.items" class="elevation-1">
                    <template v-slot:item.action="{item}">
                        <v-icon class="mr-2" color="blue darken-1" @click="edit(item)">
                            mdi-pencil
                        </v-icon>
                        <v-icon class="mr-2" color="red darken-1" @click="remove(item)">
                            mdi-close
                        </v-icon>
                    </template>
                    <template v-slot:footer="{pagination}">
                        <v-toolbar>
                            <v-dialog v-model="editItemDialog" fullscreen persistent max-width="600px">
                                <template v-slot:activator="{ on }">
                                    <v-btn text v-on="on" small color="primary">
                                        <v-icon>mdi-plus-circle-outline</v-icon>
                                        Add a new item
                                    </v-btn>
                                </template>
                                <purchase-line :line="selectedLine" :collection="editedItem.items" @close="editItemDialog = false" @save="updateItems"></purchase-line>
                            </v-dialog>
                            <v-spacer></v-spacer>
                            <v-toolbar-title>Total</v-toolbar-title>
                            <v-toolbar-title>{{ 0 | currency }}</v-toolbar-title>
                        </v-toolbar>
                    </template>
                </v-data-table>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
import Vue from 'vue'
import { mapGetters } from 'vuex'
import PurchaseLine from './PurchaseLine'

export default {
    computed: mapGetters({
        vendors: 'account/items',
    }),
    components: {
        PurchaseLine,
    },
    data: () => {
        return {
            showDatePicker: false,
            editedItem: {
                account_id: '',
                account: '',
                note: '',
                reference: '',
                properties: {
                    so: '',
                },
                items: [],
                formData: null,
            },
            selectedLine: null,
            searchSuppliers: null,
            supplierItems: [],
            loading: false,
            itemHeaders: [

                { text: 'Item', value: 'item.sku' },
                { text: 'Description value', value: 'note' },
                { text: 'Price', value: 'qty', align: 'end' },
                { text: 'Price', value: 'price', align: 'end', currency: true },
                { text: 'Discount', value: 'discount_rate', align: 'end' },
                { text: 'Tax', value: 'tax.name' },
                { text: 'Amount', value: 'total_amount', align: 'end', currency: true },
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
        updateItems(line) {
            if (!this.selectedLine) {
                if (this.editedItem.items.length < 1) line.line = 1
                else {
                    const last = this.editedItem.items[this.editedItem.items.length - 1]
                    line.line = last.line + 1
                }


                this.editedItem.items.push({ ...line })
            } else {
                const index = this.editedItem.items.findIndex(v => v.line === line.line)
                Vue.set(this.editedItem.items, index, { ...line })
                this.selectedLine = null

            }
            this.editItemDialog = false
        },
        edit(line) {
            this.selectedLine = line
            this.editItemDialog = true
        },
        remove(line) {
            const index = this.editedItem.items.findIndex(v => v.line === line.line)
            this.editedItem.items.splice(index, 1)


        },
        pickedDate() {

            this.showDatePicker = false
        }
    }

}

</script>
