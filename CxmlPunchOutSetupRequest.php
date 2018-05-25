<?php

/**
 * CxmlPunchOutSetupRequest class.
 *
 * @author Brian Newsham
 *
 * @property string $deploymentMode (production | test) defaults to production.
 * @property string $operation
 * @property string $UserAgent user agent that is sending the document.
 * @property string $BuyerCookie a unique string that identifies the buyer.
 * @property string $BrowserFormPost_URL
 */
class CxmlPunchOutSetupRequest extends CxmlDocument
{
    const DEPLOY_TEST       = 'test';
    const DEPLOY_PRODUCTION = 'production';

    const OPERATION_CREATE  = 'create';
    const OPERATION_EDIT    = 'edit';
    const OPERATION_INSPECT = 'inspect';
    const OPERATION_SOURCE  = 'source';

    /**
     * Magic getter to access 'interesting' elements and attributes within the request.
     *
     * @param string $key
     * @throws Exception
     */
    public function __get($key)
    {
        switch ($key) {
            case 'deploymentMode':
                $deploymentMode = $this->simple_xml_element->Request['deploymentMode'];
                return is_null($deploymentMode) ? self::DEPLOY_PRODUCTION : $deploymentMode->__toString();
                break;
            case 'operation':
                return $this->simple_xml_element->Request->PunchOutSetupRequest['operation'];
                break;
            case 'UserAgent':
                return $this->simple_xml_element->Header->Sender->UserAgent;
                break;
            case 'BuyerCookie':
                return $this->simple_xml_element->Request->PunchOutSetupRequest->BuyerCookie;
                break;
            case 'BrowserFormPost_URL':
                return $this->simple_xml_element->Request->PunchOutSetupRequest->BrowserFormPost->URL;
                break;
            default:
                throw new Exception("Unknown property '$key' in " . __CLASS__);
                break;
        }
    }

    /**
     * Magic setter to set 'interesting' elements and attributes within the request.
     *
     * @param string $key
     * @param mixed $value
     * @throws Exception
     */
    public function __set($key, $value)
    {
        switch ($key) {
            case 'deploymentMode':
                $this->simple_xml_element->Request['deploymentMode'] = $value;
                break;
            case 'operation':
                $this->simple_xml_element->Request->PunchOutSetupRequest['operation'] = $value;
                break;
            case 'UserAgent':
                $this->simple_xml_element->Header->Sender->UserAgent = $value;
                break;
            case 'BuyerCookie':
                $this->simple_xml_element->Request->PunchOutSetupRequest->BuyerCookie = $value;
                break;
            default:
                throw new Exception('Unknown property in ' . __CLASS__);
                break;
        }
    }

    /**
     * Add an Extrinsic element to the document.
     *
     * @param string $name Value of the name attribute.
     * @param mixed $value Content of the element.
     */
    public function addExtrinsic($name, $value)
    {
        $el = $this->simple_xml_element->Request->PunchOutSetupRequest;
        $extrinsic = $el->addChild('Extrinsic', $value);
        $extrinsic->addAttribute('name', $name);
    }

    /**
     * Set the From Credentials.
     *
     * @param string $identity Value of the Identity element (username).
     */
    public function setFromCredentials($identity, $domain = 'NetworkID')
    {
        $this->simple_xml_element->Header->From->Credential['domain'] = $domain;
        $this->simple_xml_element->Header->From->Credential->Identity = $identity;
    }

    /**
     * Set the To Credentials.
     *
     * @param string $identity Value of the Identity element (username).
     */
    public function setToCredentials($identity, $domain = 'NetworkID')
    {
        $this->simple_xml_element->Header->To->Credential['domain'] = $domain;
        $this->simple_xml_element->Header->To->Credential->Identity = $identity;
    }

