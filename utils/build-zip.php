<?php
$mods = array(
		'default'	=> array(
				'mod_settings',
				'mod_users',
				'mod_content',
				'mod_dashboard',
				'mod_menu',
				'mod_backup',
				'mod_menu',
				'mod_modules',
				'mod_statistics',
		),
		'francisco' => array(
				'mod_settings',
				'mod_users',
				'mod_content',
				'mod_dashboard',
				'mod_menu',
				'mod_backup',
				'mod_exams',
				'mod_forms',
				'mod_gcp',
				'mod_menu',
				'mod_modules',
				'mod_optin',
				'mod_statistics',
				'mod_smn',
				'mod_userpopup',
				'mod_zaxaa',
				'mod_levels',
				'mod_userspromo'
		),
		'mb'		=> array(
				'mod_settings',
				'mod_users',
				'mod_dashboard',
				'mod_modules',
				'mod_backup',
				'mod_statistics',
				'mod_mb',
				'mod_invoices',
				'mod_quotes',
				'mod_provider',
				'mod_payments',
				'mod_employees',
				'mod_customers'
		)
);
$mods_str = '';
$type = isset($_GET['type']) ? $_GET['type'] : 'default';
if( !isset($mods[$type]) )
	die('The modules selection is invalid');
foreach($mods[$type] as $mod)
{
	$mods_str .= "modules/$mod ";
}
set_time_limit(0);
$zip_name = sprintf("lt-cms-%s.zip", date('Y-m-d-H.i.s'));
$bdir = dirname(dirname(__FILE__));
chdir($bdir);
var_dump(getcwd());
$cmd = "zip -r -9 -v utils/$zip_name admin/ images/ include/ install/ js/ locale/ modules/ templates/ captcha.php cloud-backup.php config-min.php error404b.html index.php init.php login-error.html register.php robots.txt php.ini";
print '<pre>';
system($cmd);
print '</pre>';