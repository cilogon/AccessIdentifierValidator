<?php

class AccessIdentifierValidator extends AppModel {
  // Required by COmanage Plugins
  public $cmPluginType = "identifiervalidator";
  
  public $cmPluginInstantiate = true;
  
  // Document foreign keys
  public $cmPluginHasMany = array();
  
  // Association rules from this model to other models
  public $belongsTo = array(
    "CoIdentifierValidator",
    "KdcServer",
    "HttpServer"
  );
  
  // Validation rules for table elements
  public $validate = array(
    'co_identifier_validator_id' => array(
      'rule' => 'numeric',
      'required' => true,
      'allowEmpty' => false
    ),
    'kdc_server_id' => array(
      'rule' => 'numeric',
      'required' => true,
      'allowEmpty' => false
    ),
    'http_server_id' => array(
      'rule' => 'numeric',
      'required' => true,
      'allowEmpty' => false
    )
  );
  
  /**
   * Expose menu items.
   * 
   * @ since COmanage Registry v4.1.0
   * @ return Array with menu location type as key and array of labels, controllers, actions as values.
   */
  
  public function cmPluginMenus() {
    return array();
  }
  

  /**
   * Validate the ACCESS ID.
   *
   * @since  COmanage Registry v4.1.0
   * @param  String  $identifier            The identifier to be validated
   * @param  Array   $coIdentifierValidator CO Identifier Validator configuration
   * @param  Array   $coExtendedType        CO Extended Type configuration describing $identifier
   * @param  Array   $pluginCfg             Configuration information for this plugin, if instantiated
   * @return Boolean True if $identifier is valid and available
   * @throws InvalidArgumentException If $identifier is not of the correct format
   * @throws OverflowException If $identifier is already in use
   */
  
  public function validate($identifier, $coIdentifierValidator, $coExtendedType, $pluginCfg) {
    // Open a connection to the KDC.
    $kdcServerId = $pluginCfg['kdc_server_id'];

    try {
      $kdc = $this->KdcServer->connect($kdcServerId);
    } catch (Exception $e) {
      $msg = _txt('er.accessidentifier.kdc.connect') . ": ";
      $msg = $msg . print_r($e->getMessage(), true);
      $this->log($msg);

      throw new RuntimeException($msg);
    }

    // Attempt to find the principal object.
    try {
      $principalObj = $kdc->getPrincipal($identifier);
    } catch (Exception $e) {
      $principalObj = null;
    }

    if(!empty($principalObj)) {
      // Principal exists so throw OverflowException.
      $msg = _txt('er.accessidentifier.principal.exists', array($identifier));
      throw new OverflowException($msg);
    }

    // Pull the ACCESS user database HTTP Server configuration.
    $httpServerId = $pluginCfg['http_server_id'];

    $args = array();
    $args['conditions']['HttpServer.server_id'] = $httpServerId;
    $args['contain'] = false;

    $accessDb = $this->HttpServer->find('first', $args);
    $urlBase = $accessDb['HttpServer']['serverurl'];
    $url = $urlBase . '/people/' . $identifier;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_URL, $url);

    // Include headers necessary for authentication.
    $headers = array();
    $headers[] = 'XA-REQUESTER: ' . $accessDb['HttpServer']['username'];
    $headers[] = 'XA-API-KEY: ' .  $accessDb['HttpServer']['password'];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Return the payload from the curl_exec call below.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Make the query and get the response and return code.
    $response = curl_exec($ch);
    $curlReturnCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

    curl_close($ch);

    if($curlReturnCode != 404) {
      // User record exists so throw OverflowException.
      $msg = _txt('er.accessidentifier.profile.exists', array($identifier));
      throw new OverflowException($msg);
    }
    
    // If we made it here we have no objection.
    return true;
  }
}
