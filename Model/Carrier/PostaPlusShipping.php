<?php

namespace PostaPlus\PostaPlusShipping\Model\Carrier;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Config;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\Method;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Psr\Log\LoggerInterface;
use Magento\Framework\HTTP\ZendClientFactory as HTTPClientFactory;

/**
 * @category   PostaPlus
 * @package    PostaPlus_PostaPlusShipping
 * @author     mrmroshan@yahoo.com
 * @website    http://www.postaplus.com
 */
class PostaPlusShipping extends AbstractCarrier implements CarrierInterface
{
    /**
     * Carrier's code
     *
     * @var string
     */
    protected $_code = 'pppostaplusshipping';
    
    /**
     * Code debug
     *
     * @var string
     */
    protected $debug = true;
    
    /**
     * HTTP Client Factory Obj
     *
     * @var string
     */
    protected $_httpClient = null;

    /**
     * Whether this carrier has fixed rates calculation
     *
     * @var bool
     */
    protected $_isFixed = true;

    /**
     * @var ResultFactory
     */
    protected $_rateResultFactory;

    /**
     * @var MethodFactory
     */
    protected $_rateMethodFactory;
    
    protected $_log;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory $rateErrorFactory
     * @param LoggerInterface $logger
     * @param ResultFactory $rateResultFactory
     * @param MethodFactory $rateMethodFactory
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        ResultFactory $rateResultFactory,
        MethodFactory $rateMethodFactory,
    	HTTPClientFactory $httpClientFactory,
        array $data = []
    ) {
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
        
        $this->_log = $logger;
        $this->_httpClient = $httpClientFactory;

        //var_dump($this->observer->getEvent()->getOrder());

        //var_dump($this->getAllowedMethods());exit;

    }

    /**
     * Generates list of allowed carrier`s shipping methods
     * Displays on cart price rules page
     *
     * @return array
     * @api
     */
    public function getAllowedMethods()
    {
        return [$this->getCarrierCode() => __($this->getConfigData('name'))];
    }

    /**
     * Collect and get rates for storefront
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @param RateRequest $request
     * @return DataObject|bool|null
     * @api
     */
    public function collectRates(RateRequest $request)
    {
        /**
         * Make sure that Shipping method is enabled
         */
        if (!$this->isActive()) {
            return false;
        }

        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->_rateResultFactory->create();

        $shippingPrice = $this->getConfigData('price');

        $method = $this->_rateMethodFactory->create();

        /**
         * Set carrier's method data
         */
        $method->setCarrier($this->getCarrierCode());
        $method->setCarrierTitle($this->getConfigData('title'));

        /**
         * Displayed as shipping method under Carrier
         */
        $method->setMethod($this->getCarrierCode());
        $method->setMethodTitle($this->getConfigData('name'));

        $method->setPrice($shippingPrice);
        $method->setCost($shippingPrice);

        $result->append($method);


        return $result;
    }
    
    public function test(){
    	
    	
    	$this->_logger->debug("step 1:");
    	$stock = "IBM";
    	//$wsdl = "http://etrack.postaplus.net/APIService/PostaWebClient.svc?wsdl" ;
    	$wsdl = 'http://172.53.1.34:8095/APIService/PostaWebClient.svc?wsdl';
    	
    	//"http://www.restfulwebservices.net/wcf/StockQuoteService.svc?wsdl";
    	
    	$client = new  \Zend\Soap\Client($wsdl,
    						array(
    								'soap_version' => SOAP_1_1
    						));
    			
    	$this->_logger->debug("step 2:");
    	
    	
    	$stock = "IBM";
    	$parameters =array('SHIPINFO'=>array(
    			'AppointmentDate'=>'2016-11-11',
    			'AppointmentFromTime'=>'00:00',
    			'AppointmentToTime'=> '00:00',
    			'CashOnDelivery' => 100,
    			'CashOnDeliveryCurrency'=>'CUR6',
    			'ClientInfo'=>array(
    					'CodeStation'=>'KWI',
    					'Password'=>'shr',
    					'ShipperAccount'=>'Test7474',
    					'UserName'=>'shareefsh'    					
    			),
    			'CodeCurrency'=>'KWD',
    			'CodeService'=>'SRV6',
    			'CodeShippmentType'=>'SHPT1',
    			'ConnoteContact'=>array(
    					'Email1'=>'test@mail.com',
    					'Email2'=>'test@mail.com',
    					'TelHome'=>'123456',
    					'TelMobile'=>'12345',
    					'WhatsAppNumber'=>'1233'
    			),
    			'ConnoteDescription'=>'This is a test enty by roshan done via API from Magento',
    			'ConnoteInsured'=>'Y',
    			'ConnoteNotes'=>array(
    					'Note1'=>'test1',
    					'Note2'=>'test1',
    					'Note3'=>'test1',
    					'Note4'=>'test1',
    					'Note5'=>'test1',
    					'Note6'=>'test1'    					
    			),
    			'ConnotePerformaInvoice'=>array(
    					'CONNOTEPERMINV'=>array(
    							'CodeHS'=>'TEST',
    							'CodePackageType'=>'TEST',
    							'Description'=>'TEST',
    							'OrginCountry'=>'KWI',
    							'Quantity'=>'1',
    							'RateUnit'=>'1'    			
    					)
    			),
    			'ConnotePieces'=>'1',
    			'ConnoteProhibited'=>'N',
    			'ConnoteRef'=>array(
    					'Reference1'=>'test1',
    					'Reference2'=>'test1'
    			),
    			'Consignee'=>array(
    					'Company'=>'home',
    					'FromAddress'=>'kuwait city',
    					'FromArea'=>'AREA75',
    					'FromCity'=>'CITY96303',
    					'FromCodeCountry'=>'KWT',
    					'FromMobile'=>'12345678',
    					'FromName'=>'Roshan2',
    					'FromPinCode'=>'1234',
    					'FromProvince'=>'KW',
    					'FromTelphone'=>'123',
    					'Remarks'=>'test entry by roshan via Magento',
    					'ToAddress'=>'Kuwait',
    					'ToArea'=>'AREA71',
    					'ToCity'=>'CITY24051',
    					'ToCivilID'=>'123456',
    					'ToCodeCountry'=>'KWT',
    					'ToCodeSector'=>'',
    					'ToDesignation'=>'',
    					'ToMobile'=>'123456',
    					'ToName'=>'Roshan Magento',
    					'ToPinCode'=>'123',
    					'ToProvince'=>'KW',
    					'ToTelPhone'=>'123456'  					
    			),
    			'CostShipment'=>'1234',
    			'ItemDetails'=> array(
    					'ITEMDETAILS'=>array(
    							'ConnoteHeight'=>'10',
    							'ConnoteLength'=>'10',
    							'ConnoteWeight'=>'10',
    							'ConnoteWidth'=>'10',
    							'ScaleWeight'=>'10'
    					)
    			),
    			'NeedPickUp'=>'N',
    			'NeedRoundTrip'=>'N',
    			'PayMode'=>'cash'    			
    	));
    	$value = $client->Shipment_Creation($parameters);
    	
    	$type  = $client->getLastResponse();
    	
    	$this->_logger->debug("get type:".print_r($type,true));
    	$this->_logger->debug("Last Request:".print_r($type,true));
    	
    	//$this->_logger->debug("Last Request:".htmlspecialchars($client->__getLastRequest()));
    	//$this->_logger->debug("Last Responce:".htmlspecialchars($client->__getLastResponse()));
    	
    	exit;
    	
    	/*
    	
    	$request = array("request"=>$stock);
    	$this->_logger->debug("step 2:");
    	
    	$client = $this->_httpClient->create();
    	$this->_logger->debug("step 3:");
    	
    	$client->setUri((string)$wsdl);
    	$this->_logger->debug("step 4:");
    	
    	$client->setConfig(['maxredirects' => 0, 'timeout' => 30]);
    	$this->_logger->debug("step 5:");
    	
    	//$parameters =array("request"=>$stock);
    	
    	$result = $client->GetStockQuote($stock);
    	$this->_logger->debug("step 6:result:".$request);   	
    	
    	exit;
    	
    	//$response = $client->request();
    	$value = $client->GetStockQuote($parameters);
    	 
    	$this->_logger->debug("step 7:value:".$value);
    	$responseBody = $response->getBody();
    	$this->_logger->debug("step 8:");
    	
    	//$client->setRawData(utf8_encode($request));
    	$this->_logger->debug("step 9 OUTPUT:".print_r($responseBody,true));
    	*/
    	
    	/*
    	 * 
    	 $xmlStr = '<?xml version="1.0" encoding="UTF-8"?>' .
            '<req:ShipmentValidateRequest' .
            $originRegion .
            ' xmlns:req="http://www.dhl.com"' .
            ' xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' .
            ' xsi:schemaLocation="http://www.dhl.com ship-val-req' .
            ($originRegion ? '_' .
                $originRegion : '') .
            '.xsd" />';
        $xml = $this->_xmlElFactory->create(['data' => $xmlStr]);
        $nodeRequest = $xml->addChild('Request', '', '');
        $nodeServiceHeader = $nodeRequest->addChild('ServiceHeader');
        $nodeServiceHeader->addChild('SiteID', (string)$this->getConfigData('id'));
        $nodeServiceHeader->addChild('Password', (string)$this->getConfigData('password'));
        
        $request = $xml->asXML();
        if (!$request && !mb_detect_encoding($request) == 'UTF-8') {
            $request = utf8_encode($request);
        }
        
         
    	 * */
    	
    	
    	//$response =  $client->request(\Zend_Http_Client::POST)->getBody();
    	//$this->_logger->debug("step 10:");
    	
    	 
    	
    	/*
    	$client = new SoapClient($wsdl,
    			array(
    					"trace"=>1,
    					"exceptions"=>0	));
    	
    			$stock = "IBM";
    			$parameters =array("request"=>$stock);
    			$value = $client->GetStockQuote($parameters);
    	
     	$this->_logger->debug("Last Request:".htmlspecialchars($client->__getLastRequest()));
    	$this->_logger->debug("Last Responce:".htmlspecialchars($client->__getLastResponse()));
    	*/
    }//end of function 
    
    protected function _getQuotesFromServer($request)
    {
    	
    }

}