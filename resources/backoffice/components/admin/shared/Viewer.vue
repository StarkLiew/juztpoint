<template>
    <div>
        <v-data-table :headers="headers" :items="items.data" :sort-by="sortBy" :search="search" class="elevation-1" :options.sync="mutateOptions" :server-items-length="serverItemsLength" :loading="loading" loading-text="Loading..." :footer-props="{
    'items-per-page-options': [50, 100]}" :show-group-by="showGroupBy" :group-by="groupBy">
            <template v-slot:footer="{pagination}">
                <v-toolbar dense>
                    <v-toolbar-title>Total</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-title>{{summary.sum | currency}}</v-toolbar-title>
                </v-toolbar>
            </template>
            <template v-slot:group.header="{ group, groupedBy, items, headers, toggle }">
                <td :colspan="headers.length" class="text-start">
                    <v-btn fab icon @click="toggle">
                        <v-icon>remove</v-icon>
                    </v-btn>
                    {{ groupBy }} - {{ groupText(items) }}
                </td>
            </template>
            <template v-slot:[header]="{ item }" v-for="header in headers.filter(h => h.custom === true).map(h => 'item.' +  h.value)">
                <slot :name="header" :item="item"></slot>
            </template>
            <template v-slot:[header]="{ item, header }" v-for="header in headers.filter(h => h.currency === true).map(h => 'item.' +  h.value)">
                <span>{{ item[header.value] | currency }}</span>
            </template>
            <template v-slot:top>
                <v-toolbar flat dark color="primary">
                    <v-btn color="primary" dark @click="$emit('closed')" :disabled="loading">
                        <v-icon>arrow_back</v-icon>
                    </v-btn>
                    <v-divider class="mx-4" inset vertical></v-divider>
                    <v-toolbar-title>{{ title }}</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-menu>
                        <template v-slot:activator="{ on }">
                            <v-btn color="primary" dark v-on="on" class="mb-2" :disabled="loading">
                                <v-icon>arrow_downward</v-icon>
                            </v-btn>
                        </template>
                        <v-list>
                            <v-list-item>
                                <v-list-item-title>
                                    <download-excel class="btn" :fetch="allItems" :fields="exportFields" type="csv" name="data.csv">
                                        CSV
                                    </download-excel>
                                </v-list-item-title>
                            </v-list-item>
                            <v-list-item>
                                <v-list-item-title>
                                    <download-excel class="btn" :fetch="allItems" :fields="exportFields" type="xls" name="data.xls">
                                        Excel
                                    </download-excel>
                                </v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </v-toolbar>
                <v-toolbar flat dark color="primary">
                    <v-menu ref="menu" v-model="menu" :close-on-content-click="false" :return-value.sync="filter.dates" transition="scale-transition" offset-y min-width="290px">
                        <template v-slot:activator="{ on }">
                            <v-text-field class="mt-5 ml-2 mr-2" v-model="dateRangeText" label="Date range" prepend-icon="event" readonly v-on="on"></v-text-field>
                        </template>
                        <v-date-picker v-model="filter.dates" no-title range scrollable></v-date-picker>
                        <v-spacer></v-spacer>
                        <v-btn text color="primary" @click="menu = false">Cancel</v-btn>
                        <v-btn text color="primary" @click="filterData">OK</v-btn>
                        </v-date-picker>
                    </v-menu>
                    <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition" scrollable>
                        <template v-slot:activator="{ on }">
                            <v-btn color="primary" dark class="mb-2" v-on="on" :disabled="loading">
                                <v-icon>filter_list</v-icon>
                            </v-btn>
                        </template>
                        <v-form ref="form" lazy-validation>
                            <v-card tile>
                                <v-toolbar flat dark color="primary" max-height="68">
                                    <v-btn icon dark @click="close">
                                        <v-icon>mdi-close</v-icon>
                                    </v-btn>
                                    <v-toolbar-title>More filter</v-toolbar-title>
                                    <div class="flex-grow-1"></div>
                                    <v-toolbar-items>
                                        <v-btn dark text @click="filterDone">
                                            Done
                                        </v-btn>
                                    </v-toolbar-items>
                                    </v-menu>
                                </v-toolbar>
                                <v-card-title>
                                    <span class="headline">Filter by</span>
                                </v-card-title>
                                <v-card-text>
                                    <v-select v-model="filter.store" :items="stores" item-text="name" item-value="id" :loading="filling" class="mt-5 ml-2 mr-2" clearable clear-icon="clear" label="Store"></v-select>
                                    <v-select v-model="filter.terminal" :items="terminals" item-text="name" item-value="id" :loading="filling" class="mt-5 ml-2 mr-2" clearable clear-icon="clear" label="Terminal"></v-select>
                                    <v-select v-model="filter.user" :items="users" item-text="name" item-value="id" :loading="filling" class="mt-5 ml-2 mr-2" clearable clear-icon="clear" label="Employee"></v-select>
                                </v-card-text>
                            </v-card>
                        </v-form>
                    </v-dialog>
                    <v-divider class="mx-4" inset vertical></v-divider>
                    <v-btn color="primary" class="mb-2" @click="reset" :disabled="loading">
                        <v-icon>refresh</v-icon>
                    </v-btn>
                    <div class="flex-grow-1"></div>
                    <v-select item-text="name" item-value="value" v-if="!!groups" :loading="loading" class="mt-5 ml-2 mr-2" v-model="groupBy" clearable clear-icon="clear" :items="groups" label="Group By"></v-select>
                </v-toolbar>
                <v-toolbar flat dark color="primary">
                    <v-chip class="ma-2" v-for="(value, key) in filter" :key="key" v-if="!!value">
                        <span>{{ key }}:&nbsp;</span>
                        {{ getFilterByName(key, value) }}
                    </v-chip>
                </v-toolbar>
            </template>
            <template v-slot:item.action="{ item, header }">
                <v-icon small class="mr-2" @click="editItem(item)">
                    edit
                </v-icon>
                <v-icon small @click="deleteItem(item)" v-if="!header.hideTrash || item[header.hideTrash]">
                    delete
                </v-icon>
            </template>
            <template v-slot:no-data>
                <v-container class="mt-5 mb-5">
                    <h1 class="title">Empty data</h1>
                </v-container>
            </template>
        </v-data-table>
    </div>
