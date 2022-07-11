<!-- Steps-->
<div class="steps steps-light pt-2 pb-3 mb-5">
	  <% loop $CheckoutChain.Pages %>
	<a class="step-item <% if $Up.Current.ID==$ID %>active current<% end_if %> " >
		<div class="step-progress"><span class="step-count">$Pos</span></div>
		<div class="step-label">
		$Data.MenuTitle.XML</div>
	</a>
	  <% end_loop %>
</div>
