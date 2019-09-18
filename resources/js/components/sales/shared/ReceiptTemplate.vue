<template>
    <div>

    	<div class="title">{{ header.company.name }}</div>
    	<div class="title">{{ header.company.properties.address }}</div>
    	<br />
    	<br />
    	<div class="title">Receipt/Tax Invoice</div>
    	<br />
    	<br />
    	<div class="caption" v-if="value.customer">{{ value.customer.name }}</div>
    	<br />
        <table>
    		 <tbody>
    		 	 <tr>
    		 	 	<td>Date:</td><td  class="left">{{ value.date + 'Z'| moment('timezone', header.store.properties.timezone.replace(/\\/g, ''), 'DD/MM/YYYY hh:mmA') }}</td>
    		 	 </tr>
    		 	 <tr>
    		 	 	<td>Reference:</td><td  class="left">{{ value.reference }}</td>
    		 	 </tr>
    		 	 <tr>
    		 	 	<td>Cashier:</td><td  class="left">{{ value.teller.name }}</td>
    		 	 </tr>
    		 </tbody>
    	</table>
	    
          
	    <table>
		    <thead>
		      <tr>
		      	<th class="left">Item/<br/>Serve by</th>
		        <th>Price</th>
		        <th>Discount</th>
		        <th>Qty</th>
		        <th>Subtotal</th>
		        <th></th>
		      </tr>
		    </thead>
		    <tbody>
		      <div v-for="item in value.items" :key="item.name">	
				     <tr><td colspan="5" class="left">{{ item.name }}</td></tr>
				     <tr>
				     	<td  class="left">{{ item.saleBy.name }}</td>
				     	<td>{{ item.properties.price | currency }}</td>
				     	<td>{{ item.discount.amount | currency }}</td>
				     	<td>{{ item.qty  }}</td>
				     	<td>{{ item.amount | currency }}</td>
				     	<td>{{ item.tax.properties.code }}</td>
				     </tr>
		      </div>
		      <div>	
		      	     <tr>
				     	<td class="line"></td>
				     	<td class="line"></td>
				     	<td class="line"></td>
				     	<td class="line">Discount</td>
				     	<td class="line">{{ value.discount_amount | currency }}</td>
				     </tr>
				     <tr>
				     	<td></td>
				     	<td></td>
				     	<td></td>
				     	<td>Service C.</td>
				     	<td>{{ value.service_charge | currency }}</td>
				     </tr>
				     <tr>
				     	<td></td>
				     	<td></td>
				     	<td></td>
				     	<td>Tax</td>
				     	<td>{{ value.tax_total | currency }}</td>
				     </tr>
				     <tr>
				     	<td></td>
				     	<td></td>
				     	<td></td>
				     	<td>Rounding</td>
				     	<td>{{ value.rounding | currency }}</td>
				     </tr>
				     <tr>
				     	<td class=""></td>
				     	<td class=""></td>
				     	<td class=""></td>
				     	<td class="line">Total</td>
				     	<td class="line">{{ value.charge | currency }}</td>
				     </tr>
				     <tr  v-for="payment in value.payments" :key="payment.name">
				     	<td></td>
				     	<td></td>
				     	<td></td>
				     	<td>Received {{ payment.name }}</td>
				     	<td>{{ payment.total_amount | currency }}</td>
				     </tr>
				     <tr>
				     	<td></td>
				     	<td></td>
				     	<td></td>
				     	<td class="line">Change</td>
				     	<td class="line">{{ value.change | currency }}</td>
				     </tr>
		      </div>
	         

		    </tbody>
	  </table>
	  <br />
	  <br />
	  <div class="title">Thank you!</div>

    </div>


</template>

<script>
   export default { 
      data() {
      	 return {}
      },
      props: ['value', 'header'],
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
			td.left, th.left  {
               text-align: left;
			}
		    td.line {
				 border-top: 1px dashed #000;
			}
	}
</style>