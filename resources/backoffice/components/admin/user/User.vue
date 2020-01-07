<template>
    <crud title="Employees" :headers="headers" :items='users' sort-by="name" :refresh="retrieve" :default-item="defaultItem" :options.sync="options" :save-method="save" :remove-method="remove" :server-items-length="userCount" :loading="loading" loading-text="Loading..." :export-fields="exportFields">
        <template v-slot:dialog="{ valid, editedItem }">
            <v-container>

                <v-row>
                    <v-col cols="12" sm="12" md="6" lg="6">
                        <v-text-field v-model="editedItem.name" :rules="[v => !!v || 'Name is required',]" required label="Name"></v-text-field>
                        <v-text-field v-model="editedItem.email" :disabled="!editedItem.id ? false : true" label="Email" :rules="[v =>  /.+@.+\..+/.test(v)|| 'E-mail must be valid']"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="12" md="6" lg="6">
                        <v-select v-model="editedItem.properties.role" :items="[ {name: 'Manager', value: 'MGR' },  { name: 'Cashier', value: 'CSH' }]" label="Role" hint="Point-of-Sale System role." item-text="name" item-value="value"></v-select>
                        <v-checkbox :false-value="0" :true-value="1" v-model="editedItem.properties.backoffice" label="Allow access to BackOffice"></v-checkbox>
                    </v-col>
                </v-row>
            </v-container>
        </template>
        <template v-slot:item.avatar="{item}">
            <v-avatar size="36px" v-if="!!item.avatar">
                <img :src="item.avatar" alt="Thumbnail">
            </v-avatar>
            <v-avatar size="36px" v-if="!item.avatar" color="primary">
                <span class="white--text">{{ item.name.charAt(0).toUpperCase() }}</span>
            </v-avatar>
        </template>
        <template v-slot:item.properties.backoffice="{item}">
            <v-icon color="success" v-if="item.properties.backoffice === 1">mdi-check</v-icon>
        </template>
        <template v-slot:item.properties.role="{item}">
            <span class="caption">{{ item.properties.role === 'MGR' ? 'MANAGER' : 'CASHIER' }}</span>
        </template>
        <template v-slot:item.assign="{item}">
            <v-dialog v-model="pinDialog" :overlay="true" max-width="500px" transition="dialog-transition">
                <template v-slot:activator="{ on }">
                    <v-btn color="secondary" dark class="mb-2" @click="pinDialogOpen(item)" v-on="on" :disabled="loading">
                        ****
                    </v-btn>
                </template>
                <v-card tile>
                    <v-toolbar flat dark color="primary" max-height="68">
                        <v-toolbar-title>Assign Security Pin Code</v-toolbar-title>
                        </v-menu>
                        <v-spacer></v-spacer>
                        <v-btn icon dark @click="pinDialogClose" :disabled="pinSaving">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <v-card-text>
                        <v-row>
                            <v-col cols="3" sm="3">
                                <v-text-field autocomplete="off" style="text-align:center" :autofocus="true" ref="pin1" v-model="pinValues[0]" maxLength="1" solo @keydown="pinEntered(0, $event)" @input="pinNext(0)" :disabled="pinSaving"></v-text-field>
                            </v-col>
                            <v-col cols="3" sm="3">
                                <v-text-field autocomplete="off" ref="pin2" v-model="pinValues[1]" maxLength="1" solo @keydown="pinEntered(1, $event)" @input="pinNext(1)" :disabled="pinSaving"></v-text-field>
                            </v-col>
                            <v-col cols="3" sm="3">
                                <v-text-field autocomplete="off" ref="pin3" v-model="pinValues[2]" maxLength="1" solo @keydown="pinEntered(2, $event)" @input="pinNext(2)" :disabled="pinSaving"></v-text-field>
                            </v-col>
                            <v-col cols="3" sm="3">
                                <v-text-field autocomplete="off" ref="pin4" v-model="pinValues[3]" maxLength="1" solo @keydown="pinEntered(3, $event)" @input="pinNext(3)" :disabled="pinSaving"></v-text-field>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn color="red darken-1" text @click="pinReset" :disabled="pinSaving">
                            Reset
                        </v-btn>
                        <v-spacer></v-spacer>
                        <v-btn color="green darken-1" text @click.stop="pinSave()" :disabled="pinSaving" :loading="pinSaving">
                            Save
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
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
            pinDialog: false,
            pinSaving: false,
            pinValues: [],
            selectedUser: -1,
            options: {
                sortBy: [],
                sortDesc: [],
                page: 1,
                itemsPerPage: 10,
            },
            loading: true,
            defaultItem: {
                name: '',
                email: '',
                properties: {
                    role: 'CSH',
                    backoffice: 0,
                }
            },
            headers: [
                { text: 'Photo', value: 'avatar', sortable: false, custom: true },
                { text: 'Name', value: 'name' },
                { text: 'Email', value: 'email', },
                { text: 'POS Role', value: 'properties.role', sortable: false, custom: true },
                { text: 'BackOffice', value: 'properties.backoffice', sortable: false, custom: true },
                { text: 'Pin', value: 'assign', sortable: false, custom: true },
                { text: 'Actions', value: 'action', sortable: false, hideTrash: 'tenant' },
            ],
            exportFields: {
                'name': 'name',
                'email': 'email',
                'pos role': 'properties.role',
                'backoffice': 'properties.backoffice',
            },
        }
    },
    computed: mapGetters({
        users: 'user/users',
        userCount: 'user/userCount',
    }),
    async mounted() {
        //  await this.retrieve(this.options)
    },
    methods: {
        async retrieve(search, options, noCommit = false) {

            this.loading = true
            const { sortBy, sortDesc, page, itemsPerPage } = options

            const results = await this.$store.dispatch('user/fetch', { search, limit: itemsPerPage, page, sort: sortBy, desc: sortDesc, noCommit })

            this.loading = false

            if (noCommit) return results
        },
        async save(user) {
            this.loading = true

            if (!user.id) {
                await this.$store.dispatch('user/addUser', user)
            } else {
                await this.$store.dispatch('user/updateUser', user)
            }




            this.loading = false
        },
        async remove(user) {
            this.loading = true
            await this.$store.dispatch('user/trashUser', user)
            this.loading = false

        },
        pinDialogOpen(item) {
            this.selectedUser = item.id
        },
        pinDialogClose() {
            this.pinReset()
            this.selectedUser = -1
            this.pinDialog = false

        },
        pinEntered(index, event) {
            if (!event.key.match(/\d{1}/g)) {
                event.preventDefault()
            }
        },
        pinReset() {
            this.pinValues = []
            this.$refs.pin1.focus()
        },
        pinNext(index) {
            if (index < 3) {
                const el = this.$refs['pin' + (index + 2)]
                el.focus()
            }
        },
        async pinSave() {
            const pin = this.pinValues.join('')
            this.pinSaving = true
            const data = await this.$store.dispatch('user/updatePin', { id: this.selectedUser, pin })
            if (data.errors) {
                this.$toast.error(data.errors[0].extensions.validation.pin[0])
            } else {
                this.$toast.success('New pin have been saved!')
            }

            this.pinSaving = false
            this.pinDialogClose()
        },
    },
}

</script>
