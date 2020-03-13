<template>
    <crud title="Standard Products" :headers="headers" :items='items' sort-by="name" :refresh="retrieve" :default-item="defaultItem" :options.sync="options" :save-method="save" :remove-method="remove" :server-items-length="count" :loading="loading" loading-text="Loading..." :export-fields="exportFields" @edit-dialog-changed='editDialogHandler' :groups="[{name:'Category', value: 'category', text: 'name'}]">
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
                                <v-color-picker v-model="editedItem.properties.color" hide-mode-switch hide-inputs hide-canvas :swatches="swatches" show-swatches disabled></v-color-picker>
                            </v-col>
                        </v-row>
                        <v-text-field v-model="editedItem.name" :rules="[v => !!v || 'Name is required',]" required label="Name"></v-text-field>
                        <v-text-field v-model="editedItem.sku" :rules="[v => !!v || 'SKU is required',]" required label="Stock Keeping Unit (SKU)"></v-text-field>
                        <v-textarea clearable v-model="editedItem.note" clear-icon="mdi-close" label="Description"></v-textarea>
                    </v-col>
                    <v-col cols="12" sm="12" md="6" lg="6">
                        <v-select :loading="loading" :rules="[v => !!v || 'Category is required',]" required item-text="name" item-value="id" v-model="editedItem.cat_id" :items="categories" label="Category"></v-select>
                        <v-select :loading="loading" :rules="[v => !!v || 'Tax is required',]" required item-text="name" item-value="id" v-model="editedItem.tax_id" :items="taxes" label="Tax"></v-select>
                        <v-select :loading="loading" :rules="[v => !!v || 'Staff Commission is required',]" required item-text="name" item-value="id" v-model="editedItem.commission_id" :items="commissions" label="Staff Commission Rate"></v-select>
                        <v-text-field prefix="$" v-model="editedItem.properties.cost" label="Cost"></v-text-field>
                        <v-text-field prefix="$" v-model="editedItem.properties.price" label="Selling Price"></v-text-field>
                        <v-switch true-value="active" false-value="inactive" v-model="editedItem.status" inset :label="`Active`"></v-switch>
                        <v-switch :true-value="1" :false-value="0" v-model="editedItem.stockable" inset label="Stockable"></v-switch>
                        <v-text-field v-if="editedItem.stockable" v-model="editedItem.properties.opening" label="Opening Quantity"></v-text-field>
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
import { swatches } from '~~/config'

export default {
    components: {
        Crud,
        AvatarCropper,
    },
    data() {
        return {
            colorDialog: false,
            options: {
                sortBy: [],
                sortDesc: [],
                page: 1,
                itemsPerPage: 10,
            },
            swatches: swatches,
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
                properties: {
                    price: 0.00,
                    cost: 0.00,
                    color: '#3F51B5',
                    opening: 0.00,
                },
                formData: null,
            },
            headers: [
                { text: 'Preview', value: 'thumbnail', sortable: false, custom: true },
                { text: 'Name', value: 'name' },
                { text: 'SKU #', value: 'sku' },
                { text: 'Tax', value: 'tax.code', sortable: false, custom: true },
                { text: 'Stockable', value: 'stockable', sortable: false, custom: true },
                { text: 'Status', value: 'status', sortable: false, custom: true },
                { text: 'Actions', value: 'action', sortable: false },
            ],
            exportFields: {
                'name': 'name',
                'mobile': 'properties.mobile',
                'email': 'properties.email',
            },
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


            const results = await this.$store.dispatch('product/fetch', { type: 'product', search, limit: itemsPerPage, page, sort: sortBy, desc: sortDesc, noCommit })

            this.loading = false

            if (noCommit) return results
        },
        async save(item) {
            this.loading = true
     
                if (!item.id) {
                    item.type = 'product'
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
console.log(avatar.cropper)
            const croppedCanvas = await avatar.cropper.getCroppedCanvas({width: 120})
            editedItem.thumbnail = await croppedCanvas.toDataURL('image/jpeg')
            await croppedCanvas.toBlob((blob) => {
                editedItem.formData = new FormData()
                editedItem.formData.append('thumbnail', blob)

            })

            this.loading = false


        }
    },
}

</script>
