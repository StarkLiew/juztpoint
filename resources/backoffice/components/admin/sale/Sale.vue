<template>
    <crud title="Sale Receipts" :headers="headers" :items='items' sort-by="name" :refresh="retrieve" :default-item="defaultItem" :options.sync="options" :save-method="save" :remove-method="remove" :server-items-length="count" :loading="loading" loading-text="Loading..." :export-fields="exportFields">
        <template v-slot:dialog="{ valid, editedItem }">
            <v-container>
                <v-row>
                    <v-col cols="12" sm="12" md="6" lg="6">
                        <v-text-field v-model="editedItem.name" :rules="[v => !!v || 'Name is required',]" required label="Name"></v-text-field>
                        <v-text-field v-model="editedItem.properties.mobile" label="Mobile"></v-text-field>
                        <v-text-field v-model="editedItem.properties.email" label="Email" :rules="[v =>  /.+@.+\..+/.test(v ? v : 'my@example.com') || 'E-mail must be valid']"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="12" md="6" lg="6">
                        <v-switch :value="editedItem.status" :true-value="'active'" :false-value="'inactive'" v-model="editedItem.status" inset :label="`Status: ${editedItem.status}`"></v-switch>
                    </v-col>
                </v-row>
            </v-container>
        </template>
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
                name: '',
                status: 'active',
                properties: {
                    mobile: '',
                    email: '',
                }
            },
            headers: [
                { text: 'Preview', value: 'avatar', sortable: false, custom: true },
                { text: 'Name', value: 'name' },
                { text: 'Mobile #', value: 'properties.mobile', sortable: false },
                { text: 'Email #', value: 'properties.email', sortable: false },
                { text: 'Active', value: 'status', filterable: false, sortable: false, custom: true },
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
        items: 'account/items',
        count: 'account/count',
    }),
    async mounted() {

    },
    methods: {
        async retrieve(search, options, noCommit = false) {

            this.loading = true
            const { sortBy, sortDesc, page, itemsPerPage } = options


            const results = await this.$store.dispatch('account/fetch', { type: 'customer', search, limit: itemsPerPage, page, sort: sortBy, desc: sortDesc, noCommit })

            this.loading = false

            if (noCommit) return results
        },
        async save(item) {

            this.loading = true
            if (!item.id) {
                item.type = 'customer'
                await this.$store.dispatch('account/add', item)
            } else {

                await this.$store.dispatch('account/update', item)
            }


            this.loading = false
        },
        async remove(item) {

            this.loading = true

            await this.$store.dispatch('account/trash', item)

            this.loading = false



        }
    },
}

</script>
