<template>
    <v-navigation-drawer fixed app :permanent="$vuetify.breakpoint.mdAndUp" light :clipped="$vuetify.breakpoint.mdAndUp" v-model="show" :width="getDeviceWidth()">
        <template v-slot:prepend>
            <v-toolbar dark dense flat color="secondary">
                <v-btn icon to="{name: 'pos'}">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-spacer></v-spacer>
                <v-toolbar-title class="white--text">Receipts</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon>
                    <v-icon>mdi-magnify</v-icon>
                </v-btn>
            </v-toolbar>
        </template>
        <div v-for="(items, key) in receipts" :key="key">
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

export default {
    data: () => ({
        show: false,
    }),
    props: ['showList'],
    components: {

    },
    mounted() {

        if (this.$vuetify.breakpoint.xs || this.$vuetify.breakpoint.sm) this.show = true

    },
    computed: mapGetters({
        receipts: 'receipt/groupDates',
        company: 'system/company',
        store: 'auth/store',
    }),
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
