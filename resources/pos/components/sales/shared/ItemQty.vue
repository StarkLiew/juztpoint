<template>
    <v-card>
        <v-toolbar flat dark color="primary">
            <v-btn icon dark @click="cancel()">
                <v-icon>mdi-close</v-icon>
            </v-btn>
            <v-spacer></v-spacer>
            <v-toolbar-title>{{item.name}}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon dark text @click="done()">
                Done
            </v-btn>
        </v-toolbar>
        <v-toolbar flat>
            <v-btn color="success" large dark @click="inc(1, 'qty')">
                <v-icon>mdi-plus</v-icon>
            </v-btn>
            <v-spacer></v-spacer>
            <v-toolbar-title class="display-1">{{ qty }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn color="success" large dark @click="inc(-1, 'qty')">
                <v-icon>mdi-minus</v-icon>
            </v-btn>
        </v-toolbar>
    </v-card>
</template>
<script>
import { mapGetters } from 'vuex'

export default {
    data: () => ({
        valid: false,
        lazy: false,
        value: '0',
        qty: 1,
        showKeyboard: false,
        decimal: 1,
        tab: 'tab-1',
    }),
    components: {

    },
    computed: mapGetters({
        users: 'user/users',
    }),
    props: ['item', 'index', 'show'],
    mounted() {
        this.update()
    },
    watch: {
        show: {
            handler(val) {
                this.update()
            },
            deep: true
        }
    },
    methods: {
        update() {
            this.qty = this.item.qty
        },
        inc(neg, prop) {
            let val = parseFloat(this.qty) + neg
            if (val > -1) this.qty = val
        },
        done() {
            const { qty } = this
            let item = { ...this.item }
            item.qty = qty
            this.$emit('done', item, this.index)
            this.$emit('cancel')
        },
        cancel() {
            this.$emit('cancel')
        },

    }
}

</script>
