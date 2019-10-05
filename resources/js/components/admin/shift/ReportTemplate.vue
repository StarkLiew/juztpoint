<template>
    <div>
        <div class="title">{{ header.company.name }}</div>
        <div class="title">{{ header.company.properties.address }}</div>

        <br />
        <br />
        <div class="title">{{ title }} Report</div>
        <br />
        <br />

    </div>
</template>
<script>
export default {
    data() {
        return {}
    },
    props: ['title', 'header'],
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

    .title {
        text-align: center;
        font-weight: bold;
    }

    .caption {
        font-weight: bold;
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
}

</style>
