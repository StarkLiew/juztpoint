<template>
    <div class="fill-height">
        <receipt-list @list-toggle="listToggleUpdate" :show-list.sync="showList" @selected="selected" :show="showList"> </receipt-list>
        <receipt-tool @list-toggle="listToggle"></receipt-tool>
        <v-content style="margin-top: 5px;height: calc(100vh - 54px);">
            <receipt-view :selected="selectedItem"></receipt-view>
            <v-overlay :value="overlay">
                <v-progress-circular indeterminate size="64"></v-progress-circular>
            </v-overlay>
        </v-content>
    </div>
</template>
<script>
import ReceiptTool from './ReceiptTool'
import ReceiptList from './ReceiptList'
import ReceiptView from './ReceiptView'

export default {
    data: () => ({
        overlay: false,
        showList: false,
        selectedItem: null,
    }),
    components: {
        ReceiptTool,
        ReceiptList,
        ReceiptView,
    },

    methods: {
        listToggle() {
            this.showList = !this.showList
        },
        listToggleUpdate(val) {
            this.showList = val
        },
        overlayShow() {
            this.overlay = true
        },
        listToggle() {
            this.showList = !this.showList
        },
        selected(item) {

            this.selectedItem = item
        },
    }
}

</script>
