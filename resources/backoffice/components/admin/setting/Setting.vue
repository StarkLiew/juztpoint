<template>
    <div class="fill-height">
        <v-container>
            <v-row v-if="!selected">
                <v-col col="6" lg="6" md="6" sm="12" v-for="(setter, index) in settings" :key="index">
                    <v-card min-width="300" class="mx-auto mx-2" color="grey lighten-4" max-width="600">
                        <v-card-title>
                            {{ setter.title }}
                        </v-card-title>
                        <v-list>
                            <v-list-item-group color="primary">
                                <v-list-item v-for="(item, i) in setter.items" :key="i" @click="select(item)" :to="item.to">
                                    <v-list-item-content>
                                        <v-list-item-title>{{ item.title }}</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list-item-group>
                        </v-list>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>
<script>
import { mapGetters } from 'vuex'


export default {
    components: {

    },
    data() {
        return {
            settings: [],
            selected: null,
            options: {
                sortBy: [],
                sortDesc: [],
                page: 1,
                itemsPerPage: 10,
            },
            title: '',
            exportFields: {},
            headers: [],
            loading: false,

        }
    },
    computed: mapGetters({

    }),
    async mounted() {
        this.initialise()
    },
    methods: {

        select(item) {
            this.selected = item
            this.headers = item.headers
            this.exportFields = item.exports
        },
        initialise() {
            this.settings = [{
                    title: 'Information',
                    describe: '',
                    items: [
                        { title: 'My Company', to: 'company' },
                        { title: 'Stores', to: 'stores' },
                    ]
                },
                {
                    title: 'Account',
                    describe: '',
                    items: [
                        { title: 'Taxation', to: 'taxes' },
                        { title: 'Service Charge', to: 'service' },
                    ]
                },
                {
                    title: 'Operation',
                    describe: '',
                    items: [
                        { title: 'Terminal', to: 'terminals' },
                    ]
                },
                {
                    title: 'Product and Service',
                    describe: '',
                    items: [
                        { title: 'Categories', to: 'categories' },
                        { title: 'Commissions', to: 'commissions' },
                    ]
                },

            ]
        }

    }
}

</script>
