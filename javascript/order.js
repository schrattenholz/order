/*vendor\schrattenholz\order\javascript\order.js*/

jQuery( document ).ready(function() {

		jQuery('#profile_orders').find('.saveOrderAsModelButton').each(function(){
			jQuery(this).on('click',function(){
				$('#ClientOrderID').val($(this).attr('data-clientorderid'));
				$('#OrderName').val("Vorlage "+$(this).attr('data-clientorderid'));
				
				$('#orderModal').modal("toggle");
			});
		});

		jQuery('#orderModal').find('.saveModelBtn').each(function(){
			jQuery(this).on('click',function(){
				$('#orderModal').modal("toggle");
			});
		});

		var pageLink="$Link";
		jQuery('#signin-tab').submit(function (event) {
			//console.log("signin-tab");
				event.preventDefault();
			if (jQuery('#signin-tab')[0].checkValidity() === false) {
				event.stopPropagation();
			} else {
				jQuery('#signin-tab').addClass('was-validated');
				doLogin(jQuery('#signin-tab'));
			}
			
		});
		jQuery('#signup-tab').submit(function (event) {
				event.preventDefault();
				console.log("signup-tab");
			if(jQuery('#CreateUserAccountPassword').val()!=jQuery('#CreateUserAccountPasswordConfirm').val()){
				//alert("Die Passwörter stimmen nicht überein.");
				jQuery('#CreateUserAccountPasswordConfirmFeedback').attr('style','display:block;').html("Die Passwörter stimmen nicht überein.");
				console.log("Die Passwörter stimmen nicht überein.");
				event.stopPropagation();
			}else if (jQuery('#signup-tab')[0].checkValidity() === false) {
				jQuery('#CreateUserAccountPassword').find(".invalid-feedback").html("Das Passwort muss 6 bis 12 Zeichen haben und aus Zahlen sowie Klein/Großbuchstaben bestehen.")
				event.stopPropagation();
			} else {
				jQuery('#CreateUserAccountPasswordConfirmFeedback').attr('style','display:none;');
				jQuery('#signup-tab').addClass('was-validated');
				doRegistration(jQuery('#signup-tab'));
			}
			
		});
	if(jQuery('#Form_OrderProfileFeature_Profile_ProfileData_Form').length>0){
		jQuery('#Form_OrderProfileFeature_Profile_ProfileData_Form').on('submit',function (event) {

			if (jQuery('#Form_OrderProfileFeature_Profile_ProfileData_Form')[0].checkValidity() === false) {
			} else {
				saveProfileData();
				event.stopPropagation();
			}
			jQuery('#Form_OrderProfileFeature_Profile_ProfileData_Form').addClass('was-validated');
			return false;
		});
		jQuery('#Form_OrderProfileFeature_Profile_ProfileData_PasswordForm').submit(function (event) {

			savePassword();
			event.preventDefault();
			event.stopPropagation();

		});
	}

	if(jQuery('#Form_OrderProfileFeature_ProfilLoginForm').length>0){
		jQuery('#Form_OrderProfileFeature_ProfilLoginForm').submit(function (event) {
				event.preventDefault();
			if (jQuery('#Form_OrderProfileFeature_ProfilLoginForm')[0].checkValidity() === false) {
				event.stopPropagation();
			} else {
				doLogin(jQuery('#Form_OrderProfileFeature_ProfilLoginForm'))
			}
			jQuery('#Form_OrderProfileFeature_ProfilLoginForm').addClass('was-validated');
		});
	}

	if(jQuery('#checkoutAddress').length>0){

		$('#OrderProfileFeature_RegistrationForm_useraccounttab_CreateUserAccountPassword_Holder').css('display','none');
		$('#OrderProfileFeature_RegistrationForm_useraccounttab_CreateUserAccountPasswordConfirm_Holder').css('display','none');
		$('#OrderProfileFeature_RegistrationForm_useraccounttab_CreateUserAccountCustomerGroup_Holder').css('display','none');
		$('#OrderProfileFeature_RegistrationForm_useraccounttab_CreateUserAccountPasswordConfirm').removeAttr('required','required').attr('aria-required',false);
		$('#OrderProfileFeature_RegistrationForm_useraccounttab_CreateUserAccountPassword').removeAttr('required','required').attr('aria-required',false);
		$('#OrderProfileFeature_RegistrationForm_useraccounttab_CreateUserAccountCustomerGroup').find('input').each(function(){
				$(this).removeAttr('required','required');
		});
		$('#OrderProfileFeature_RegistrationForm_useraccounttab_CreateUserAccount').on('change', function()
		{
			if (this.checked)
			{
				
				$('#OrderProfileFeature_RegistrationForm_useraccounttab_CreateUserAccountPassword_Holder').css('display','block');
				$('#OrderProfileFeature_RegistrationForm_useraccounttab_CreateUserAccountPasswordConfirm_Holder').css('display','block');
				$('#OrderProfileFeature_RegistrationForm_useraccounttab_CreateUserAccountCustomerGroup_Holder').css('display','block');
				$('#OrderProfileFeature_RegistrationForm_useraccounttab_CreateUserAccountPasswordConfirm').attr('required','required').attr('aria-required',true);
				$('#OrderProfileFeature_RegistrationForm_useraccounttab_CreateUserAccountPassword').attr('required','required').attr('aria-required',true);
				$('#OrderProfileFeature_RegistrationForm_useraccounttab_CreateUserAccountCustomerGroup_Holder').find('input').each(function(){
					$(this).attr('required','required');
				});
			}else{
				$('#OrderProfileFeature_RegistrationForm_useraccounttab_CreateUserAccountPassword_Holder').css('display','none');
				$('#OrderProfileFeature_RegistrationForm_useraccounttab_CreateUserAccountPasswordConfirm_Holder').css('display','none');
				$('#OrderProfileFeature_RegistrationForm_useraccounttab_CreateUserAccountCustomerGroup_Holder').css('display','none');
				$('#OrderProfileFeature_RegistrationForm_useraccounttab_CreateUserAccountPasswordConfirm').removeAttr('required','required').attr('aria-required',false);
				$('#OrderProfileFeature_RegistrationForm_useraccounttab_CreateUserAccountPassword').removeAttr('required','required').attr('aria-required',false);
				$('#OrderProfileFeature_RegistrationForm_useraccounttab_CreateUserAccountCustomerGroup_Holder').find('input').each(function(){
					$(this).removeAttr('required','required');
				});
			}
		});
		jQuery('#checkoutAddress').submit(function (event) {
				event.preventDefault();
			if (jQuery('#checkoutAddress')[0].checkValidity() === false) {
				event.stopPropagation();
			} else {
				jQuery('#checkoutAddress').addClass('was-validated');
				checkoutAddress();
			}
			
		});
		jQuery('#loginMember').click(function (event) {
			loginMember();
			event.preventDefault();

		});
	}
if(jQuery('#checkoutSummary').length>0){
		jQuery('#checkoutSummary').submit(function (event) {
			console.log("checkoutSummary submit");
				event.preventDefault();
			if (jQuery('#checkoutSummary')[0].checkValidity() === false) {
				event.stopPropagation();
			} else {
				checkoutSummary()
			}
			jQuery('#checkoutSummary').addClass('was-validated');
		});
	}
	if(jQuery('#product').length>0){
		//refreshSelectedProduct();
	}
	jQuery('<div class="quantity-nav"><div class="quantity-button quantity-up d-flex align-items-center justify-content-center"><i class="czi-arrow-up d-block"><span class="sr-only">icon</span></i></div><div class="quantity-button quantity-down d-flex align-items-center justify-content-center"><i class="czi-arrow-down d-block"><span class="sr-only">icon</span></i></div></div>').insertAfter('.quantity input');
	jQuery('.quantity').each(function() {

		var spinner = jQuery(this),
			input = spinner.find('input[type="text"]'),
			btnUp = spinner.find('.quantity-up'),
			btnDown = spinner.find('.quantity-down'),
			min = input.attr('min'),
			max = input.attr('max');
			portionable=input.attr('data-portionable');
			regex="/[a-z]|[A-Z]|\s/g";
			multi=1;
		btnUp.click(function() {
			portionable=input.attr('data-portionable');
			var oldValue=input.val().replace(",",".");
			if(oldValue.search("kg")>-1){
				multi=1000;
			}else{
				multi=1;
			}
			oldValue = parseFloat(oldValue.replace (regex,""))*multi;
			var step=parseInt(input.attr('step'));
			var newVal=(oldValue + step);
			if (oldValue >=  input.attr('max')) {
				newVal = oldValue;
			}
			
			if(portionable==1){
				if(newVal>=1000){
					newVal=(newVal/1000).toFixed(2)+"kg";
				}else{
					newVal=newVal+"g";
				}
			}else{
				
			}
			spinner.find("input").val(newVal.toString().replace(".",","));
			spinner.find("input").trigger("change");
			calculatePrice();
		});

		btnDown.click(function() {
			portionable=input.attr('data-portionable');
			var oldValue=input.val().replace(",",".");
			if(oldValue.search("kg")>-1){
				multi=1000;
			}else{
				multi=1;
			}
			oldValue = parseFloat(oldValue.replace (regex,""))*multi;
			var step=parseInt(input.attr('step'));
			var newVal=(oldValue - step);
			if (oldValue <=  input.attr('min')) {
				newVal = oldValue;
			}
			if(portionable==1){
				if(newVal>=1000){
					newVal=(newVal/1000).toFixed(2)+"kg";
				}else{
					newVal=newVal+"g";
				}
			}
			spinner.find("input").val(newVal.toString().replace(".",","));
			spinner.find("input").trigger("change");
			calculatePrice();
		});

	});

	function doLogin(loginForm){
		jQuery.ajax({
			url: pageLink+"/loginMember?person="+encodeURIComponent(JSON.stringify(loginForm.serializeObject())),
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
					if(status=='info' || status=='warning'){
						$('#toast_success').toast({
						autohide: true,
						delay:7000,
						animation:true
					});
					$('#toast_success .toast-header .content').html("Login erfolgreich");
					$('#toast_success .toast-body').html("Sie sind nun angemedelt.");
					$('#toast_success').toast('show');
					}
					window.location=pageLink;
				}
			}
		});
	}
	function doRegistration(loginForm){
		
			jQuery("#registrationBtn .spinner-border").removeClass("d-none").addClass("d-innerblock");
			jQuery("#registrationBtn").attr("disabled","disabled");
		jQuery.ajax({
			url: pageLink+"/registerMember?person="+encodeURIComponent(JSON.stringify(loginForm.serializeObject()))+"&sec=13",
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
			$('#signin-modal').on('hidden.bs.modal', function (e) {
				$('#signin-modal').unbind('hidden.bs.modal');
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
					$('#toast_success').toast({
						autohide: true,
						delay:7000,
						animation:true
					});
					$('#toast_success .toast-header .content').html("Registrierung erfolgreich");
					$('#toast_success .toast-body').html("Wir haben Ihnen eine E-Mail gesendet, mit der Sie die Registrierung abschliessen können.");
					$('#toast_success').toast('show');
					//window.location=pageLink;
				}
			});
			jQuery("#registrationBtn .spinner-border").removeClass("d-innerblock").addClass("d-none");
			jQuery("#registrationBtn").removeAttr("disabled");
			$('#signin-modal').modal('hide');

				
			}
		});
	}
	//SELECTFIELD


	var x, i, j, selElmnt, a, b, c;
	/* Look for any elements with the class "custom-select": */
	x = document.getElementsByClassName("custom-select");
	for (i = 0; i < x.length; i++) {
	  selElmnt = x[i].getElementsByTagName("select")[0];
	  /* For each element, create a new DIV that will act as the selected item: */
	  a = document.createElement("DIV");
	  a.setAttribute("class", "select-selected");
	  a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
	  x[i].appendChild(a);
	  /* For each element, create a new DIV that will contain the option list: */
	  b = document.createElement("DIV");
	  b.setAttribute("class", "select-items select-hide");
	  for (j = 1; j < selElmnt.length; j++) {
		/* For each option in the original select element,
		create a new DIV that will act as an option item: */
		c = document.createElement("DIV");
		c.innerHTML = selElmnt.options[j].innerHTML;
		c.addEventListener("click", function(e) {
			/* When an item is clicked, update the original select box,
			and the selected item: */
			var y, i, k, s, h;
			s = this.parentNode.parentNode.getElementsByTagName("select")[0];
			h = this.parentNode.previousSibling;
			for (i = 0; i < s.length; i++) {
			  if (s.options[i].innerHTML == this.innerHTML) {
				s.selectedIndex = i;
				h.innerHTML = this.innerHTML;
				y = this.parentNode.getElementsByClassName("same-as-selected");
				for (k = 0; k < y.length; k++) {
				  y[k].removeAttribute("class");
				}
				this.setAttribute("class", "same-as-selected");
				break;
			  }
			}
			h.click();
		});
		b.appendChild(c);
	  }
	  x[i].appendChild(b);
	  a.addEventListener("click", function(e) {
		/* When the select box is clicked, close any other select boxes,
		and open/close the current select box: */
		e.stopPropagation();
		closeAllSelect(this);
		this.nextSibling.classList.toggle("select-hide");
		this.classList.toggle("select-arrow-active");
	  });
	}

	function closeAllSelect(elmnt) {
	  /* A function that will close all select boxes in the document,
	  except the current select box: */
	  var x, y, i, arrNo = [];
	  x = document.getElementsByClassName("select-items");
	  y = document.getElementsByClassName("select-selected");
	  for (i = 0; i < y.length; i++) {
		if (elmnt == y[i]) {
		  arrNo.push(i)
		} else {
		  y[i].classList.remove("select-arrow-active");
		}
	  }
	  for (i = 0; i < x.length; i++) {
		if (arrNo.indexOf(i)) {
		  x[i].classList.add("select-hide");
		}
	  }
	}

	/* If the user clicks anywhere outside the select box,
	then close all select boxes: */
	document.addEventListener("click", closeAllSelect);
});

