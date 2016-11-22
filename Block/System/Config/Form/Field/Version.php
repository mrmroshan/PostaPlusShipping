<?php
namespace PostaPlus\PostaPlusShipping\Block\System\Config\Form\Field;

use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * @category   PostaPlus
 * @package    PostaPlus_PostaPlusShipping
 * @author     mrmroshan@yahoo.com
 * @website    http://www.postaplus.com
 */
class Version extends \Magento\Config\Block\System\Config\Form\Field
{
    const EXTENSION_URL = 'http://www.postaplus.com';

    /**
     * @var \PostaPlus\PostaPlusShipping\Helper\Data $helper
     */
    protected $_helper;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \MagePsycho\Customshipping\Helper\Data $helper
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \PostaPlus\PostaPlusShipping\Helper\Data $helper
    ) {
        $this->_helper = $helper;
        parent::__construct($context);
    }


    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $extensionVersion   = $this->_helper->getExtensionVersion();
        $extensionTitle     = 'PostaPlus Shipping';
        $versionLabel       = sprintf(
            '<a href="%s" title="%s" target="_blank">%s</a>',
            self::EXTENSION_URL,
            $extensionTitle,
            $extensionVersion
        );
        $element->setValue($versionLabel);

        return $element->getValue();
    }
}