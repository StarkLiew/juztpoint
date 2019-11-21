<template>
    <v-app id="app">
        <div>
             <receipt v-model="item" :header="{company, store}"></receipt>
        </div>
    </v-app>
</template>
<script>
import receipt from "../../pos/components/sales/shared/ReceiptTemplate"
export default {
    components: {
        receipt,
    },
    data: () => ({
        company: null,
        store: null,
        item: null,
    }),
}

</script>
