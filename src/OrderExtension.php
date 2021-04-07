<?php 	

namespace Schrattenholz\Order;

use Silverstripe\ORM\DataExtension;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Security\IdentityStore;
use SilverStripe\Control\Email\Email;
use Schrattenholz\Order\Backend;
use SilverStripe\ORM\ValidationException;
use Psr\Log\LoggerInterface;
use SilverStripe\View\Requirements;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Security\Security;
class OrderExtension extends DataExtension {
	private static $allowed_actions = array (
		'ClearBasket',
		'addToList',
		'getListCount',
		'getBasket',
		'calcPreis',
		'removeProductFromBasket',
		'getSingleProduct',
		'getLinkCheckoutAddress',
		'setCheckoutAddress',
		'getCheckoutAddress',
		'makeOrder',
		'getLocations',
		'checkIfProductInBasket',
		'getWarenkorbData',
		'loadSelectedParameters',
		'logoutInactiveUser',
		'logoutUser',
		'formattedNumber',
		'formattedWeight',
		'OrderConfig',
		'getAllProducts'
	);
	public function test(){
		return "test";
	}
	 public function onAfterInit(){
		$vars = [
			"Link"=>$this->getOwner()->Link(),
			"ID"=>$this->owner->ID
		];
		Requirements::javascriptTemplate("schrattenholz/order:javascript/order.js",$vars);
		
	}
	public function getIncludedVAT($val){
		$netto=$this->getNetto($val);
		return $val-$netto;
	}
	public function getNetto($val){
		return round($val/100*$vat,2);
	}
	public function formattedNumber($val){
			return number_format(floatval($val), 2, ',', '.');
	}
	public function formattedWeight($val){
		if($val>=1000){
			return str_replace(".",",",round($val/1000,2)."kg");
		}else{
			return str_replace(".",",",$val."g");
		}
	}
	function init() {
		parent:: init();
		self::expandBasketLiveTime();
	}
	public function OrderConfig(){
		return OrderConfig::get()->First();
	}
	function logoutUser(HTTPRequest $request){
		$this->owner->ClearBasket();
		Injector::inst()->get(IdentityStore::class)->logOut();
		$this->owner->redirect($this->owner->Link());
	}
	public function getAllProducts(){
		return Product::get();
	}

