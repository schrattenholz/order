<% include PageTitleOverlap %>
    <!-- Page Content-->
	<div class="container pb-5 mb-2 mb-md-4">
	<div class="row">
	<section id="content" class="col-lg-8">
				<% include Schrattenholz/Order/Includes/CheckoutSteps %>
	
<!-- Order details-->
<% if $Basket.ProductContainers %>  
          <h2 class="h6 pt-1 pb-3 mb-3 border-bottom">Deine Bestellung in der Übersicht</h2>
       <% loop $Basket.ProductContainers.Sort("ProductSort") %>
		 <!-- Item-->
          <div class="d-sm-flex justify-content-between my-4 pb-3 border-bottom">
            <div class="media media-ie-fix d-block d-sm-flex text-center text-sm-left w-100"><a class="d-inline-block mx-auto mr-sm-4" href="$Product.Link?id=$ID&v=$PriceBlockElement.ID&vac=$Vacuum" style="width: 10rem;"><img src="$Product.ProductImages.First.Fill(200,200).URL" alt="Zum Produkt wechseln"></a>
              <div class="media-body pt-2">
                <h3 class="product-title font-size-base mb-2"><a href="$Product.Link?id=$ID&v=$PriceBlockElement.ID&vac=$Vacuum">$Product.SummaryTitle</a></h3>
                <% if $PriceBlockElement %>
					<div class="font-size-sm">
					<span class="text-muted mr-2"><% loop $PriceBlockElement %>$FullTitle<% end_loop %></span>
					</div>
					
				<% end_if %>
				<% if $ProductOptions.Filter('Active',1).Count>0 %>
				<h6>Zusatzoptionen</h6>
					<dl class="row mb-0 font-size-xs">
					<% loop $ProductOptions %>
						<% if  $ProductOptions_ProductContainer.Active  %>
						<dt class="col-sm-9 mb-0">$Title</dt>
							  <dd class="col-sm-3 mb-0 font-size-xs"> $Top.formattedNumber($ProductOptions_ProductContainer.Price) &euro;</dd>
						<% end_if %>
					<% end_loop %>
					</dl>
				<% end_if %>
                <div class="font-size-lg text-accent pt-2"><% if $CompletePrice.CaPrice %>ca. <% end_if %>$Top.formattedNumber($CompletePrice.Price) &euro;</div>
              </div>
            </div>
            <div class="pt-2 pt-sm-0 pl-sm-3 mx-auto mx-sm-0 text-center text-sm-right" style="max-width: 9rem;">
              <p class="mb-0"><span class="text-muted font-size-sm"><% if $PriceBlockElement.Portionable %>Menge<% else %>Anzahl in Stück<% end_if %>:</span><span>&nbsp;<% if $PriceBlockElement.Portionable %>$Top.formattedWeight($Quantity)<% else %>$Quantity<% end_if %></span></p>
              <!--<button class="btn btn-link px-0" type="button"><i class="czi-edit mr-2"></i><span class="font-size-sm">Edit</span></button>-->
            </div>
          </div>
		  <% end_loop %>

