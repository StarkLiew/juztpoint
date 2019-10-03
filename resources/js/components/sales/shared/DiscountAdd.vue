<template>
    <v-card>
        <v-toolbar flat dark color="primary">
            <v-btn icon dark @click="cancel()">
                <v-icon>close</v-icon>
            </v-btn>
            <v-spacer></v-spacer>
            <span>Discount</span>
            <v-spacer></v-spacer>
            <v-btn icon dark text @click="done()">
                Done
            </v-btn>
        </v-toolbar>
        <v-divider></v-divider>
        <v-toolbar flat>
            <v-spacer></v-spacer>
            <v-flex class="subheader">
                <v-icon>label</v-icon>Discount
            </v-flex>
            <v-flex class="display-1" @click="showKeyboard = true">
                {{discountRate | currency({fractionCount: decimal})}}
            </v-flex>
            <v-btn-toggle v-model="discountType" @change="(val) => { decimal = val + 1 }">
                <v-btn large text>
                    %
                </v-btn>
                <v-btn large text>
                    $
                </v-btn>
            </v-btn-toggle>
        </v-toolbar>
        <keyboard @done="showKeyboard = false" @clear="discountRate = 0.0" @change="discountRateChange" @close="closedKeyboard" :decimal="decimal" :show="showKeyboard">
        </keyboard>
    </v-card>
</template>
<script>
import Keyboard from '../../ui/Keyboard'

export default {
    data: () => ({

        discountFixed: false,
        showKeyboard: false,
        discountRate: 0.0,
        decimal: 1,
        discountType: 0,
    }),
    components: {
        Keyboard,
    },

    props: ['discount'],
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
            if (this.discount) {
                this.discountType = this.parseDiscountType(this.discount.discountType)
                this.discountRate = this.discount.rate
            }

        },

        done() {

            const { discountRate, discountType } = this

            let discount = { rate: discountRate, type: this.parseDiscountType(discountType) }

            this.$emit('done', discount)
        },
        cancel() {
            this.$emit('cancel')
        },
        discountRateChange(val) {
            this.discountRate = val
        },
        parseDiscountType(discountType) {

            if (discountType === 0) {
                return 'percent'
            }

            if (discountType === 1) {
                return 'fix'
            }

            if (discountType === 'fix') {
                return 1
            }

            return 0

        },
        closedKeyboard() {

            showKeyboard = false
        }
    }
}

</script>
