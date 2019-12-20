<template>
    <crud title="Variant Services" :headers="headers" :items='items' sort-by="name" :refresh="retrieve" :default-item="defaultItem" :options.sync="options" :save-method="save" :remove-method="remove" :server-items-length="count" :loading="loading" loading-text="Loading..." :export-fields="exportFields" @edit-dialog-changed='editDialogHandler' :groups="[{name:'Category', value: 'category', text: 'name'}]">
        <template v-slot:dialog="{ dialog, valid, editedItem }">
            <v-container>
                <v-row>
                    <v-col cols="12" sm="12" md="6" lg="6">
                        <v-row class="text-center">
                            <v-col cols="4" sm="4" md="4" lg="4">
                                <v-card min-height="164" justify="center">
                                    <v-avatar class="profile mt-1 mb-1" color="grey" size="164" tile>
                                        <v-img v-if="editedItem.thumbnail" :src="editedItem.thumbnail"></v-img>
                                    </v-avatar>
                                    <v-btn dark fab absolute top right small id="pick-avatar">
                                        <v-icon dark>add_a_photo</v-icon>
                                    </v-btn>
                                    <v-btn @click="clearThumbnail(editedItem)" absolute dark fab top left small v-if="editedItem.thumbnail" color="red">
                                        <v-icon dark>cancel</v-icon>
                                    </v-btn>
                                    <avatar-cropper :output-options="{height: 120, width: 120 }" ref="cropper" :labels="{ submit: 'Done', cancel: 'Cancel' }" @submit="handleSubmitted(editedItem)" trigger="#pick-avatar"></avatar-cropper>
                                </v-card>
                            </v-col>
                            <v-col cols="8" sm="8" md="8" lg="8">
                                <v-color-picker v-model="editedItem.properties.color" hide-mode-switch hide-inputs hide-canvas :swatches="swatches" show-swatches></v-color-picker>
                            </v-col>
                        </v-row>
                        <v-text-field v-model="editedItem.name" :rules="[v => !!v || 'Name is required',]" required label="Name"></v-text-field>
                        <v-text-field v-model="editedItem.sku" :rules="[v => !!v || 'SKU is required',]" required label="Service Code"></v-text-field>
                        <v-textarea clearable v-model="editedItem.note" clear-icon="mdi-close" label="Description"></v-textarea>
                        <v-select :loading="loading" :rules="[v => !!v || 'Category is required',]" required item-text="name" item-value="id" v-model="editedItem.cat_id" :items="categories" label="Category"></v-select>
                        <v-select :loading="loading" :rules="[v => !!v || 'Tax is required',]" required item-text="name" item-value="id" v-model="editedItem.tax_id" :items="taxes" label="Tax"></v-select>
                        <v-select :loading="loading" :rules="[v => !!v || 'Staff Commission is required',]" required item-text="name" item-value="id" v-model="editedItem.commission_id" :items="commissions" label="Staff Commission Rate"></v-select>
                    </v-col>
                    <v-col cols="12" sm="12" md="6" lg="6">
                        <v-switch :true-value="'active'" :false-value="'inactive'" v-model="editedItem.status" inset :label="`Active`"></v-switch>
                        <v-data-table disable-pagination :headers="variantHeaders" :items="editedItem.variants" class="elevation-1">
                            <template v-slot:item.value="{ item }">
                                <v-chip color="primary" v-for="value in item.value" :key="value" dark>{{ value }}</v-chip>
                            </template>
                            <template v-slot:top>
                                <v-toolbar flat color="white">
                                    <v-toolbar-title>Variant</v-toolbar-title>
                                    <v-divider class="mx-4" inset vertical></v-divider>
                                    <v-spacer></v-spacer>
                                    <v-dialog v-model="variantDialog" max-width="500px">
                                        <template v-slot:activator="{ on }">
                                            <v-btn color="primary" dark class="mb-2" v-on="on">Add</v-btn>
                                        </template>
                                        <v-card>
                                            <v-card-text>
                                                <v-container>
                                                    <v-row>
                                                        <v-col cols="12" sm="6" md="6">
                                                            <v-text-field v-model="editedVariantItem.name" label="Variant name" :disabled="editedVariantIndex > -1"></v-text-field>
                                                        </v-col>
                                                        <v-col cols="12" sm="6" md="6">
                                                            <v-combobox v-model="editedVariantItem.value" :search-input.sync="variantSearch" hide-selected hint="Color, Size" label="Variant value" multiple persistent-hint small-chips>
                                                                <template v-slot:no-data>
                                                                    <v-list-item>
                                                                        <v-list-item-content>
                                                                            <v-list-item-title>
                                                                                No results matching "<strong>{{ variantSearch }}</strong>". Press <kbd>enter</kbd> to create a new one
                                                                            </v-list-item-title>
                                                                        </v-list-item-content>
                                                                    </v-list-item>
                                                                </template>
                                                            </v-combobox>
                                                        </v-col>
                                                    </v-row>
                                                </v-container>
                                            </v-card-text>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="blue darken-1" text @click="closeVariant">Cancel</v-btn>
                                                <v-btn color="blue darken-1" text @click="saveVariant(editedItem)">Save</v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                </v-toolbar>
                            </template>
                            <template v-slot:item.action="{ item }">
                                <v-icon small class="mr-2" @click="editVariantItem(editedItem, item)">
                                    edit
                                </v-icon>
                                <v-icon small @click="deleteVariantItem(editedItem, item)">
                                    delete
                                </v-icon>
                            </template>
                            <template v-slot:no-data>
                                <div class="caption">Add some variant</div>
                            </template>
                        </v-data-table>
                        <v-data-table disable-pagination :headers="stockHeaders" :items="editedItem.properties.stocks" class="elevation-1">
                            <template v-slot:item.value="{ item }">
                                <v-chip color="primary" v-for="value in item.value" :key="value" dark>{{ value }}</v-chip>
                            </template>
                            <template v-slot:top>
                                <v-toolbar flat color="white">
                                    <v-toolbar-title>Pricing List</v-toolbar-title>
                                    <v-divider class="mx-4" inset vertical></v-divider>
                                    <v-spacer></v-spacer>
                                    <v-btn color="secondary" @click="assignStock(editedItem)" class="mt-2 mb-2">
                                        <v-icon>refresh</v-icon>
                                    </v-btn>
                                </v-toolbar>
                            </template>
                            <template v-slot:item.price="props">
                                <v-text-field v-model="props.item.price" label="From Price" prefix="$"></v-text-field>
                            </template>
                        </v-data-table>
                        <v-select item-text="name" item-value="value" v-model="editedItem.properties.duration" :items="durations" label="Duration"></v-select>
                    </v-col>
                </v-row>
            </v-container>
        </template>
        <template v-slot:item.thumbnail="{item}">
            <v-avatar size="36px" v-if="!!item.thumbnail">
                <img :src="item.thumbnail" alt="Thumbnail">
            </v-avatar>
            <v-avatar size="36px" v-if="!item.thumbnail" :color="!!item.properties.color ? item.properties.color : 'grey'">
            </v-avatar>
        </template>
        <template v-slot:item.stockable="{item}">
            <v-icon color="success" v-if="item.stockable === 1">check</v-icon>
            <v-icon color="danger" v-if="item.stockable === 0">time</v-icon>
        </template>
        <template v-slot:item.status="{item}">
            <v-chip v-if="item.status === 'active'" class="ma-2" color="green" text-color="white">
                ACTIVE
            </v-chip>
            <v-chip v-if="item.status === 'inactive'" class="ma-2" color="red" text-color="white">
                INACTIVE
            </v-chip>
        </template>
    </crud>
