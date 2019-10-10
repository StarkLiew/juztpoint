<template>
    <v-data-table :headers="headers" :items="items" :sort-by="sortBy" :search="search" class="elevation-1" :options.sync="mutateOptions" :server-items-length="serverItemsLength" :loading="loading" loading-text="Loading...">
        <template v-slot:top>
            <v-toolbar flat dark color="primary">
                <v-toolbar-title>{{ title }}</v-toolbar-title>
                <v-divider class="mx-4" inset vertical></v-divider>
                <v-layout row wrap>
                    <v-text-field type="search" v-model="search" append-icon="search" outlined name="search" label="Search..."></v-text-field>
                </v-layout>
                <div class="flex-grow-1"></div>
                <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition" scrollable>
                    <template v-slot:activator="{ on }">
                        <v-btn color="primary" dark class="mb-2" v-on="on">
                            <v-icon>add</v-icon>
                        </v-btn>
                    </template>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <v-card tile>
                            <v-toolbar flat dark color="primary" max-height="68">
                                <v-btn icon dark @click="close">
                                    <v-icon>mdi-close</v-icon>
                                </v-btn>
                                <v-toolbar-title>{{ title }}</v-toolbar-title>
                                <div class="flex-grow-1"></div>
                                <v-toolbar-items>
                                    <v-btn dark text @click="save">
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
                        <v-btn color="primary" dark v-on="on" class="mb-2">
                            <v-icon>arrow_downward</v-icon>
                        </v-btn>
                    </template>
                    <v-list>
                        <v-list-item @click="">
                            <v-list-item-title>PDF</v-list-item-title>
                        </v-list-item>
                        <v-list-item @click="">
                            <v-list-item-title>Excel</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </v-toolbar>
        </template>
        <template v-slot:item.action="{ item }">
            <v-icon small class="mr-2" @click="editItem(item)">
                edit
            </v-icon>
            <v-icon small @click="deleteItem(item)">
                delete
            </v-icon>
        </template>
        <template v-slot:no-data>
            <v-btn fab small @click="">
                <v-icon>refresh</v-icon>
            </v-btn>
        </template>
    </v-data-table>
</template>
<script>
export default {
    data() {
        return {
            dialog: false,
            editedItem: {},
            editedIndex: -1,
            search: '',
            valid: true,
            mutateOptions: this.options,
        }
    },
    created() {
        this.initialize()
    },
    props: ['title', 'headers', 'items', 'sortBy', 'defaultItem', 'options', 'loading', 'serverItemsLength', 'refresh', 'saveMethod', 'removeMethod'],
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
        },
    },

    watch: {
        dialog(val) {
            val || this.close()
        },
        async search(val) {
            await this.refresh(val, this.mutateOptions)
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

            this.editedItem = this.defaultItem
        },
        editItem(item) {
            this.editedIndex = this.items.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialog = true
        },
        async deleteItem(item) {
            const index = this.items.indexOf(item)
            confirm('Are you sure you want to delete this item?') && await this.removeMethod(item)
        },
        close() {
            this.dialog = false
            setTimeout(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
                this.$refs.form.reset()

            }, 300)
        },
        async save() {

            if (this.$refs.form.validate()) {
                await this.saveMethod(this.editedItem)

                this.close()
            }



        },

    }
}

</script>
