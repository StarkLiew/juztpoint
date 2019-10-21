<template>
    <v-row>
        <v-col col="6" lg="6" md="6" sm="12" v-for="(item, i) in items" :key="i">
            <v-card min-height="300" class="mx-auto" color="grey lighten-4" max-width="600">
                <v-card-title>
          
                        <div class="caption text-uppercase">
                           {{ item.title }}
                        </div>
                             
                    <v-spacer></v-spacer>

                </v-card-title>
                <v-sheet color="transparent">
                </v-sheet>
            </v-card>
        </v-col>
        <v-col col="6" lg="6" md="6" sm="12">
        </v-col>
    </v-row>
</template>
<script>
import { mapGetters } from 'vuex'

export default {
    components: {

    },
    data() {
        return {
            checking: false,
            heartbeats: [],
            items: [],
        }
    },
    computed: {
   
    },
    async mounted() {
       this.initialize()
    },
    methods: {
        initialize() {
             this.items = [
                 {title: 'Recent Sales'},
                 {title: 'Top Sevices'},
                 {title: 'Top Products'},
                 {title: 'Top Employee'},
             ]
        },

    }
}

</script>
