<?php
namespace PostaPlus\PostaPlusShipping\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use \Psr\Log\LoggerInterface as Logger; //log injection
use PostaPlus\PostaPlusShipping\Model\Carrier\PostaPlusShipping as PPS;

class AutoCreateShipment implements ObserverInterface
{
	protected $_logger;
	protected $_PPS;
	
	public function __construct(
			Logger $logger,
			PPS $PPS,
			array $data = []
			)
	{
		
		//parent::__construct();
		
		$this->_logger = $logger;
		$this->_PPS = $PPS;
		//Observer initialization code...
		
		//You can use dependency injection to get any class this observer may need.
	}

	public function execute(EventObserver $observer)
	{
		//Observer execution code...
		$event = $observer->getEvent();
		
		
		$shipment = $observer->getEvent()->getShipment();
		$order = $shipment->getOrder();
		
		//$this->_logger->debug("Order Details:".print_r($order,true));
		$this->_PPS->test();
		
		
	}
}
?>