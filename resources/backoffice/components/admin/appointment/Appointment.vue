<template>
  <v-sheet height="80vh">
    <v-calendar
      type="week"
      now="2019-01-08"
      value="2019-01-08"
      :events="[]"
    ></v-calendar>
  </v-sheet>
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