<!-- Client details-->
<form id="checkoutSummary" class="needs-validation" novalidate>
		  <div class="custom-control custom-checkbox  pt-3 mt-4 border-top">
				<input class="custom-control-input" type="checkbox" name="agb" id="agb" style="margin-top:-2px" required />
					 <label class="custom-control-label" for="agb" >Ich habe die <a href="$LinkAGB"  target="_blank" >Allgemeinen Geschäftsbedingungen</a> gelesen und verstanden.</label>
		</div>
		<div class="custom-control custom-checkbox  pb-3 mb-4 border-bottom">
		  <input class="custom-control-input"  type="checkbox" name="datenschutz" id="datenschutz" style="margin-top:-2px" required />
		  
					 <label class="custom-control-label" for="datenschutz">Ich stimme zu, dass meine Angaben aus dem Bestellprozess zur Bearbeitung meiner Bestellung erhoben und verarbeitet werden. Die Daten werden ausschließlich für die Bearbeitung der Bestellung und der dazu nötigen Prozesse verwendet.</label> 
					 <p class="font-size-sm">Detaillierte Informationen zum Umgang mit Nutzerdaten finden Sie in unserer <a target="_blank" href="{$BaseHref}datenschutzerklaerung" >Datenschutzerklärung</a>.</p>
		  </div>
		  
		
          <!-- Navigation (desktop)-->
          <div class="d-none d-lg-flex pt-4">
		  
					 
            <div class="w-50 pr-3"><a class="btn btn-secondary btn-block" href="$LinkCheckoutDelivery"><i class="czi-arrow-left mt-sm-0 mr-1"></i><span class="d-none d-sm-inline">Zurück zu den Lieferoptionen</span><span class="d-inline d-sm-none">Zurück</span></a></div>
            <div class="w-50 pl-2"><button type="submit" class="bestellenBtn btn btn-primary btn-block" link="$LinkCheckoutFinal"><span class="d-none d-sm-inline">Verbindlich bestellen</span><span class="d-inline d-sm-none">Verbindlich bestellen</span><i class="czi-arrow-right mt-sm-0 ml-1"></i></button></div>
          </div>

