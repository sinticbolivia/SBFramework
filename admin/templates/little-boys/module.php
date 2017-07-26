<?php
define('MOD_TEMPLATE', 1);
lt_get_header()
?>
<?php SB_MessagesStack::ShowMessages(); ?>
<?php print sb_show_module(); ?>
<?php lt_get_footer(); ?>