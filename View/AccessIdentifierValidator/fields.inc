<?php
  // Determine if fields are editable
  $e = false;
  
  if(($this->action == "add" && $permissions['add']) || ($this->action == "edit" && $permissions['edit']))
    $e = true;
    
  // We shouldn't get here if we don't have at least read permission, but check just in case
  
  if(!$e && !$permissions['view'])
    return false;
  
  // Add breadcrumbs
  print $this->element("coCrumb");
  
  $args = array();
  $args['plugin'] = null;
  $args['controller'] = 'co_identifier_validators';
  $args['action'] = 'index';
  $args['co'] = $cur_co['Co']['id'];
  $this->Html->addCrumb(_txt('ct.co_identifier_validators.pl'), $args);
  
  $args = array();
  $args['plugin'] = null;
  $args['controller'] = 'co_identifier_validators';
  $args['action'] = 'edit';
  $args[] = $vv_ivid;
  $this->Html->addCrumb(_txt('ct.co_identifier_validators.1'), $args);  
  
  $crumbTxt = _txt('op.' . $this->action . '-a', array(_txt('ct.access_identifier_validators.1')));
  $this->Html->addCrumb($crumbTxt);
  
  print $this->Form->hidden('co_identifier_validator_id', array('default' => $vv_ivid)) . "\n";
?>

<?php if(empty($vv_kdc_servers)): ?>
<div class="co-info-topbox">
  <i class="material-icons">info</i>
  <?php print _txt('er.server.none', array(_txt('en.server', null, ServerEnum::KdcServer))); ?>
</div>
<?php elseif(empty($vv_http_servers)): ?>
<div class="co-info-topbox">
  <i class="material-icons">info</i>
  <?php print _txt('er.server.none', array(_txt('en.server', null, ServerEnum::HttpServer))); ?>
</div>
<?php else: ?>

<ul id="<?php print $this->action; ?>_access_identifier_validator" class="fields form-list form-list-admin">
  <li>
    <div class="field-name">
      <?php print ($e ? $this->Form->label('kdc_server_id', _txt('pl.accessidentifier.kdc_server_id')) : _txt('pl.accessidentifier.kdc_server_id')); ?>
      <span class="required">*</span>
    </div>
    <div class="field-info">
      <?php
        $attrs = array();
        $attrs['value'] = (isset($access_identifier_validators[0]['AccessIdentifierValidator']['kdc_server_id'])
                           ? $access_identifier_validators[0]['AccessIdentifierValidator']['kdc_server_id']
                           : null);
        // Since required=true, this will render blank by default but not allow save until a server is selected
        $attrs['empty'] = true;

        if($e) {
          print $this->Form->select('kdc_server_id',
                                    $vv_kdc_servers,
                                    $attrs);

          if($this->Form->isFieldError('kdc_server_id')) {
            print $this->Form->error('kdc_server_id');
          }
        } else {
          if(!empty($access_identifier_validators[0]['AccessIdentifierValidator']['kdc_server_id'])) {
            print filter_var($vv_servers[ $access_identifier_validators[0]['AccessIdentifierValidator']['kdc_server_id'] ], FILTER_SANITIZE_SPECIAL_CHARS);
          }
        }
      ?>
    </div>
  </li>
  <li>
    <div class="field-name">
      <?php print ($e ? $this->Form->label('http_server_id', _txt('pl.accessidentifier.http_server_id')) : _txt('pl.accessidentifier.http_server_id')); ?>
      <span class="required">*</span>
    </div>
    <div class="field-info">
      <?php
        $attrs = array();
        $attrs['value'] = (isset($access_identifier_validators[0]['AccessIdentifierValidator']['http_server_id'])
                           ? $access_identifier_validators[0]['AccessIdentifierValidator']['http_server_id']
                           : null);
        // Since required=true, this will render blank by default but not allow save until a server is selected
        $attrs['empty'] = true;

        if($e) {
          print $this->Form->select('http_server_id',
                                    $vv_http_servers,
                                    $attrs);

          if($this->Form->isFieldError('http_server_id')) {
            print $this->Form->error('http_server_id');
          }
        } else {
          if(!empty($access_identifier_validators[0]['AccessIdentifierValidator']['http_server_id'])) {
            print filter_var($vv_servers[ $access_identifier_validators[0]['AccessIdentifierValidator']['http_server_id'] ], FILTER_SANITIZE_SPECIAL_CHARS);
          }
        }
      ?>
    </div>
  </li>
  <?php if($e): ?>
    <li>
      <div class="field-name">
        <span class="required"><?php print _txt('fd.req'); ?></span>
      </div>
      <div class="field-info">
        <?php print $this->Form->submit($submit_label); ?>
      </div>
    </li>
  <?php endif; ?>
</ul>

<?php endif; ?>