<% end_if %>
</section>
<!-- Sidebar-->
        <aside class="col-lg-4 pt-4 pt-lg-0">
		<% if $Basket.ProductContainers %>  
          <div class="cz-sidebar-static rounded-lg box-shadow-lg ml-lg-auto">
            <h2 class="h6 text-center text-md-left mb-4"><i class="h6 text-body czi-euro-circle"></i>&nbsp;Gesamtkosten</h2>

            <h3 class="font-weight-normal text-right my-4"><% if $Basket.TotalPrice.CaPrice %>ca. <% end_if %>$Top.formattedNumber($Basket.TotalPrice.Price) &euro;</h3>
			            <ul class="list-unstyled font-size-sm pb-2 border-bottom">
              <!--<li class="d-flex justify-content-between align-items-center"><span class="mr-2">Preis:</span><span class="text-right"><% if $Basket.TotalPrice.CaPrice %>ca. <% end_if %>$Top.formattedNumber($Basket.TotalPrice.Price) &euro;</span></li>
              <li class="d-flex justify-content-between align-items-center"><span class="mr-2">Shipping:</span><span class="text-right">—</span></li>-->
              <li class="d-flex justify-content-between align-items-center"><span class="mr-2">
			  <% if $Top.CurrentOrderCustomerGroup.VatExluded %>
			  zzgl. 
			  <% else %>
			  inkl. 
			  <% end_if %>
			  MwSt.({$Top.CurrentOrderCustomerGroup.Vat}%):</span>
			  <span class="text-right">
				<% if $Basket.TotalPrice.CaPrice %>ca. <% end_if %>$Top.formattedNumber($Basket.TotalPrice.Vat) &euro;
			  </span>
			  </li>
             <!-- <li class="d-flex justify-content-between align-items-center"><span class="mr-2">Discount:</span><span class="text-right">—</span></li>-->
            </ul>
			<p class="font-size-xs">
			Die angebenen Kosten sind ein Richtwert für Sie. Den tats&auml;chlichen Preis k&ouml;nnen wir erst nach dem Auswiegen der gew&uuml;nschten Produkte bestimmen.</p>
			</div>
		  <div class="cz-sidebar-static rounded-lg box-shadow-lg ml-lg-auto mt-3">
            <h2 class="h6 mb-4 text-center text-md-left"><i class="h6 text-body czi-announcement"></i>&nbsp;Zus&auml;tzliche Anmerkungen</h4>
                $Basket.AdditionalNotes
          </div>
			 <div class="cz-sidebar-static rounded-lg box-shadow-lg ml-lg-auto mt-3">
            <h2 class="h6 mb-4 text-center text-md-left"><i class="h6 text-body czi-home"></i>&nbsp;Adresse</h4>
                <ul class="list-unstyled font-size-sm">
                  <li><span class="text-muted">Kontakt:&nbsp;</span>$CheckoutAddress.FirstName $CheckoutAddress.Surname</li>
                  <li><span class="text-muted">Adresse:&nbsp;</span>$CheckoutAddress.Street, $CheckoutAddress.ZIP $CheckoutAddress.City</li>
                  <li><span class="text-muted">Telefon:&nbsp;</span>$CheckoutAddress.PhoneNumber</li>
				  <li><span class="text-muted">E-Mail:&nbsp;</span>$CheckoutAddress.Email</li>
                </ul>
          </div>

		  <div class="cz-sidebar-static rounded-lg box-shadow-lg ml-lg-auto mt-3">
            <h2 class="h6 mb-4 text-center text-md-left"><i class="h6 text-body czi-package"></i>&nbsp;Lieferart</h4>
                
                <ul class="list-unstyled font-size-sm">
                  <li class="text-center text-md-left"><strong>$Basket.DeliveryType.Title</strong> $Basket.VersandInfo().RAW</li>
                </ul>
          </div>
		  <div class="cz-sidebar-static rounded-lg box-shadow-lg ml-lg-auto mt-3">
            <h2 class="h6 mb-4 text-center text-md-left"><i class="h6 text-body czi-package"></i>&nbsp;Bezahlart</h2>
                
                <ul class="list-unstyled font-size-sm">
                  <li class="text-center text-md-left"><strong>$Basket.PaymentMethod.Title</strong></li>
				  <% if $Basket.PaymentMethod.Template=="Schrattenholz\Payment\Templates\PaymentMethod_SEPA" %>
				  <li class="text-center text-md-left"><strong>IBAN:</strong> $CheckoutAddress.IBAN_Hint</li>
				  <li class="text-center text-md-left"><strong>BIC:</strong> $CheckoutAddress.BIC_Hint</li>
				  <% end_if %>
                </ul>
          </div>
		 <% else %>
		 		  <div class="cz-sidebar-static rounded-lg box-shadow-lg ml-lg-auto mt-3">
            <h2 class="h6 mb-4 text-center text-md-left"><i class="h6 text-body czi-euro-circle"></i>Leerer Warenkorb</h4>
                
                <p>Es befinden sich keine Produkte in Deinem Warenkorb</p>
          </div>
		 <% end_if %>
		 </aside>
	</div>
	<!-- Navigation (mobile)-->
      <div class="row d-lg-none">
        <div class="col-12">
          <div class="d-flex pt-4 mt-3">
            <div class="w-100 "><button type="submit" class="bestellenBtn btn btn-primary btn-block" link="$LinkCheckoutFinal"><span class="d-none d-sm-inline">Verbindlich bestellen</span><span class="d-inline d-sm-none">Verbindlich bestellen</span><i class="czi-arrow-right mt-sm-0 ml-1"></i></button></div>
          </div>
        </div>
      </div>
      <div class="row d-lg-none">
        <div class="col-12">
          <div class="d-flex pt-0 mt-3">
            <div class="w-100 "><a class="btn btn-secondary btn-block" href="$LinkCheckoutDelivery"><i class="czi-arrow-left mt-sm-0 mr-1"></i><span class="d-none d-sm-inline">Zurück zu den Lieferoptionen</span><span class="d-inline d-sm-none">Zurück</span></a></div>
        </div>
      </div>
    </div>
</div>
  </form>
  <script>
  var order="$LinkCheckoutFinal";
  var link="$Link";




function checkoutSummary(nextLink,pageLink){
	jQuery('.bestellenBtn').attr('disabled',true);
	jQuery('.bestellenBtn').prepend('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>');
	var nextLink='$LinkCheckoutFinal';
	var pageLink='$Link';
	jQuery.ajax({
		url: pageLink+"/makeOrder",
		success: function(data) {
		window.location=nextLink; 
		console.log("checkoutSummary="+data);
		if(parseInt(data)>0){
			//$('#warenkorb_icon').html(data+'<span>Warenkorb</span>');
			}
		}
	});
}
</script>
