<template>
    <crud title="Employees" :headers="headers" :items.sync='users' sort-by="name" :refresh="retrieve" :default-item="defaultItem" :options.sync="options" :save-method="save" :remove-method="remove" :server-items-length="userCount" :loading="loading" loading-text="Loading..." :export-fields="exportFields">
        <template v-slot:dialog="{ valid, editedItem }">
            <v-container>
                <v-row>
                    <v-col cols="12" sm="12" md="6" lg="6">
                        <v-text-field v-model="editedItem.name" :rules="[v => !!v || 'Name is required',]" required label="Name"></v-text-field>
                        <v-text-field v-model="editedItem.email" :disabled="!editedItem.id ? false : true" label="Email" :rules="[v =>  /.+@.+\..+/.test(v)|| 'E-mail must be valid']"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="12" md="6" lg="6">
                        <v-select v-model="editedItem.properties.role" :items="[ {name: 'Manager', value: 'MGR' },  { name: 'Cashier', value: 'CSH' }]"  label="Role" hint="Point-of-Sale System role." item-text="name" item-value="value"></v-select>
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
            <v-icon color="success" v-if="item.properties.backoffice === 1">check</v-icon>
        </template>
        <template v-slot:item.properties.role="{item}">
            <span class="caption">{{ item.properties.role === 'MGR' ? 'MANAGER' : 'CASHIER' }}</span>
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
                { text: 'BackOffice', value: 'properties.backoffice', sortable: false, custom: true},
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

            const results = await this.$store.dispatch('user/fetchUsers', { search, limit: itemsPerPage, page, sort: sortBy, desc: sortDesc, noCommit })

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

        }
    },
}

</script>
