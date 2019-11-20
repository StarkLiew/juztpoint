<p>Hi {{ $name }},</p>
<p>Thank you for </p>
<p>Here is your receipt:</p>

    <div class="content">
        @if(isset($value['status'])
         <div class="stamp" :if="value.status === 'void'">{{ $value['status'] }}</div>
         @endif
        <div class="title">{{ $header['company']['name'] }}</div>
        <div class="title">{{ $header['company']['properties']['address'] }}</div>
        <br />
        <br />
        <div class="title">Receipt/Tax Invoice</div>

        <br />
        <br />
        @if(isset($value['customer'])
        <div class="caption">{{ $value['customer']['name'] }}</div>
        @endif
        <br />
        <table>
            <tbody>
                <tr>
                    <td>Date:</td>
                    <td class="left">{{ (new DateTime("now",  new DateTimeZone($ header['store']['properties']['timezone']))->setTimestamp(strtotime($value['date']))->format('d.m.Y, H:i') }}</td>
                </tr>
                <tr>
                    <td>Reference:</td>
                    <td class="left">{{ $value['reference'] }}</td>
                </tr>
                <tr>
                    <td>Cashier:</td>
                    <td class="left" v-if="value.teller">{{ $value[teller][name] }}</td>
                </tr>
            </tbody>
        </table>
        <table>
            <thead>
                <tr>
                    <th class="left">Item/<br />Serve by</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                 @foreach($value['items'] as $item)
                <div>
                    <tr>
                        <td colspan="5" class="left">{{ $item['name'] }}</td>
                    </tr>
                    <tr>

                        <td class="left">{{ $item['saleBy']['name'] }}</td>
                        <td>{{number_format((float) $item['properties']['price'], 2, '.', '')}}</td>
                        <td>{{ number_format((float)$item['discount']['amount'], 2, '.', '') }}</td>
                        <td>{{ $item['qty'] }}</td>
                        <td>{{ number_format((float)$item['amount'], 2, '.', '') }}</td>
                        <td>{{ $item['tax']['properties'].[code] }}</td>
                    </tr>
                </div>
                 @endforeach
                <div>
                    <tr>
                        <td class="line"></td>
                        <td class="line"></td>
                        <td class="line"></td>
                        <td class="line">Discount</td>
                        <td class="line">{{ number_format((float)$value['discount_amount'], 2, '.', '')  }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Service C.</td>
                        <td>{{ number_format((float)$value['service_charge'], 2, '.', '')  }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Tax</td>
                        <td>{{ number_format((float)$value['tax_total'], 2, '.', '')  }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Rounding</td>
                        <td>{{ number_format((float)$value['rounding'], 2, '.', '')  }}</td>
                    </tr>
                    <tr>
                        <td class=""></td>
                        <td class=""></td>
                        <td class=""></td>
                        <td class="line">Total</td>
                        <td class="line">{{ number_format((float)$value['charge'], 2, '.', '')  }}</td>
                    </tr>
                     @foreach($value['payments'] as $payment)
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Received {{ payment['name'] }}</td>
                        <td>{{ number_format((float)$payment['total_amount'] , 2, '.', '')  }}</td>
                    </tr>
                          @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="line">Change</td>
                        <td class="line">{{ number_format((float)$value['change'] , 2, '.', '')  }}</td>
                    </tr>
                    <tr v-if="value.refund">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="line">Refund</td>
                        <td class="line">{{ number_format((float)$value['refund'] , 2, '.', '')  }}</td>
                    </tr>
                </div>
            </tbody>
        </table>
        <br />
        <br />
        <div class="title">Thank you!</div>
    </div>

<div>
<p>This is a system notification. Please do not reply to this email.</p>
<br />&copy; 2019. All rights reserved.
<p>JuztPoint, proudly made in Seremban, Malaysia</p>
</div>


<style>


    .content {
        width: 70mm;
        height: auto;
        padding: 0;
        margin: 0;
        list-style-type: none;
        font-family: "arial";
        font-size: 8px;
        color: #eeeeee;
    }
    .stamp {

        font-weight: bold;
        font-size: 30px;
        position:fixed;
        left: -12px;
        top: -12px;
        text-transform: uppercase;
         transform: rotate(-45deg);

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

</style>