<template>
    <v-data-table :headers="headers" :items="items" :sort-by="sortBy" :search="search" class="elevation-1" :options.sync="mutateOptions" :server-items-length="serverItemsLength" :loading="loading" loading-text="Loading..." :footer-props="{
    'items-per-page-options': [50, 100]}" :show-group-by="showGroupBy" :group-by="groupBy">
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
        <template v-slot:top>
            <v-toolbar flat dark color="primary">
                <v-toolbar-title>{{ title }}</v-toolbar-title>
            </v-toolbar>
            <v-toolbar flat dark color="primary">
                <v-btn color="primary" class="mb-2" @click="filter" :disabled="loading">
                    <v-icon>search</v-icon>
                </v-btn>
                <v-text-field type="search" class="mt-5 ml-2 mr-2" v-model="search" name="search" label="Search ..." :disabled="loading" :clearable="true" @click:clear="reset" :clear-icon="'remove'"></v-text-field>
                <v-btn color="primary" class="mb-2" @click="reset" :disabled="loading">
                    <v-icon>refresh</v-icon>
                </v-btn>
                <div class="flex-grow-1"></div>
                <v-select item-text="name" item-value="value" v-if="!!groups" :loading="loading" class="mt-5 ml-2 mr-2" v-model="groupBy" clearable clear-icon="clear" :items="groups" label="Group By"></v-select>
                <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition" scrollable>
                    <template v-slot:activator="{ on }">
                        <v-btn color="primary" dark class="mb-2" v-on="on" :disabled="loading">
                            <v-icon>add</v-icon>
                        </v-btn>
                    </template>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <v-card tile>
                            <v-toolbar flat dark color="primary" max-height="68">
                                <v-btn icon dark @click="close" :disabled="saving">
                                    <v-icon>mdi-close</v-icon>
                                </v-btn>
                                <v-toolbar-title>{{ title }}</v-toolbar-title>
                                <div class="flex-grow-1"></div>
                                <v-toolbar-items>
                                    <v-btn dark text @click="save" :loading="saving" :disabled="saving || !valid">
                                        Save
                                    </v-btn>
                                </v-toolbar-items>
                                </v-menu>
                            </v-toolbar>
                            <v-card-title>
                                <span class="headline">{{ formTitle }}</span>
                            </v-card-title>
                            <v-card-text>
                                <slot name="dialog" :editedItem="editedItem"></slot>
                            </v-card-text>
                        </v-card>
                    </v-form>
                </v-dialog>
                <v-menu>
                    <template v-slot:activator="{ on }">
                        <v-btn color="primary" dark v-on="on" class="mb-2" :disabled="loading">
                            <v-icon>arrow_downward</v-icon>
                        </v-btn>
                    </template>
                    <v-list>
                        <v-list-item @click="">
                            <v-list-item-title>
                                <download-excel class="btn" :fetch="allItems" :fields="exportFields" type="csv" name="data.csv">
                                    CSV
                                </download-excel>
                            </v-list-item-title>
                        </v-list-item>
                        <v-list-item @click="">
                            <v-list-item-title>
                                <download-excel class="btn" :fetch="allItems" :fields="exportFields" type="xls" name="data.xls">
                                    Excel
                                </download-excel>
                            </v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
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
</template>
<script>
import JsonExcel from 'vue-json-excel'

export default {
    data() {
        return {
            groupBy: null,
            showGroupBy: false,
            dialog: false,
            editedItem: {},
            editedIndex: -1,
            search: '',
            valid: true,
            saving: false,
            mutateOptions: this.options,
        }
    },
    components: {
        'downloadExcel': JsonExcel,
    },
    created() {
        this.initialize()
    },
    props: ['title', 'headers', 'items', 'sortBy', 'defaultItem', 'options', 'loading', 'serverItemsLength', 'refresh', 'saveMethod', 'removeMethod', 'exportFields', 'groups'],
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
        },
    },

    watch: {
        dialog(val) {
            this.$emit('edit-dialog-changed', val)
            val || this.close()
        },
        mutateOptions: {
            async handler() {
                await this.refresh(this.search, this.mutateOptions)
            },
            deep: true,
        },
    },
    methods: {
        initialize() {
            this.editedItem = JSON.parse(JSON.stringify(this.defaultItem))
        },
        editItem(item) {
            this.editedIndex = this.items.indexOf(item)
            this.editedItem = JSON.parse(JSON.stringify(item))
            this.dialog = true
        },
        async deleteItem(item) {
            const index = this.items.indexOf(item)
            confirm('Are you sure you want to delete this item?') && await this.removeMethod(item)
        },
        async filter() {
            await this.refresh(this.search, this.mutateOptions)
        },
        async reset() {
            this.search = ''
            await this.refresh(this.search, this.mutateOptions)
        },
        close() {
            this.dialog = false
            setTimeout(() => {
                this.editedItem = JSON.parse(JSON.stringify(this.defaultItem))
                this.editedIndex = -1
                this.$refs.form.reset()
            }, 300)
        },
        async save() {
            this.saving = true
            if (this.$refs.form.validate()) {
                await this.saveMethod(this.editedItem)
                this.close()
            }
            this.saving = false
        },
        async allItems() {
            const options = Object.assign(this.mutateOptions, { itemsPerPage: 0, page: 1 })
            const results = await this.refresh(this.search, options, true)
            return results
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
