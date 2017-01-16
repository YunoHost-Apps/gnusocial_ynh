<?php
if (!defined('GNUSOCIAL')) { exit(1); }

$config['site']['name'] = 'gs_name';

$config['site']['server'] = 'gs_domain';
$config['site']['path'] = false;

$config['site']['ssl'] = 'sometimes';

$config['site']['fancy'] = true;

$config['db']['database'] = 'mysqli://yunouser:yunopass@localhost/yunouser';

$config['db']['type'] = 'mysql';

$config['db']['schemacheck'] = 'script';

// Uncomment below for better performance. Just remember you must run
// php scripts/checkschema.php whenever your enabled plugins change!
//$config['db']['schemacheck'] = 'script';

$config['site']['profile'] = 'gs_profile';

addPlugin('Qvitter');

addPlugin('ldapAuthentication', array(
        'provider_name'=>'localhost',
        'authoritative'=>true,
        'autoregistration'=>true,
        'email_changeable'=>false,
        'password_changeable'=>false,
        'password_encoding'=>'md5',
        'host'=>array( 'localhost' ),
        'starttls'=>false,
        'basedn'=>'dc=yunohost,dc=org',
        'attributes'=>array(
                'username'=>'uid',
                'nickname'=>'uid',
                'email'=>'mail',
                'fullname'=>'displayName',
                'password'=>'unicodePwd')
));

addPlugin('ldapAuthorization', array(
        'provider_name'=>'localhost',
        'authoritative'=>true,
        'login_group'=>'ou=users,dc=yunohost,dc=org',
        'roles_to_groups'=>array(
                // Create these roles migth be necessary
                'moderator'=>'cn=moderators,ou=users,dc=yunohost,dc=org',
                'administrator'=>'cn=admins,ou=users,dc=yunohost,dc=org'
        ),
        'basedn'=>'dc=yunohost,dc=org',
        'host'=>array( 'localhost' ),
        'starttls'=>false,
        'attributes'=>array(
                'username'=>'uid')
));

