<template>
    <v-data-table :headers="headers" :items="items" :sort-by="sortBy" :search="search" class="elevation-1"
        :options.sync="mutateOptions" :server-items-length="serverItemsLength" :loading="loading" loading-text="Loading..."
    >
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
                </v-dialog>
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
            mutateOptions: this.options,
        }
    },
    created() {

    },
    props: ['title', 'headers', 'items', 'sortBy', 'defaultItem','options', 'loading', 'serverItemsLength' ],
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
        },
    },

    watch: {
        dialog(val) {
            val || this.close()
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
        deleteItem(item) {
            const index = this.items.indexOf(item)
            confirm('Are you sure you want to delete this item?') && this.items.splice(index, 1)
        },

        close() {
            this.dialog = false
            setTimeout(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            }, 300)
        },

        save() {
            if (this.editedIndex > -1) {
                Object.assign(this.desserts[this.editedIndex], this.editedItem)
            } else {
                this.desserts.push(this.editedItem)
            }
            this.close()
        },
    }
}

</script>
