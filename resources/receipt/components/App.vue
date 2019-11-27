<template>
    <div>
        <!-- <receipt v-model="selected.item" :header="{company: selected.company, store: selected.item.store}"></receipt> -->
        {{ selected }}
    </div>
</template>
<script>
import receipt from "../../pos/components/sales/shared/ReceiptTemplate"

import { mapGetters } from 'vuex'

export default {
    components: {
        receipt
    },
    data: () => ({
        selected: null,
    }),
    mounted() {
        this.$on("selected-receipt", message => this.selected = message)
    }

}

</script>