</template>
<script>
import JsonExcel from 'vue-json-excel'

export default {
    data() {
        return {
            groupBy: null,
            menu: false,
            filter: {
                dates: [],
                user: null,
                store: null,
                terminal: null,
            },
            showGroupBy: false,
            dialog: false,
            search: '',
            mutateOptions: this.options,
            users: [],
            terminals: [],
            stores: [],
            filling: false,
        }
    },
    components: {
        'downloadExcel': JsonExcel,
    },
    created() {
        this.initialize()
    },
    props: ['title', 'headers', 'summary', 'items', 'sortBy', 'defaultItem', 'options', 'loading', 'serverItemsLength', 'refresh', 'saveMethod', 'removeMethod', 'exportFields', 'groups'],
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
        },
        dateRangeText() {
            return this.filter.dates.join(' ~ ')
        },
    },

    watch: {
        async dialog(val) {

            val || this.close()
            if (val) {
                this.filling = true
                this.terminals = await this.$store.dispatch('setting/fetch', { type: 'terminal', search: '', limit: 0, page: 1, sort: 'name', desc: '', noCommit: true })
                this.stores = await this.$store.dispatch('setting/fetch', { type: 'store', search: '', limit: 0, page: 1, sort: 'name', desc: '', noCommit: true })
                this.users = await this.$store.dispatch('user/fetchUsers', { search: '', limit: 0, page: 1, sort: '', desc: '', noCommit: true })
                this.filling = false

            }


        },
        mutateOptions: {
            async handler() {
                await this.refresh({ filters: { dates: this.filter.dates } }, this.mutateOptions)
            },
            deep: true,
        },
    },
    methods: {
        initialize() {

        },
        async filterData() {
            if (this.menu) {
                this.$refs.menu.save(this.filter.dates)
                this.menu = false
            }

            await this.refresh(this.filter, this.mutateOptions)
        },
        async reset() {
            this.filter.dates = []
            this.filter.user = null,
                this.filter.store = null,
                this.filter.terminal = null,
                await this.refresh(this.filter.dates, this.mutateOptions)
        },

        async allItems() {
            const options = Object.assign(...this.mutateOptions, { itemsPerPage: 0, page: 1 })
            const results = await this.refresh(this.filter.dates, options, true)

            return results.data
        },
        filterDone() {
            this.dialog = false
            this.filterData()

        },
        close() {
            this.dialog = false
        },
        remove() {

        },
        getFilterByName(key, value) {
            let found = false
            if (key === 'store') found = this.stores.find(v => v.id === value)
            if (key === 'terminal') found = this.terminals.find(v => v.id === value)
            if (key === 'user') found = this.users.find(v => v.id === value)
            if (key === 'dates') found = this.dateRangeText
            if (!found) return ''
            return found.name

        },
        groupText(items) {
            if (items.length > 0) {

                const item = this.groups.find(v => v.value === this.groupBy)

                if (!item) return ''
                else return items[0][this.groupBy][item.text]
            }
            return ''
        }

    }
}

</script>