	function logoutInactiveUser() {
		
		$inactivityLimit = 2; // in Minutes
		$inactivityLimit = $inactivityLimit * 60; // Converted to seconds
		$sessionStart = $this->getSession()->get('session_start_time');
		
		if (isset($sessionStart)){
		    $elapsed_time = time() - $this->getSession()->get('session_start_time');
			
		    if ($elapsed_time >= $inactivityLimit) {
		        /*$member = Member::currentUser();
				if($member) $member->logOut();*/
				$this->getSession()->clear('session_start_time');
				$this->owner->ClearBasket();
				$this->owner->ClearAddress();
				//Director::redirect(Director::baseURL() . 'Security/login');
		    }
		}
		$this->getSession()->set('session_start_time', time());
		return $this->getSession()->get('session_start_time');
	}
	function utf8_urldecode($str) {
		$str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($str));
		return html_entity_decode($str,null,'UTF-8');;
	} 
	public function getLocations(){	
		return Stadt::get();
	}
	public function setCheckoutAddress($data){
		$personDaten=json_decode($this->utf8_urldecode($data['person']),true);
		$ar=array();
		foreach($personDaten as $key => $value){
			 array_push($ar, $key."=".$value);
		}
		
		$this->getSession()->set('personendaten', implode('+++',$ar));
		return $this->getSession()->get('personendaten');
	}
	public function getCheckoutAddress(){
		$personenDaten=$this->getSession()->get('personendaten');
		if($personenDaten){
		$tmpAr=explode('+++',$personenDaten);		
		$arForDataList=array();	
		foreach($tmpAr as $element){
			$tmp=explode('=',$element);
			
			$arForDataList[$tmp[0]]=$tmp[1];
			
		}
		return new ArrayData($arForDataList);
		}else{return false;}		
	}
	public function getLinkAGB(){
		$orderConfig=OrderConfig::get()->First();
		$agb=SiteTree::get()->where('ID='.$orderConfig->AGBID)->First();
		return $agb->Link();	
	}
	public function getLinkProductRoot(){
		$orderConfig=OrderConfig::get()->First();
		$productRoot=SiteTree::get()->where('ID='.$orderConfig->ProductRootID)->First();
		return $productRoot->Link();	
	}
	public function getLinkCheckoutAddress(){
		$orderConfig=OrderConfig::get()->First();
		$CheckoutAddress=SiteTree::get()->where('ID='.$orderConfig->CheckoutAddressID)->First();
		return $CheckoutAddress->Link();	
	}
	public function getLinkBasket(){
		$orderConfig=OrderConfig::get()->First();
		$basket=SiteTree::get()->where('ID='.$orderConfig->BasketID)->First();
		return $basket->Link();	
	}
	public function getLinkCheckoutSummary(){
		$orderConfig=OrderConfig::get()->First();
		$checkoutSummary=SiteTree::get()->where('ID='.$orderConfig->CheckoutSummaryID)->First();
		return $checkoutSummary->Link();	
	}
	public function getLinkCheckoutFinal(){
		$orderConfig=OrderConfig::get()->First();
		$checkoutFinal=SiteTree::get()->where('ID='.$orderConfig->CheckoutFinalID)->First();
		return $checkoutFinal->Link();	
	}
	public function getWarenkorbData(){
		$request = Injector::inst()->get(HTTPRequest::class);
		$session = $request->getSession();
		return $session->get('warenkorb');
	}
	public function getAllSession(){
		$request = Injector::inst()->get(HTTPRequest::class);
		$session = $request->getSession()->getAll();
	}
	function getSession(){
		$request = Injector::inst()->get(HTTPRequest::class);
		$session = $request->getSession();
		return $session;
	}
	public function setWarenkorbData($data){
		$this->getSession()->set('warenkorb', implode('|||',$data));
		$this->getSession()->set('warenkorb_start_time', time());
		
	}
	public function clearSession($value){
		$this->getSession()->set($value, '');
		$this->getSession()->clear($value);
		$this->getSession()->clear('warenkorb_start_time');
	}

	public function getSingleProduct(){
		foreach(explode('|||',$this->getWarenkorbData()) as $product){
			$tmpAr=explode('+++',$product);
			// Alle Produkte ausser dem zu löschenden Produkt werden im neuen Warenkorb gespeichert
			$variantID=$this->getOwner()->Preise()->First()->ID;
			if($tmpAr[0]==$this->getOwner()->ID && $tmpAr[2]==$variantID && $tmpAr[3]==$vac){
				$aktProduct=$tmpAr;
				return new ArrayData(array("Quantity"=>$aktProduct[1],"Variant01"=>$aktProduct[2],"Vac"=>$aktProduct[3]));
			}
		}
		return false;
	}
	public function checkIfProductInBasket($data){
		$productID=$data['id'];
		$variant01=$data['variant01'];
		$vac=$data['vac'];
		//return "ses=".$this->getWarenkorbData();
		foreach(explode('|||',$this->getWarenkorbData()) as $product){
			$tmpAr=explode('+++',$product);
			//return $vac." - ".$tmpAr[3];
			// Alle Produkte ausser dem zu löschenden Produkt werden im neuen Warenkorb gespeichert
			if($tmpAr[0]==$productID && $tmpAr[2]==$variant01 && $tmpAr[3]==$vac){
				return $tmpAr[1];
			}
		}
		return 0;
	}
	//Produkt aus dem Warenkorb entfernen
	public function removeProductFromBasket($data){
		$newBasket=array();
		$id=$data['id'];
		$variant01=$data['variant01'];
		$vac=$data['vac'];
		$tmp="|||".$id."-".$variant01."-".$vac."|||";
		foreach(explode('|||',$this->getWarenkorbData()) as $product){
			$tmpAr=explode('+++',$product);		
			$tmp.=$tmpAr[0]." - ".$tmpAr[2]." - ".$tmpAr[3]." | ";
			// Alle Produkte ausser dem zu löschenden Produkt werden im neuen Warenkorb gespeichert
			//return "|||".$id."-".$variant01."-".$vac."|||"."|||".$tmpAr[0]."-".$tmpAr[2]."-".$tmpAr[3]."|||";
			if($tmpAr[0]==$id && $tmpAr[2]==$variant01 && $tmpAr[3]==$vac){
			}else{
				$tmp.='hinizu';
				array_push($newBasket,$product);
			}
		}
		//return $tmp;
		$this->setWarenkorbData($newBasket);
		return count($newBasket);
	
	}
	function calcPreis($productID,$quantity,$number){
		$preis=Preis::get()->where("TonneID=".$productID." AND MaxProductQuantity >=".$quantity." AND MinProductQuantity <=".$quantity)->First()->Price;
		return number_format ( ($preis*$quantity)*$number ,2 );
	}
	// Anzahl der Produkte 
	public function getListCount(){
		if($this->getWarenkorbData()){
			$count=count(explode('|||',$this->getWarenkorbData()));
		}else{
			$count=0;
		}
		return $count;
	}
	// clear basket
	public function ClearBasket(){
		//Injector::inst()->get(LoggerInterface::class)->error('ClearBasket------------------>');
		$vars=new ArrayData(array("Basket"=>$basket,"Order"=>$order));
		$this->owner->extend('OrderExtension_ClearBasket', $vars);
		$this->clearSession('warenkorb');
	}
	// clear address-data
	public function ClearAddress(){
		$this->clearSession('personendaten');
		
		//return Session::get('warenkorb');
	}
	
	
	// Produkt in den Warenkorb
	public function addToList($data){
		$error=false;
		$action=$data['action'];
		$productData=json_decode(utf8_encode($data['orderedProduct']),true);

		//Daten validieren
		if($productData['quantity']!=0){
			if($action=="new"){
				// Prüfen, ob das Produkt bereits in der Session abgelegt ist
				//if($this->noDouble($productData['id'])){
				if($this->noDouble($productData['id'],$productData['variant01'],$productData['vac'])){
					$list=explode('|||',$this->getWarenkorbData());
					$tmp=$productData['id']."+++".$productData['quantity']."+++".$productData['variant01']."+++".$productData['vac'];
					array_push($list,$tmp);
					$list=array_filter($list);
					$this->setWarenkorbData($list);
				}else{
				// Produkt bereits im Warenkorb
					return "0|double";
				}
			}else if($action=="edit"){
				$list=array();
				foreach(explode('|||',$this->getWarenkorbData()) as $product){
					$tmpAr=explode('+++',$product);
					// Abbrechen wenn ein Double gefunden wurde
					if($tmpAr[0]==$productData['id'] && $tmpAr[2]==$productData['variant01'] && $tmpAr[3]==$productData['vac']){
						$product=$productData['id']."+++".$productData['quantity']."+++".$productData['variant01']."+++".$productData['vac'];
						//return false;
					}
					array_push($list,$product);
				}
				$this->setWarenkorbData($list);
			}
			
			return "2|".count($list);
		}else{
			// Es fehlen Eingaben
			$error=true;
			return "0|validation";
		}
	}
	
	// Prüft ob das aktuelle Produkt bereits in der Session angelegt wurde
	function noDouble($productID,$variantID,$vac){	
		foreach(explode('|||',$this->getWarenkorbData()) as $product){
			$tmpAr=explode('+++',$product);
			// Abbrechen wenn ein Double gefunden wurde
			if($tmpAr[0]==$productID && $tmpAr[2]==$variantID && $tmpAr[3]==$vac){
				return false;
			}
		}
		// Kein Double gefunden
		return true;
	}
	
	public function getBasket(){
		$products=new ArrayList();
		$tmpAr=array();
		$totalPrice=0;
		if($this->getWarenkorbData()!="" || $this->getWarenkorbData()!=false){
		$session=explode('|||',$this->getWarenkorbData());

			foreach($session as $product){
				$productAr=explode('+++',$product);
				//$tmpAD=	DataList::create('Tonne')->where('Tonne'.$add.'.ID='.$productAr[0])->First();
				$product=Product::get()->byID($productAr[0]);
				$variant01=Preis::get()->byID($productAr[2]);
				$tmpAD= $product;
				//$tmpAD->TotalPrice=str_replace(".",",",$this->calcPreis($productAr[0],$productAr[1],$productAr[2]));
				$tmpAD->Quantity=$productAr[1];
				$tmpAD->Variant01=$variant01;
				$tmpAD->Vac=$productAr[3];

				$products->push($tmpAD);
				//$totalPrice=$totalPrice+$this->calcPreis($productAr[0],$productAr[1],$productAr[2]);			
			}
			//$totalTax=((number_format ((number_format ($totalPrice,2)*100/119),2)-number_format ($totalPrice,2))*-1);
			//return new ArrayData(array('Products'=>$products,'TotalPrice'=>str_replace(".",",",number_format($totalPrice,2)),'TotalPriceFloat'=>number_format($totalPrice,2),'TotalTax'=>str_replace(".",",",number_format($totalTax,2))));
			return new ArrayData(array('Products'=>$products));
			
		}else{
			return new ArrayData(array('Products'=>false));
		}
		
	}
	function makeOrder(){
		$email = Email::create()
		->setHTMLTemplate('Schrattenholz\\Order\\Layout\\ConfirmationClient') 
		->setData([
				'BaseHref' => $_SERVER['DOCUMENT_ROOT'],
				'Basket' => $this->getBasket(),
				'CheckoutAddress' => $this->getCheckoutAddress(),
				'OrderConfig'=>OrderConfig::get()->First()
		])
		->setFrom(OrderConfig::get()->First()->ResponseEmail)
		->setTo($this->getCheckoutAddress()->Email)
		->setSubject(utf8_encode("Bestellbestätigung Hof Lehnmühle"));
		$email->send();
			//$email = new Email("webseite@amp-bayern.com", "stein@amp-bayern.com", "Neue Tonnenreinigung-Bestellung", "");
			/*
			$email = new Email("webseite@amp-bayern.com", "fabian@schrattenholz.de", "Neue Tonnenreinigung-Bestellung", "");
			$email->setHTMLTemplate('Confirmation');
			$email->populateTemplate($this->getBasket());
			$email->populateTemplate(array(
				'BaseHref' => $_SERVER['DOCUMENT_ROOT'],
				'Basket' => $this->getBasket(),
				'CheckoutAddress' => $this->getCheckoutAddress(),
			));
			*/
			
		$email = Email::create()
		->setHTMLTemplate('Schrattenholz\\Order\\Layout\\Confirmation') 
		->setData([
			'BaseHref' => $_SERVER['DOCUMENT_ROOT'],
			'Basket' => $this->getBasket(),
			'CheckoutAddress' => $this->getCheckoutAddress(),
			'OrderConfig'=>OrderConfig::get()->First()
		])
		->setFrom(OrderConfig::get()->First()->OrderEmail)
		->setTo(OrderConfig::get()->First()->OrderEmail)
		->setSubject("Neue Bestellung");
		if($email->send()){
			//$this->ClearAddress();
			//$this->ClearBasket();
		}
		
		
	
	}
	
}
