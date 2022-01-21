<style>

.noBorder{
border:0;
}
h3, legend {
  font-size: 1.22em;
  font-weight: 400;
  line-height: 1.375em;
  margin-bottom: 0.6815em;
}
table {
  background-color: transparent;
  border-collapse: collapse;
  border-spacing: 0;
  max-width: 100%;
}
.table {
  margin-bottom: 20px;
  width: 100%;
}
.table th, .table td {
  border-top: 1px solid #dddddd;
  line-height: 20px;
  padding: 8px;
  text-align: left;
  vertical-align: top;
}
.table-striped tbody > tr:nth-child(2n+1) > td, .table-striped tbody > tr:nth-child(2n+1) > th {
  background-color: #f9f9f9;
}
.table-striped tbody tr:nth-child(2n+1) td, .table-striped tbody tr:nth-child(2n+1) th {
  background-color: #f5f5f5;
}
</style>
<h3>Guten Tag</h3>
<p>
folgende Bestellung ist soeben eingegangen:
</p>
<h3>Produkte auf der Bestellliste</h3>
<table class="table table-striped">
          <thead>
            <tr>
              <th style="border-top: 1px solid #dddddd;  line-height: 20px;  padding: 8px;  text-align: left;  vertical-align: top;">Produkt </th>
			   <th style="border-top: 1px solid #dddddd;  line-height: 20px;  padding: 8px;  text-align: left;  vertical-align: top;"></th>
              <th style="border-top: 1px solid #dddddd;  line-height: 20px;  padding: 8px;  text-align: left;  vertical-align: top;">Anzahl</th>
			  <th style="border-top: 1px solid #dddddd;  line-height: 20px;  padding: 8px;  text-align: left;  vertical-align: top;"></th>
            </tr>
          </thead>
		   <tbody>
		 <% loop $Basket.ProductContainers.Sort('ProductSort') %>
			<tr id="product_{$ID}" style="<% if $Even %>background-color: #d7e6fa;<% else %>background-color: #f5f5f5;<% end_if %>">
				<td style="border-top: 1px solid #dddddd;  line-height: 20px;  padding: 8px;  text-align: left;  vertical-align: top;">$Product.SummaryTitle</td>
				
				<td style="border-top: 1px solid #dddddd;  line-height: 20px;  padding: 8px;  text-align: left;  vertical-align: top;">
				<% if $PriceBlockElement %>
					<div class="font-size-sm">
					<span class="text-muted mr-2"><% loop $PriceBlockElement %>$FullTitle<% end_loop %>
					</span>
					</div>					
				<% end_if %>
				</td>
				<td style="border-top: 1px solid #dddddd;  line-height: 20px;  padding: 8px;  text-align: left;  vertical-align: top;"><% if $PriceBlockElement.Portionable %>$formattedWeight($Quantity)<% else %>{$Quantity}stk<% end_if %></td>
				
			</tr>
		  <% end_loop %>

		 </tbody>
        </table>
		<h3>Anmerkungen zur Bestellung</h3>
		$Basket.AdditionalNotes
				<h3>Lieferart</h3>
				<% if $Basket.DeliveryType.Type=="collection" %>
		$Basket.DeliveryType.Title am $Basket.CollectionDay.Title
		<% else_if  $Basket.DeliveryType.Type=="delivery" %>
		$Basket.DeliveryType.Title am $Basket.DeliveryDate.Nice
		<% end_if %>
		<h3>Angaben zum Auftraggeber</h3>
		
		 <div class="span4" style="margin-left:0;">
			<table class="table table-condensed">
				<thead>
				<tr>
				  <th>Kontaktdaten</th>
				  <th></th>
				  
				</tr>
			  </thead>
				<tbody>
					<tr><td class="noBorder">Firmenname:</td><td>$CheckoutAddress.Company</td></tr>
					<tr><td class="noBorder">Strasse:</td><td class="noBorder">$CheckoutAddress.Street</td></tr>
					<tr><td class="noBorder">PLZ:</td><td class="noBorder">$CheckoutAddress.ZIP</td></tr>
					<tr><td class="noBorder">Ort:</td><td class="noBorder">$CheckoutAddress.City</td></tr>
				</tbody>
			</table>
          </div>
          <div class="span4">
		  <table class="table table-condensed">
			<thead>
				<tr>
				  <th>Ansprechpartner</th>
				  <th></th>
				  
				</tr>
			  </thead>
				<tbody>
					<tr><td width="20%">Vorname:</td><td width="80%">$CheckoutAddress.FirstName</td></tr>
					<tr><td class="noBorder">Nachname:</td><td class="noBorder">$CheckoutAddress.Surname</td></tr>
					<tr><td class="noBorder">Telefon:</td><td class="noBorder">$CheckoutAddress.PhoneNumber</td></tr>
					<tr><td class="noBorder">Email:</td><td class="noBorder">$CheckoutAddress.Email</td></tr>
				</tbody>
			</table>
		</div>
$OrderConfig.EmailSignature