<?php
namespace SinticBolivia\SBFramework\Modules\Cmihope\Controllers;

use SinticBolivia\SBFramework\Classes\SB_Controller;
use SinticBolivia\SBFramework\Classes\SB_Route;
use SinticBolivia\SBFramework\Classes\SB_Request;
use SinticBolivia\SBFramework\Classes\SB_MessagesStack;
use SinticBolivia\SBFramework\Classes\SB_TableList;
use SinticBolivia\SBFramework\Database\Classes\SB_DbTable;

class TransfersController extends SB_Controller
{
	public function task_default()
	{
		if( !sb_is_user_logged_in() )
		{
			sb_redirect(SB_Route::_('index.php?mod=users&view=login'));
		}
		$user = sb_get_current_user();
		$title = __('Exam Room Transfers', 'cmihope');
		$user = sb_get_current_user();
		$table = new SB_TableList('mb_transfers', 'id', 'cmihope');
		$table->SetColumns(array(
				'id'			=> array('label' => 'ID'),
				'user_id'		=> array('label' => __('User', 'cmihope')),
				'from_store'	=> array('label' => __('From Store', 'cmihope')),
				'to_store'		=> array('label' => __('To Store', 'cmihope')),
				'from_warehouse'	=> array('label' => __('From Warehouse', 'cmihope')),
				'to_warehouse'		=> array('label' => __('To Warehouse', 'cmihope')),
				'status'			=> array('label' => __('Status', 'cmihope')),
				'creation_date'		=> array('label' => __('Date Time'))
		));
		$table->AddCondition('user_id', '=', $user->user_id);
		$table->Fill();
		//$table->AddCondition($column, $operator, $value);
		$this->SetView('transfers/default');
		$this->SetVars(get_defined_vars());
		$this->document->SetTitle($title);
	}
	public function task_new()
	{
		if( !sb_is_user_logged_in() )
		{
			sb_redirect(SB_Route::_('index.php?mod=users&view=login'));
		}
		$user = sb_get_current_user();
		if( !$user->_store_id )
		{
			lt_die(__('The user has not store assigned, please contact with administrator', 'cmihope'));
		}
		$title = __('Create New Transfer', 'cmihope');
		$warehouses = SB_DbTable::GetTable('mb_warehouse', 1)->GetRows(-1, 0, array('store_id' => $user->_store_id));
		lt_add_js('angularjs', BASEURL . '/js/angular/angular-1.6.4.min.js', 0, true);
		lt_add_js('cmi-transfer-app', MOD_CMIHOPE_URL . '/js/app/transfers.js', 0, true);
		$this->SetView('transfers/new');
		$this->SetVars(get_defined_vars());
	}
}