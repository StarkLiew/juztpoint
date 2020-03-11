<template>
    <v-navigation-drawer fixed app :permanent="$vuetify.breakpoint.mdAndUp" light :clipped="$vuetify.breakpoint.mdAndUp" v-model="show" :width="getDeviceWidth()">
        <template v-slot:prepend>
            <v-toolbar dark dense flat color="secondary">
                <v-btn icon to="{name: 'pos'}">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-spacer></v-spacer>
                <v-toolbar-title class="white--text">Receipt</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-menu v-model="datemenu" :close-on-content-click="true" transition="scale-transition" offset-y max-width="290px" min-width="290px">
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" icon>
                            <v-icon>mdi-calendar</v-icon>
                        </v-btn>
                    </template>
                    <v-date-picker v-model="filterDate">
                    </v-date-picker>
                </v-menu>
            </v-toolbar>
        </template>
        <div v-for="(items, key) in filtered" :key="key">
            <v-subheader>{{ key | moment('timezone', store.properties.timezone.replace(/\\/g, ''),'DD/MM/YYYY') }}</v-subheader>
            <v-list-item two-line @click="select(item)" v-for="(item, index) in items" :key="index" :disabled="item.status === 'void' ||  item.status === 'void offline' ? true : false">
                <v-list-item-content>
                    <v-list-item-title>{{item.date + 'Z' | moment('timezone', store.properties.timezone.replace(/\\/g, ''),'hh:mmA') }}
                        <v-chip v-if="item.status === 'offline' || item.status === 'update offline'" class="ma-2" small color="grey">
                            {{ item.status }}
                        </v-chip>
                        <v-chip v-if="item.status === 'void' || item.status === 'void offline'" class="ma-2" small color="dark">
                            {{ item.status }}
                        </v-chip>
                        <v-chip v-if="item.status === 'active'" class="ma-2" small color="green">
                            {{ item.status }}
                        </v-chip>
                    </v-list-item-title>
                    <span class="overline">{{item.reference}}</span>
                </v-list-item-content>
                <v-btn icon>{{item.charge | currency}}</v-btn>
            </v-list-item>
            <v-divider></v-divider>
        </div>
    </v-navigation-drawer>
</template>
<script>
import { mapGetters } from 'vuex'
import vue from 'vue'

export default {
    data: () => ({
        show: false,
        filterDate: null,
        datemenu: false,
    }),
    props: ['showList'],
    components: {

    },

    mounted() {
        if (this.$vuetify.breakpoint.xs || this.$vuetify.breakpoint.sm) this.show = true
        this.filterDate = this.$moment(new Date()).format('Y-M-D')

    },

    computed: { ...mapGetters({
            receipts: 'receipt/groupDates',
            company: 'system/company',
            store: 'auth/store',
        }),
        filtered() {
            this.$emit('selected', null)

            return Object.keys(this.receipts).filter(key => key === this.filterDate).reduce((obj, key) => {
                obj[key] = this.receipts[key]
                return obj
            }, {})
        }
    },
    watch: {
        showList(val) {
            this.show = val
        },
        show(val) {

            this.$emit('list-toggle', val)
        },
    },
    methods: {

        isSameDate(item, index) {

        },
        select(item) {
            this.$emit('selected', item)
            this.show = false
        },
        getDeviceWidth() {
            if (this.$vuetify.breakpoint.xs || this.$vuetify.breakpoint.sm) {
                return this.$vuetify.breakpoint.width
            }

            return 370

        },
    }
}

</script>
<style>
.v-navigation-drawer__content {
    max-height: 100vh;
}

</style>
