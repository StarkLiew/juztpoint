<template>
    <crud title="Terminals" :headers="headers" :items='items' sort-by="name" :refresh="retrieve" :default-item="defaultItem" :options.sync="options" :save-method="save" :remove-method="remove" :server-items-length="count" :loading="loading" loading-text="Loading..." :export-fields="exportFields">
        <template v-slot:dialog="{ valid, editedItem }">
            <v-container>
                <v-row>
                    <v-col cols="12" sm="12" md="6" lg="6">
                        <v-text-field v-model="editedItem.name" :rules="[v => !!v || 'Name is required',]" required label="Name"></v-text-field>
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
                description: '',
            },
            headers: [
                { text: 'Name', value: 'name' },
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

    },
    methods: {
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



        }
    },
}

</script>
