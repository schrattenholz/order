 <% include PageTitleOverlap %>
    <!-- Page Content-->
	<div class="container pb-5 mb-2 mb-md-4">
	<form id="checkoutAddress" class="needs-validation" novalidate>
		<div class="row">
			<section id="content" class="col-lg-8">
				<% include Schrattenholz/Order/Includes/CheckoutSteps %>
				<% if $CurrentMember %>
				<div class="d-sm-flex justify-content-between align-items-center bg-secondary p-4 rounded-lg mb-grid-gutter">
					<div class="media align-items-center">
						<div class="img-thumbnail rounded-circle position-relative" style="width: 6.375rem;">
							<span class="badge badge-warning" data-toggle="tooltip" title="" data-original-title="Reward points">$ClientOrders.Count</span>
							<img class="rounded-circle" src="img/shop/account/avatar.jpg" alt="$CurrentMember.FirstName $CurrentMember.Lastname">
						</div>
						<div class="media-body pl-3">
							<h3 class="font-size-base mb-0">$CurrentMember.FirstName $CurrentMember.Surname</h3><span class="text-accent font-size-sm">$CurrentMember.Email</span>
						</div>
					</div>
					<a class="btn btn-light btn-sm btn-shadow mt-3 mt-sm-0" href="{$OrderConfig.AcountRoot.Link}profil"><i class="czi-edit mr-2"></i>Benutzerdaten bearbeiten</a>
				</div>
				 <% end_if %>
				

						<% if $Basket.ProductContainers %>
							$RegistrationForm.Fields
						<% else %>
							<p>Es befinden sich momentan keine Produkte in Deinem Warenkorb!</p>
							<a href="$LinkProductRoot">Zur Produkt-&Uuml;bersicht</a>
						<% end_if %>

				
					<div class="span12" id="message"></div>
				
				  <!-- Navigation (desktop)-->
					<div class="d-none d-lg-flex pt-4 mt-3">
						<div class="w-50 pr-3">
							<a href="$OrderConfig.Basket.Link" name="action_back" value="Zurück zum Warenkorb" class="action action btn btn-secondary btn-shadow mb-2 mr-1 col-12" id="OrderProfileFeature_RegistrationForm_useraccounttab_action_back">
							<i class="czi-arrow-left mt-sm-0 mr-1"></i><span class="d-none d-sm-inline">Zurück zum Warenkorb</span><span class="d-inline d-sm-none">Zurück</span>
							</a>
						</div>
						<div class="w-50 pl-2">
							<button type="submit" name="action_continue" value="Weiter zu den Lieferoptionen" class="action action btn btn-primary btn-shadow mb-2 mr-1 col-12" id="OrderProfileFeature_RegistrationForm_useraccounttab_action_continue">
								<span class="d-none d-sm-inline">Weiter zu den Lieferoptionen</span><span class="d-inline d-sm-none">Weiter</span><i class="czi-arrow-right mt-sm-0 ml-1"></i></a>
							</button>
						</div>
					</div>
				
			</section>
	  <!-- Sidebar-->
			<aside class="col-lg-4 pt-4 pt-lg-0">
				<div class="cz-sidebar-static rounded-lg box-shadow-lg ml-lg-auto">
					<div class="form-group mb-4">
					  <label class="mb-3" for="order-comments"><span class="badge badge-info font-size-xs mr-2">Info</span><span class="font-weight-medium">Zusätzliche Anmerkungen</span></label>
					  <textarea id="OrderProfileFeature_RegistrationForm_useraccounttab_AdditionalNotes" name="AdditionalNotes" rows="7" cols="20" class="form-control">$Basket.AdditionalNotes</textarea>
					</div>
				</div>
			</aside>

			<!-- Navigation (mobile)-->

				<div class="col-12 d-lg-none">
				  <div class="d-flex pt-4 mt-3">
					<div class="w-50 pr-3"><a class="btn btn-secondary btn-block" href="$OrderConfig.Basket.Link"><i class="czi-arrow-left mt-sm-0 mr-1"></i><span class="d-none d-sm-inline">Zurück</span><span class="d-inline d-sm-none">Zurück</span></a></div>
					<div class="w-50 pl-2">
					<button type="submit" name="action_continue" value="Weiter zu den Lieferoptionen" class="action action btn btn-primary btn-shadow mb-2 mr-1 col-12" id="OrderProfileFeature_RegistrationForm_useraccounttab_action_continue_mobile">
					<span class="d-none d-sm-inline">Weiter</span><span class="d-inline d-sm-none">Weiter</span><i class="czi-arrow-right mt-sm-0 ml-1"></i></a>
							</button></div>
				  </div>
				</div>
		</div>
		<input type="hidden" id="CreateUserAccount_Val" name="CreateUserAccount_Val" />
		</form>
	</div>
	