</template>
<script>
import { mapGetters } from 'vuex'
import Crud from '../shared/Crud'
import AvatarCropper from 'vue-avatar-cropper'
import Vue from 'Vue'
import { durations } from '~~/config'

export default {
    components: {
        Crud,
        AvatarCropper,
    },
    data() {
        return {
            colorDialog: false,
            variantDialog: false,
            variantSearch: '',
            editedVariantIndex: -1,
            editedVariantItem: {
                name: '',
                value: []
            },
            variantDefaultItem: {
                name: '',
                value: []
            },
            options: {
                sortBy: [],
                sortDesc: [],
                page: 1,
                itemsPerPage: 10,
            },
            swatches: [
                ['#FF0000', '#AA0000', '#550000'],
                ['#FFFF00', '#AAAA00', '#555500'],
                ['#00FF00', '#00AA00', '#005500'],
                ['#00FFFF', '#00AAAA', '#005555'],
                ['#0000FF', '#0000AA', '#000055'],
            ],
            type: '',
            loading: true,
            commissions: [],
            taxes: [],
            categories: [],
            defaultItem: {
                name: '',
                thumbnail: '',
                sku: '',
                cat_id: 0,
                tax_id: 0,
                stockable: 0,
                status: 'active',
                discount: 0.00,
                note: '',
                allow_assistant: 0,
                commission_id: 0,
                variants: [],
                properties: {
                    price: 0.00,
                    cost: 0.00,
                    color: '',
                    opening: 0.00,
                    stocks: [],
                },
                formData: null,
            },
            headers: [
                { text: 'Preview', value: 'thumbnail', sortable: false, custom: true },
                { text: 'Name', value: 'name' },
                { text: 'Code #', value: 'sku' },
                { text: 'Tax', value: 'tax.code', sortable: false, custom: true },
                { text: 'Status', value: 'status', sortable: false, custom: true },
                { text: 'Actions', value: 'action', sortable: false },
            ],
            variantHeaders: [
                { text: 'Variant name', value: 'name' },
                { text: 'Variant value', value: 'value' },
                { text: 'Actions', value: 'action', sortable: false },
            ],
            stockHeaders: [
                { text: 'Name', value: 'name' },
                { text: 'Price from', value: 'price' },
            ],
            exportFields: {
                'name': 'name',
                'mobile': 'properties.mobile',
                'email': 'properties.email',
            },
            durations: durations,
        }
    },
    computed: mapGetters({
        items: 'product/items',
        count: 'product/count',
    }),
    async mounted() {

    },
    methods: {
        async editDialogHandler(val) {
            this.loading = true
            if (!!val) {
                this.categories = await this.$store.dispatch('setting/fetch', { type: 'category', search: '', limit: 0, page: 1, sort: true, desc: false, noCommit: true })
                this.taxes = await this.$store.dispatch('setting/fetch', { type: 'tax', search: '', limit: 0, page: 1, sort: true, desc: false, noCommit: true })
                this.commissions = await this.$store.dispatch('setting/fetch', { type: 'commission', search: '', limit: 0, page: 1, sort: true, desc: false, noCommit: true })
            }

            this.loading = false
        },
        async retrieve(search, options, noCommit = false) {

            this.loading = true
            const { sortBy, sortDesc, page, itemsPerPage } = options


            const results = await this.$store.dispatch('product/fetch', { type: 'service-variant', search, limit: itemsPerPage, page, sort: sortBy, desc: sortDesc, noCommit })

            this.loading = false

            if (noCommit) return results
        },
        editVariantItem(editedItem, item) {

            this.editedVariantItem = JSON.parse(JSON.stringify(item))
            this.editedVariantIndex = editedItem.variants.indexOf(item)
            this.variantDialog = true
        },

        async deleteVariantItem(editedItem, item) {
            const index = editedItem.variants.findIndex(v => v.name === item.name)
            editedItem.variants.splice(index, 1)

            if (editedItem.stockable === 1) {
                await this.assignStock(editedItem)
            }

        },
        async saveVariant(editedItem) {

            if (this.editedVariantIndex > -1) {
                Vue.set(editedItem.variants, this.editedVariantIndex, this.editedVariantItem)

                if (editedItem.stockable === 1) {
                    await this.assignStock(editedItem)
                }
            } else {
                const index = editedItem.variants.findIndex(v => v.name === this.editedVariantItem.name)

                if (index > -1) {
                    this.$toast.error('Variant name already exist.')
                } else {
                    try {
                        editedItem.variants.push(this.editedVariantItem)

                        if (editedItem.stockable === 1) {
                            await this.assignStock(editedItem)
                        }
                    } catch (e) {
                        throw e
                    }
                }
            }
            await this.closeVariant()
        },
        async closeVariant() {
            await setTimeout(() => {
                this.editedVariantItem = JSON.parse(JSON.stringify(this.variantDefaultItem))
                this.editedVariantIndex = -1
                this.variantDialog = false
            }, 300)
        },

        async save(item) {

            this.loading = true

            if (!item.id) {
                item.type = 'service-variant'
                await this.$store.dispatch('product/add', item)
            } else {
                await this.$store.dispatch('product/update', item)
            }

            this.loading = false
        },
        async remove(item) {

            this.loading = true

            await this.$store.dispatch('product/trash', item)

            this.loading = false
        },
        clearThumbnail(editedItem) {
            editedItem.thumbnail = ''
        },
        async handleSubmitted(editedItem) {
            this.loading = true
            const avatar = this.$refs.cropper

            const croppedCanvas = await avatar.cropper.getCroppedCanvas()
            editedItem.thumbnail = await croppedCanvas.toDataURL('image/jpeg')
            await croppedCanvas.toBlob((blob) => {
                editedItem.formData = new FormData()
                editedItem.formData.append('thumbnail', blob)

            })

            this.loading = false
        },
        async assignStock(editedItem) {

            this.loading = true



            if (editedItem.variants.length > 0) {
                let stocks = this.combine(editedItem.variants)
                for (let stock of editedItem.properties.stocks) {
                    const index = stocks.findIndex(v => v.name === stock.name)
                    let newStock = stocks[index]
                    if (!!newStock) {
                        newStock.cost = stock.cost
                        newStock.price = stock.price
                        newStock.qty = stock.qty
                    }
                }
                editedItem.properties.stocks = stocks
            } else {
                editedItem.properties.stocks = []
            }
            this.loading = false
        },

        combine(variants) {
            let combos = [
                []
            ]
            for (const variant of variants) {
                let tmp = []
                if (!variant.value) return
                for (const combo of combos) {
                    for (const value of variant.value) {
                        if (combo == '') tmp.push({ name: value, cost: 0, price: 0, qty: 0 });
                        else tmp.push({ name: combo.name + '-' + value, cost: 0, price: 0, qty: 0 })
                    }
                }
                combos = tmp
            }
            return combos
        },


    },
}

</script>
