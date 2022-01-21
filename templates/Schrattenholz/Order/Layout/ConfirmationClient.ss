<h3 style="font-size: 1.22em;  font-weight: 400;  line-height: 1.375em;  margin-bottom: 0.6815em;">Guten Tag $CheckoutAddress.Gender $CheckoutAddress.FirstName $CheckoutAddress.Surname</h3>
 $OrderConfig.ConfirmationMailBeforeContent
</p>
<h3 style="font-size: 1.22em;  font-weight: 400;  line-height: 1.375em;  margin-bottom: 0.6815em;">Produkte auf der Bestellliste</h3>
<table class="table table-striped" style="  background-color: transparent;  border-collapse: collapse;  border-spacing: 0;  max-width: 100%;">
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
					<span class="text-muted mr-2"><% loop $PriceBlockElement %>$FullTitle<% end_loop %</span>
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
		
				<h3>Lieferoption</h3>
		$Basket.getVersandInfo.RAW
<h3>Ihre Daten</h3>
		
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
		  <div>
		  $OrderConfig.ConfirmationMailAfterContent
		  
		  </div>
		  
		  <div>
		  $OrderConfig.EmailSignature
		  
		  </div>
