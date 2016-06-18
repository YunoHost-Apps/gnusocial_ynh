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


  ~
