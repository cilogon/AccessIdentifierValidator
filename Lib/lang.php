<?php
  
global $cm_lang, $cm_texts;

// When localizing, the number in format specifications (eg: %1$s) indicates the argument
// position as passed to _txt.  This can be used to process the arguments in
// a different order than they were passed.

$cm_access_identifier_validator_texts['en_US'] = array(
  // Titles, per-controller
  'ct.access_identifier_validators.1'  => 'ACCESS ID Identifier Validator',
  'ct.access_identifier_validators.pl' => 'ACCESS ID Identifier Validators',
  
  // Error messages
  'er.accessidentifier.kdc.connect'        => 'Failed to connect to ACCESS KDC server',
  'er.accessidentifier.principal.exists'   => 'Principal %1$s exists in the ACCESS KDC',
  'er.accessidentifier.profile.exists'     => 'Profile for %1$s exists in the ACCESS user database',
  
  // Plugin texts
  'pl.accessidentifier.kdc_server_id'       => 'KDC Server',
  'pl.accessidentifier.kdc_server_id.desc'  => 'ACCESS KDC Server',
  'pl.accessidentifier.http_server_id'       => 'Http Server',
  'pl.accessidentifier.http_server_id.desc'  => 'ACCESS User Database API Server',
  'pl.accessidentifier.principal_type'       => 'KDC Principal Type',
  'pl.accessidentifier.principal_type.desc'  => 'Identifier type used as the KDC principal',
);
