<template>
    <crud title="Terminals" :headers="headers" :items='items' sort-by="name" :refresh="retrieve" :default-item="defaultItem" :options.sync="options" :save-method="save" :remove-method="remove" :server-items-length="count" :loading="loading" loading-text="Loading..." :export-fields="exportFields">
        <template v-slot:dialog="{ valid, editedItem }">
            <v-container>
                <v-row>
                    <v-col cols="12" sm="12" md="6" lg="6">
                        <v-text-field v-model="editedItem.name" :rules="[v => !!v || 'Name is required',]" required label="Name"></v-text-field>
                    </v-col>
                    <v-col class="text-center" cols="6" sm="12" md="6" lg="6" v-if="editedItem.properties.device_id">
                        <vue-qr :text="editedItem.properties.device_id" :size="200"></vue-qr>
                        <p>{{ editedItem.properties.device_id }}</p>
                    </v-col>
                    <v-col class="text-center" cols="6" sm="12" md="6" lg="6" v-if="editedItem.properties.fingerprint">
                        <p>{{ editedItem.properties.fingerprint }}</p>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12" sm="12" md="6" lg="6">
                        <v-select v-model="editedItem.properties.store_id" :items="stores" item-text="name" item-value="id" label="Store"></v-select>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12" sm="12" md="6" lg="6">
                        <v-text-field v-model="editedItem.description" label="Description"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="12" md="6" lg="6">
                        <v-switch :true-value="1" :false-value="0" v-model="editedItem.properties.active" inset label="Active"></v-switch>
                    </v-col>
                </v-row>
            </v-container>
        </template>
        <template v-slot:item.reset="{item}">
            <v-btn @click="reset(item)" text small dark color="red darken-1">Reset</v-btn>
        </template>
    </crud>
</template>
<script>
import { mapGetters } from 'vuex'
import Crud from '../shared/Crud'
import VueQr from 'vue-qr'

export default {
    components: {
        Crud,
        VueQr,
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
            stores: [],
            defaultItem: {
                name: '',
                description: '',
                properties: {
                    store_id: 0,
                    active: 0,
                }
            },
            headers: [
                { text: 'Name', value: 'name' },
                { text: 'Identifier', value: 'properties.device_id' },
                { text: 'Reset', value: 'reset', sortable: false, custom: true },
                { text: 'Actions', value: 'action', sortable: false },
            ],
            exportFields: {
                'name': 'name',
                'description': 'description',
            },
        }
    },
    computed: mapGetters({
        items: 'setting/items',
        count: 'setting/count',
    }),
    async mounted() {
        this.getStores()
    },
    methods: {
        async getStores() {
            this.stores = await this.$store.dispatch('setting/fetch', { type: 'store', search: '', limit: 0, page: 1, sort: true, desc: false, noCommit: true })

        },
        async retrieve(search, options, noCommit = false) {

            this.loading = true
            const { sortBy, sortDesc, page, itemsPerPage } = options


            const results = await this.$store.dispatch('setting/fetch', { type: 'terminal', search, limit: itemsPerPage, page, sort: sortBy, desc: sortDesc, noCommit })


            this.loading = false

            if (noCommit) return results
        },
        async save(item) {

            this.loading = true
            if (!item.id) {
                item.type = 'terminal'
                await this.$store.dispatch('setting/add', item)
            } else {

                await this.$store.dispatch('setting/update', item)
            }


            this.loading = false
        },
        async remove(item) {

            this.loading = true

            await this.$store.dispatch('setting/trash', item)

            this.loading = false



        },
        async reset(item) {
                delete item.properties['fingerprint']
                await this.save(item)
        },

    },
}

</script>
