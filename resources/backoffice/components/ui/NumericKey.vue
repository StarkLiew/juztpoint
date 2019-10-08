<template>
    <v-layout justify-center>
   
            <v-container fluid grid-list-sm style="width:200px">
                <v-layout wrap style="width:200px">
                    <v-flex v-for="(key, i) in keys" :key="i" xs4 @click="touched(key)">
                        <v-img :src="``" :lazy-src="``" aspect-ratio="1" v-ripple class="blue lighten-2 v-btn">
                            <template v-slot:placeholder>
                                <v-layout fill-height align-center justify-center ma-0>
                                </v-layout>
                            </template>
                            <v-layout pa-2 column fill-height class="lightbox white--text text-center">
                                <v-spacer></v-spacer>
                                <v-flex shrink>
                                    <div class="subheading text-wrap">{{ key }}</div>
                                    <v-icon color="white">{{key}}</v-icon>
                                </v-flex>
                            </v-layout>
                        </v-img>
                    </v-flex>
                </v-layout>
            </v-container>
    
    </v-layout>
</template>
<script>
export default {
    data: () => ({
        valid: false,
        lazy: false,
        tab: 'tab-1',
        val: '0',
        keys: ['1', '2', '3', '4', '5', '6', '7', '8', '9', 'clear', '0', 'done', ],
    }),
    mounted() {

    },
    props: [
        'decimal',

    ],
    methods: {
        touched(key) {

            if (key == 'clear') {
                this.val = '0'
                this.$emit('clear')
                return
            }
            if (key == 'done') {
                this.val = '0'
                this.$emit('done')
                return
            }
            let val = this.val

            val = val.toString().replace('.', '')


            val = val.toString() + key.toString()

            val = parseFloat(parseInt(val) / Math.pow(10, this.decimal)).toFixed(this.decimal)

            this.val = val
            this.$emit('change', val)

        },
        close() {
            this.$emit('close')
        }


    }
}

</script>
