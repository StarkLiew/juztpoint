<template>
    <div>
        <div class="title">{{ title }} Report</div>
        <div class="subtitle-2" v-if="data.shift.status === 'open'">Shift Open on {{ data.shift.open.date | moment('timezone', header.store.properties.timezone.replace(/\\/g, ''), 'DD/MM/YYYY hh:mmA') }}</div>
        <div class="subtitle-2" v-if="data.shift.status === 'close'">Shift Closed on {{ data.shift.close.date | moment('timezone', header.store.properties.timezone.replace(/\\/g, ''), 'DD/MM/YYYY hh:mmA') }}</div>
        <div class="subtitle-2" v-if="by">by {{ by.name }}</div>
        <br />
        <br />
        <table>
            <tr>
                <td class="caption center">Total Nett Sales</td>
                <td class="caption center">Transactions</td>
                <td></td>
            </tr>
            <tr>
                <td class="center">{{ data.nett | currency }}</td>
                <td class="center">{{ data.count}}</td>
                <td></td>
            </tr>
        </table>
        <br />
        <div class="caption">SUMMARY</div>
        <table>
            <tr>
                <td class="left">Gross Sales</td>
                <td>{{data.gross | currency}}</td>
            </tr>
            <tr>
                <td class="left">Refunds</td>
                <td>{{-data.refund | currency }}</td>
            </tr>
            <tr>
                <td class="left">Discount</td>
                <td>{{-data.footer_discount | currency}}</td>
            </tr>
            <tr>
                <td class="left">Nett Sales</td>
                <td>{{data.nett | currency}}</td>
            </tr>
            <tr>
                <td class="left">Rounding</td>
                <td>{{data.rounding | currency}}</td>
            </tr>
            <tr>
                <td class="left">Tax</td>
                <td>{{data.tax_total | currency}}</td>
            </tr>
            <tr>
                <td class="left">Total Received</td>
                <td>{{data.total_charge | currency}}</td>
            </tr>
        </table>
        <br />
        <div class="caption">PAYMENT</div>
        <table>
            <tr v-for="(value, key) in data.payment" :key="key">
                <td class="left">{{ key.toUpperCase() }}</td>
                <td>{{value | currency}}</td>
            </tr>
           
        </table>
        <br />
        <div class="caption">SHIFT</div>
        <table>
            <tr>
                <td class="left">Opening</td>
                <td>{{data.shift.open.amount | currency}}</td>
            </tr>
            <tr v-if="data.shift.status === 'close'">
                <td class="left">Drawer Balance</td>
                <td>{{data.shift.close.amount | currency}}</td>
            </tr>
            <tr v-if="data.shift.status === 'close'">
                <td class="left">Cash Received</td>
                <td>{{data.shift.close.amount - data.shift.open.amount| currency}}</td>
            </tr>
        </table>
                <table>
            <tr>
                <td class="left">Void</td>
                <td>{{ data.void }}</td>
            </tr>
 
        </table>
        <br />
        <br />
    </div>
</template>
<script>
export default {
    data() {
        return {}
    },
    props: ['title', 'header', 'data', 'by'],
}

</script>
<style>
@page {
    size: 80mm auto;
    margin: 5mm;
}

@media print {

    * {
        width: 70mm;
        height: auto;
        padding: 0;
        margin: 0;
        list-style-type: none;
        font-family: "arial";
        font-size: 8px;
    }
}


.title, .subtitle-2 {
    text-align: center;
    font-weight: bold;
}

.caption {
    font-weight: bold;
}

table {
    width: 100%;
}

table th {
    border: none;
    border-bottom: 1px dashed #000;
    padding: 1px;
    text-align: right;

}

table tr td {
    border: none;
    border-bottom: none;
    border-right: none;
    padding: 1px;
    text-align: right;

}

td.left,
th.left {
    text-align: left;
}

td.line {
    border-top: 1px dashed #000;
}
td.center {
      text-align: center;

}

</style>
