<template>
    <div>
        <!-- <receipt v-model="selected.item" :header="{company: selected.company, store: selected.item.store}"></receipt> -->
        {{ selected }}
    </div>
</template>
<script>
import receipt from "../../pos/components/sales/shared/ReceiptTemplate"

import { mapGetters } from 'vuex'
import { bus } from '../../backoffice/app';

export default {
    components: {
        receipt
    },
    created() {
        $bus.$on('selected', (data) => {
            this.selected = data;
        })
    },
    data: () => ({
        selected: null,
    }),


}

</script>
