<?php

 App::uses("SIVController", "Controller");

class AccessIdentifierValidatorsController extends SIVController {
  // Class name, used by Cake
  public $name = "AccessIdentifierValidator";
  
  // Establish pagination parameters for HTML views
  public $paginate = array(
    'limit' => 25,
    'order' => array(
      'principal_type' => 'asc'
    )
  );

  /**
   * Callback after controller methods are invoked but before views are rendered.
   *
   * @since  COmanage Registry v4.1.0
   */

  function beforeRender() {
    parent::beforeRender();

    // Pull the list of available KDC and HTTP servers for the edit view.
    $args = array();
    $args['conditions']['Server.server_type'] = ServerEnum::KdcServer;
    $args['conditions']['Server.co_id'] = $this->cur_co['Co']['id'];
    $args['conditions']['Server.status'] = SuspendableStatusEnum::Active;
    $args['fields'] = array('id', 'description');
    $args['contain'] = false;

    $this->set('vv_kdc_servers', $this->AccessIdentifierValidator->KdcServer->Server->find('list', $args));

    $args = array();
    $args['conditions']['Server.server_type'] = ServerEnum::HttpServer;
    $args['conditions']['Server.co_id'] = $this->cur_co['Co']['id'];
    $args['conditions']['Server.status'] = SuspendableStatusEnum::Active;
    $args['fields'] = array('id', 'description');
    $args['contain'] = false;

    $this->set('vv_http_servers', $this->AccessIdentifierValidator->HttpServer->Server->find('list', $args));
  }
  
  /**
   * Authorization for this Controller, called by Auth component
   * - precondition: Session.Auth holds data used for authz decisions
   * - postcondition: $permissions set with calculated permissions
   *
   * @since  COmanage Registry v4.1.0
   * @return Array Permissions
   */
  
  function isAuthorized() {
    $roles = $this->Role->calculateCMRoles();
    
    // Construct the permission set for this user, which will also be passed to the view.
    $p = array();
    
    // Determine what operations this user can perform
    
    // Edit an existing Access Identifier Validator?
    $p['edit'] = $roles['cmadmin'] || $roles['coadmin'];
    
    // View all existing Access Identifier Validator?
    $p['index'] = $roles['cmadmin'] || $roles['coadmin'];
    
    // View an existing Access Identifier Validator?
    $p['view'] = $roles['cmadmin'] || $roles['coadmin'];
    
    $this->set('permissions', $p);
    return($p[$this->action]);
  }
}
