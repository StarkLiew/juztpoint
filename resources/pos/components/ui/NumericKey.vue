<template>
    <v-layout justify-center>
        <v-flex xs8 sm4>
            <v-container fluid grid-list-sm>
                <v-layout wrap>
                    <v-flex v-for="(key, i) in keys" :key="i" xs4 @click="touched(key)">
                        <v-img :src="``" :lazy-src="``" aspect-ratio="1" v-ripple class="blue lighten-2 v-btn">
                            <template v-slot:placeholder>
                                <v-layout fill-height align-center justify-center ma-0>
                                </v-layout>
                            </template>
                            <v-layout pa-4 column fill-height class="lightbox white--text text-center justify-center">
                                <v-spacer></v-spacer>
                                <v-flex>
                                    <div class="v-icon text-wrap" v-if="key.length < 2">{{ key }}</div>
                                    <v-icon v-if="key.length > 1" color="white">{{key}}</v-icon>
                                </v-flex>
                            </v-layout>
                        </v-img>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-flex>
    </v-layout>
</template>
<script>
export default {
    data: () => ({
        valid: false,
        lazy: false,
        tab: 'tab-1',
        val: '0',
        keys: ['1', '2', '3', '4', '5', '6', '7', '8', '9', 'mdi-close', '0', 'mdi-check', ],
    }),
    mounted() {

    },
    props: [
        'decimal',

    ],
    methods: {
        touched(key) {

            if (key == 'mdi-close') {
                this.val = '0'
                this.$emit('clear')
                return
            }
            if (key == 'mdi-check') {
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