    /**
     * Set the Sender Credentials.
     *
     * @param string $identity Value of the Identity element (username).
     * @param string $shared_secret Value of the SharedSecret elememnt (password).
     */
    public function setSenderCredentials($identity, $shared_secret, $domain = 'NetworkID')
    {
        $this->simple_xml_element->Header->Sender->Credential['domain'] = $domain;
        $this->simple_xml_element->Header->Sender->Credential->Identity = $identity;
        $this->simple_xml_element->Header->Sender->Credential->SharedSecret = $shared_secret;
    }

    /**
     * Set the Buyer Cookie.
     *
     * @param string $value Value of the BuyerCookie element.
     */
    public function setBuyerCookie($value)
    {
        $this->simple_xml_element->Request->PunchOutSetupRequest->BuyerCookie = $value;
    }

    /**
     * Set the BrowserFormPost URL. This is where the client will post back to this site.
     *
     * @param string $url
     */
    public function setBrowserFormPostUrl($url)
    {
        $this->simple_xml_element->Request->PunchOutSetupRequest->BrowserFormPost->URL = $url;
    }

    /**
     * Get the ShipTo element if it exists.
     *
     * @return CxmlAddress|null
     */
    public function getShipTo()
    {
        $nodes = $this->simple_xml_element->xpath('//PunchOutSetupRequest/ShipTo');
        return count($nodes) ? CxmlAddress::factory($nodes[0]) : null;
    }

    /**
     * Create and populate the ShipTo element.
     *
     * @param CxmlAddress $ship_to
     */
    public function setShipTo($ship_to)
    {
        $el = $this->simple_xml_element->Request->PunchOutSetupRequest;
        $ShipTo = $el->addChild('ShipTo');
        $Address = $ShipTo->addChild('Address');
        if (isset($ship_to->addressID)) {
            $Address['addressID'] = $ship_to->addressID;
        }
        if (isset($ship_to->Name)) {
            $Name = $Address->addChild('Name', $ship_to->Name);
            $Name['xml:lang'] = 'en';
        }
        $PostalAddress = $Address->addChild('PostalAddress');
        if (isset($ship_to->name)) {
            $PostalAddress['name'] = $ship_to->name;
        }
        $PostalAddress->addChild('DeliverTo', $ship_to->DeliverTo);
        $PostalAddress->addChild('Street', $ship_to->Street);
        $PostalAddress->addChild('City', $ship_to->City);
        $PostalAddress->addChild('State', $ship_to->State);
        $PostalAddress->addChild('PostalCode', $ship_to->PostalCode);
        $country = $PostalAddress->addChild('Country', $ship_to->Country);
        $country['isoCountryCode'] = $ship_to->isoCountryCode;
    }

    /**
     * Set the SelectedItem element.
     *
     * @param string $supplier_part_id
     * @param string $supplier_part_auxiliary_id
     */
    public function setSelectedItem($supplier_part_id, $supplier_part_auxiliary_id = null)
    {
        $el = $this->simple_xml_element->Request->PunchOutSetupRequest;
        $selected_item = $el->addChild('SelectedItem');
        $item_id = $selected_item->addChild('ItemID');
        $item_id->addChild('SupplierPartID', $supplier_part_id);
        $item_id->addChild('SupplierPartAuxiliaryID', $supplier_part_auxiliary_id);
    }

    /**
     * Set the deploymentMode attribute on the Request element.
     *
     * @param string $value Deployment mode value.
     */
    public function setDeploymentMode($value)
    {
        $this->simple_xml_element->Request['deploymentMode'] = $value;
    }

    /**
     * Set the operation attribute on the PunchOutSetupRequest element.
     *
     * @param string $value Operation type.
     */
    public function setOperation($value)
    {
        $this->simple_xml_element->Request->PunchOutSetupRequest['operation'] = $value;
    }

    /**
     * ItemOut iterator get method
     *
     * @return array
     */
    public function getItemIterator()
    {
        $item_outs = $this->simple_xml_element->xpath('//ItemOut');
        $result = array();
        foreach ($item_outs as $item_out) {
            $result[] = CxmlItemOut::factory($item_out);
        }
        return $result;
    }
}
