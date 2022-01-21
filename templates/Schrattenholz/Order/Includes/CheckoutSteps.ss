<!-- Steps-->
<div class="steps steps-light pt-2 pb-3 mb-5">
	<a class="step-item <% if $ID==$OrderConfig.CheckoutSummary.ID || $ID==$OrderConfig.CheckoutFinal.ID || $ID==$OrderConfig.CheckoutAddress.ID %>active<% end_if %> <% if $ID==$OrderConfig.Basket.ID %>active current<% end_if %>" >
		<div class="step-progress"><span class="step-count">1</span></div>
		<div class="step-label"><i class="czi-cart"></i>$OrderConfig.Basket.MenuTitle.XML</div>
	</a>
	<a class="step-item  <% if $ID==$OrderConfig.CheckoutSummary.ID || $ID==$OrderConfig.CheckoutFinal.ID %>active<% end_if %><% if $ID==$OrderConfig.CheckoutAddress.ID %>active current<% end_if %>" >
		<div class="step-progress"><span class="step-count">2</span></div>
		<div class="step-label"><i class="czi-user-circle"></i>$OrderConfig.CheckoutAddress.MenuTitle.XML</div>
	</a>
	<a class="step-item  <% if $ID==$OrderConfig.CheckoutSummary.ID || $ID==$OrderConfig.CheckoutFinal.ID %>active<% end_if %><% if $ID==$OrderConfig.CheckoutDelivery.ID %>active current<% end_if %>" >
		<div class="step-progress"><span class="step-count">3</span></div>
		<div class="step-label"><i class="czi-truck"></i>$OrderConfig.CheckoutDelivery.MenuTitle.XML</div>
	</a>
	<a class="step-item <% if $ID==$OrderConfig.CheckoutFinal.ID %>active<% end_if %> <% if $ID==$OrderConfig.CheckoutSummary.ID %>active current<% end_if %>" >
		<div class="step-progress"><span class="step-count">4</span></div>
		<div class="step-label"><i class="czi-package"></i>$OrderConfig.CheckoutSummary.MenuTitle.XML</div>
	</a>
	<a class="step-item <% if $ID==$OrderConfig.CheckoutFinal.ID %>active current<% end_if %>" >
		<div class="step-progress"><span class="step-count">5</span></div>
		<div class="step-label"><i class="czi-check-circle"></i>$OrderConfig.CheckoutFinal.MenuTitle.XML</div>
	</a>
</div>
