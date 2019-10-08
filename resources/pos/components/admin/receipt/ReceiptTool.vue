<template>
    <v-app-bar :clipped-left="$vuetify.breakpoint.mdAndUp" dark dense flat color="secondary">
        <v-app-bar-nav-icon @click.stop="listToggle"></v-app-bar-nav-icon>
        <v-spacer></v-spacer>
    </v-app-bar>
</template>
<script>
import { settings } from '~/config'

export default {
    data: () => ({

    }),

    methods: {
        listToggle() {
            this.$emit('list-toggle')
        }
    }
}

</script>
