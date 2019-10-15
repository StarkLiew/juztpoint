<template>
    <crud title="Products" :headers="headers" :items.sync='items' sort-by="name" :refresh="retrieve" :default-item="defaultItem" :options.sync="options" :save-method="save" :remove-method="remove" :server-items-length="count" :loading="loading" loading-text="Loading..." :export-fields="exportFields">
        <template v-slot:dialog="{ valid, editedItem }">
            <v-container>
                <v-row>
                    <v-col cols="12" sm="12" md="6" lg="6">
                        <v-row class="text-center">
                            <v-col cols="6" sm="6" md="6" lg="6">
                                <v-card min-height="164">
                                    <v-avatar class="profile" color="grey" size="164" tile v-if="editedItem.properties.thumbnail">
                                        <v-img :src="editedItem.properties.thumbnail"></v-img>
                                    </v-avatar>
                                    <v-btn dark fab large v-if="!editedItem.properties.thumbnail" class="pick-avatar" dark>
                                        <v-icon dark>add_a_photo</v-icon>
                                    </v-btn>
                                    <v-btn absolute dark fab small top right v-if="editedItem.properties.thumbnail" class="pick-avatar" dark>
                                        <v-icon dark>add_a_photo</v-icon>
                                    </v-btn>
                                    <v-btn absolute dark fab top left small v-if="editedItem.properties.thumbnail" color="red">
                                        <v-icon dark>cancel</v-icon>
                                    </v-btn>
                                    <avatar-cropper ref="cropper" :labels="{ submit: 'Done', cancel: 'Cancel'}" @submit="handleSubmitted" trigger=".pick-avatar"></avatar-cropper>
                                </v-card>
                            </v-col>
                            <v-col cols="6" sm="6" md="6" lg="6">
                                <v-color-picker v-model="editedItem.properties.color" hide-mode-switch hide-inputs hide-canvas :swatches="swatches" show-swatches></v-color-picker>
                            </v-col>
                        </v-row>
                        <v-text-field v-model="editedItem.name" :rules="[v => !!v || 'Name is required',]" required label="Name"></v-text-field>
                        <v-text-field v-model="editedItem.sku" :rules="[v => !!v || 'SKU is required',]" required label="Stock Keeping Unit (SKU)"></v-text-field>
                        <v-select v-model="editedItem.cat_id" :items="[]" label="Category"></v-select>
                        <v-select v-model="editedItem.tax_id" :items="[]" label="Tax"></v-select>
                    </v-col>
                    <v-col cols="12" sm="12" md="6" lg="6">
                        <v-text-field prefix="$" v-model="editedItem.properties.price" label="Selling Price"></v-text-field>
                        <v-switch :true-value="1" :false-value="0" v-model="editedItem.stockable" inset label="Stockable"></v-switch>
                        <v-text-field v-if="editedItem.stockable" prefix="$" v-model="editedItem.properties.cost" label="Cost"></v-text-field>
                        <v-text-field v-if="editedItem.stockable" v-model="editedItem.properties.cost" label="Opening Quantity"></v-text-field>
                        <v-switch :true-value="'active'" :false-value="'inactive'" v-model="editedItem.status" inset :label="`Active`"></v-switch>
                    </v-col>
                </v-row>
            </v-container>
        </template>
        <template v-slot:item.stockable="{item}">
            <v-icon color="success" v-if="item.stockable === 1">check</v-icon>
            <v-icon color="danger" v-if="item.stockable === 0">time</v-icon>
        </template>
        <template v-slot:item.status="{item}">
            <span class="caption success" v-if="item.status === 'active'">ACTIVE</span>
            <span class="caption danger" v-if="item.status === 'inactive'">INACTIVE</span>
        </template>
    </crud>
</template>
<script>
import { mapGetters } from 'vuex'
import Crud from '../shared/Crud'
import AvatarCropper from 'vue-avatar-cropper'

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
            swatches: [
                ['#FF0000', '#AA0000', '#550000'],
                ['#FFFF00', '#AAAA00', '#555500'],
                ['#00FF00', '#00AA00', '#005500'],
                ['#00FFFF', '#00AAAA', '#005555'],
                ['#0000FF', '#0000AA', '#000055'],
            ],
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
        async handleSubmitted() {
            const avatar = this.$refs.cropper
            console.log(avatar.cropper.getCroppedCanvas().toDataURL('image/jpeg'))
        
        }
    },
}

</script>
