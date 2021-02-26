  <section id="content" class="home clearfix">
<form id="checkoutAddress" class="was-validated">
   <div class="container">
   <div class="row">
	   <div class="span12">
		   $Content
		  
	   </div>
   </div>

        <div class="row">
		
		  <div class="col-md-6">
		
		 <h3>Kontaktdaten:</h3>
				<div class="clearfix"></div>
				 <div class="form-group">
					<label>Firmenname</label>
					<input type="text" class="form-control" name="Firmenname" value="$CheckoutAdress.Firmenname"/>					
				</div>
				 <div class="form-group">
					<label>Strasse/Nr*</label>
					<input  type="text" class="form-control" id="Strasse" name="Strasse" value="$CheckoutAdress.Strasse" required/>
				</div>
				 <div class="form-group">
					<label>PLZ*</label>
					<input type="text" class="form-control" id="PLZ"  name="PLZ" value="$CheckoutAdress.PLZ"  required/>
				</div>
				 <div class="form-group">
					<label>Ort*</label>
					<input type="text" class="form-control" id="Ort" name="Ort" value="$CheckoutAdress.Ort"   required/>
				</div>
				<div class="clearfix"></div>
				<h3 class="clearfix">Ansprechpartner</h3>
				 <div class="form-group row">
				
				<label class="custom-control-label col-6" for="01"><input type="radio" id="01" class="form-check-input" name="Anrede" required value="Frau" <% if $CheckoutAdress.Anrede=="Frau" %>checked<% end_if %>> Frau</label>
				
				<label class="custom-control-label col-6" for="02"><input type="radio" id="02" class="form-check-input" name="Anrede" required value="Herr" <% if $CheckoutAdress.Anrede=="Herr" %>checked<% end_if %>> Herr</label>
				</div>
				 <div class="form-group">
					<label>Nachname*</label>
					<input class="feld form-control"  type="text" id="Nachname" name="Nachname" value="$CheckoutAdress.Nachname" required />
				</div>
				 <div class="form-group">
					<label>Vorname*</label>
					<input class="feld last form-control" type="text" id="Vorname" name="Vorname" value="$CheckoutAdress.Vorname" required/>
				</div>
				 <div class="form-group">
					<label>Telefon*</label>
					<input class="feld form-control"  type="text" id="Telefon" name="Telefon" value="$CheckoutAdress.Telefon" required />
				</div>
				 <div class="form-group">
					<label>Email*</label>
					<input class="feld last form-control" type="text" id="Email" name="Email" value="$CheckoutAdress.Email" required />
				</div>
				
          </div>
		  
          <div class="col-md-6">
		   <h3 class="clearfix">Zus&auml;tzliche Anmerkungen</h3>
		   <br>
				<div class="feld">
					<textarea id="Anmerkungen"  class="form-control" name="Anmerkungen" cols="50" rows="4">$CheckoutAdress.Anmerkungen</textarea>
				</div>
          </div>
		  
        </div>
		<div class="row">
		<div class="span12" id="message">
			
		</div>
   </div>
  
		<div class="row">
			<div class="col-12">
				<button class="btn"  type="submit">Weiter zur Bestellübersicht</button>
				&nbsp;&nbsp; 
				<a class="btn" href="$LinkBasket">Zur&uuml;ck zum Warenkorb</a>
			</div>
		</div>
		</div>  
    </form>
  </section>
<script>



function checkoutAddress(nextLink,pageLink){
	var nextLink='$LinkCheckoutSummary';
	var pageLink='$Link';
	jQuery.ajax({
		url: pageLink+"/setCheckoutAddress?person="+JSON.stringify(jQuery('#checkoutAddress').serializeObject()),
		success: function(data) {
		window.location=nextLink; 
		console.log("checkoutAddress="+data);
		if(parseInt(data)>0){
			//$('#warenkorb_icon').html(data+'<span>Warenkorb</span>');
			}
		}
	});
}
</script>