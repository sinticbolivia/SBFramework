<?php
function mod_storage_show_table_column_image($item)
{
	$src = UPLOADS_URL . '/' . $item->file;
	return '<img src="' . $src . '" alt="" style="width:80px;" />';
}