<script>


function loginMember(){
	console.log("loginMember");
	var pageLink='$Link';
	jQuery.ajax({
	
		url: pageLink+"/loginMember?person="+encodeURIComponent(JSON.stringify(jQuery('#checkoutAddress').serializeObject())),
		success: function(data) {
		var response=JSON.parse(data);
		var status=response.Status;
		var message=response.Message;
		var object=response.Value;
		/*
		JSON
			$returnValues->Status=false;
			$returnValues->Message="Das Passwort muss mindestens 8 Zeiechen haben!";
			$returnValues->Value='object';
		*/
		 
		console.log("loginMember="+status);
			if(status=='error'){
				$('#toast_error').toast({
						autohide: true,
						delay:3000,
						animation:true
					});
					$('#toast_error .toast-header .content').html("Fehler");
					$('#toast_error .toast-body').html(message);
					$('#toast_error').toast('show');
			}else{
				/*
				if(status=='info' || status=='warning'){
					$('#toast_success').toast({
						autohide: true,
						delay:3000,
						animation:true
					});
					$('#toast_success .toast-header .content').html("OK");
					$('#toast_success .toast-body').html(message);
					$('#toast_success').toast('show');
				}
				*/	
				window.location=pageLink;
			}
		}
	});

}
function checkoutAddress(nextLink,pageLink){
	var nextLink='$LinkCheckoutDelivery';
	var pageLink='$Link';
if(jQuery('#CreateUserAccount').is(":checked")){
	jQuery('#CreateUserAccount_Val').val(1);
}else{
jQuery('#CreateUserAccount_Val').val(0);
}
	jQuery('input').each(function(){
		$(this).removeAttr('disabled');
	});
	jQuery.ajax({
	
		url: pageLink+"/setCheckoutAddress?person="+encodeURIComponent(JSON.stringify(jQuery('#checkoutAddress').serializeObject())),
		success: function(data) {
		var response=JSON.parse(data);
		var status=response.Status;
		var message=response.Message;
		var object=response.Value;
		/*
		JSON
			$returnValues->Status=error/info/warning/good;
			$returnValues->Message="Das Passwort muss mindestens 8 Zeiechen haben!";
			$returnValues->Value='object';
		*/
		 
		console.log("checkoutAddress="+data);
			if(status=='error'){
				$('#toast_error').toast({
						autohide: true,
						delay:3000,
						animation:true
					});
					$('#toast_error .toast-header .content').html("Fehler");
					$('#toast_error .toast-body').html(message);
					$('#toast_error').toast('show');
			}else if(status=='info'){
				$('#modal_info').modal({
						autohide: false,
						delay:3000,
						animation:true
					})
				$('#modal_info .modal-title').html("Wichtige Information!");
				$('#modal_info .modal-body').html(message);
				$('#modal_info .btn').html("Ok, fortfahren.");
				$('#modal_info').modal('show');
				$('#modal_info').on('hidden.bs.modal', function () {
				  window.location=nextLink;
				});
			}else{
				  window.location=nextLink;
			}
		}
	});
}
$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
	console.log("="+this.value);
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};
</script>