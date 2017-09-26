<?php
/**
 * @property bool 	showSelector
 * @property bool 	showCount
 * @property int	itemsPerPage
 * 
 * 
 */
class LT_TableList extends SB_Object
{
	protected	$table;
	/**
	 * The table primary key column name
	 * @var string
	 */
	protected	$column_id;
	protected	$module			= '';
	protected	$itemsPerPage 	= 25;
	protected	$columns 		= array();
	protected	$rowActions		= array(
										'edit' 		=> array(
												'link' 	=> '', 
												'label' => 'Edit', 
												'icon' 	=> 'glyphicon glyphicon-edit'
										),
										'delete' 	=> array(
												'link' => '', 
												'label' => 'Delete', 
												'icon' => 'glyphicon glyphicon-trash'
										)
	);
	protected	$items				= array();
	protected	$showSelector		= true;
	protected	$showCount			= true;
	protected	$dbh;
	protected	$dbColumns			= array();
	protected	$query				= null;
	protected	$order				= 'desc';
	protected	$order_by			= null;
	protected 	$conditions			= array();
	protected	$useTableColumns	= false;
	public		$cssClass			= '';
	public 		$showExport			= false;
	public		$exportTypes		= array('CSV');
	public function __construct($table, $column_id, $mod = 'unknow')
	{
		$this->table 		= $table;
		$this->column_id	= $column_id;
		$this->module		= $mod;
		$this->dbh			= SB_Factory::getDbh();
	}
	/**
	 * Get table columns for listing
	 * 
	 * @return  
	 */
	public function UseTableColumns($prefix = '')
	{
		$this->useTableColumns = true;
		//##get columns from query
		if( $this->query )
		{
			return true;
		}
		$table = $this->table;
		
		if( stristr($this->table, ',') )
		{
			$tables = explode(',', $this->table);
			$table = array_shift($tables);
		}
		/*
		$cols = array();
		foreach(SB_DbTable::GetTableColumns(strtok($table, ' ')) as $index => $data)
		{
			$cols[$prefix . $index] = $data;
		}
		*/
		$cols = SB_DbTable::GetTableColumns(strtok($table, ' '));
		$this->SetColumns($cols);
		return true;
	}
	public function SetColumns($columns)
	{
		$this->columns = (array)$columns;
	}
	public function AddCondition($column, $operator, $value, $AND_OR = 'AND', $join = false)
	{
		$this->conditions[] = array(
			'column'	=> $column,
			'operator'	=> $operator,
			'value'		=> $value,
			'and_or'	=> $AND_OR,
			'join'		=> $join
		);
	}
	/**
	 * Set the row actions, by default the actions are edit and delete.
	 * In order to replace or add new actions, you will to pass a array with string index that will do as view parameter:
	 * Example:
	 * 'edit' => array('label' => 'Edit', 'icon' => 'glyphicon glyphicon-edit', 'link' => null)
	 * This will build a link like:
	 * index.php?mod=[module]&view=edit&id=[row_id]
	 * 
	 * You can build a task button as well
	 * 
	 * 'task:delete' => array('label' => 'Delete')
	 * 
	 * It will build a link like:
	 * index.php?mod=[module]&task=delete&id=[row_id]
	 * 
	 * If you want to passed a fixed link, just use the the 'link' index 
	 * 
	 * 'edit' => array('label' => 'Edit', 'icon' => 'glyphicon glyphicon-edit', 'link' => 'http://google.com')
	 * 
	 * 
	 * @param array $actions 
	 * @return void
	 */
	public function SetRowActions($actions)
	{
		$this->rowActions = $actions;
	}
	/**
	 * Set a SQL query to build the table list model and results
	 * 
	 * @param string $query 
	 * @return bool
	 */
	public function SetQuery($query)
	{
		$this->query = $query;
		return true;
	}
	public function Fill()
	{
		$page		= SB_Request::getInt('page', 1);
		$search_by 	= SB_Request::getString('search_by');
		$keyword 	= SB_Request::getString('keyword');
		$export		= SB_Request::getString('export');
		if( $page <= 0 )
			$page = 1;
		$this->currentPage	= $page;
		$total_rows = 0;
		if( $this->query )
		{
			//##count rows
			$query 		= preg_replace('/^(SELECT)\s+(.*)\s+(FROM.*)/i', '$1 COUNT(*) $3', $this->query);
			//$total_rows = $this->dbh->GetVar($query);
			//var_dump($total_rows);die();
			//##check if there is a search request
			if( $keyword && $search_by && $search_by != '-1' )
			{
				$query .= " AND $search_by LIKE '%$keyword%' ";
			}
			$total_rows = $this->dbh->GetVar($query);
		}
		else
		{
			foreach($this->columns as $db_col => $_data)
			{
				$data = (array)$_data;
				if( isset($data['db_col']) && !$data['db_col'] ) continue;
				if( isset($data['subquery']) && !empty($data['subquery']) )
				{
					$this->dbColumns[] = "({$data['subquery']}) AS $db_col";
				}
				else
				{
					$this->dbColumns[] = $db_col;
				}
			}
			$this->dbh->Select('COUNT(*)')
						->From($this->table)
						->Where(null);
			$this->BuildConditions();
			//##check if there is a search request
			if( $keyword && $search_by && $search_by != '-1' )
			{
				$this->dbh->SqlAND(array($search_by => $keyword), 'LIKE', '%', '%');			
			}
			$this->dbh->Query(null);
			$total_rows	= (int)$this->dbh->GetVar();
		}
		
		if( !$total_rows )
			return true;
		$this->totalPages 	= $this->itemsPerPage > 0 ? ceil($total_rows / $this->itemsPerPage) : 1;
		$offset				= $page == 1 ? 0 : ($page - 1) * $this->itemsPerPage;
		if( $this->query )
		{
			$query = $this->query;
			//##check if there is a search request
			if( $keyword && $search_by && $search_by != '-1' )
			{
				$query .= " AND $search_by LIKE '%$keyword%' ";
			}
			$this->dbh->builtQuery = $query . ' ';
		}
		else
		{
			$this->dbh->Select($this->dbColumns)
					->From($this->table)
					->Where(null);
			$this->BuildConditions();
			//##check if there is a search request
			if( $keyword && $search_by && $search_by != '-1' )
			{
				$this->dbh->SqlAND(array($search_by => $keyword), 'LIKE', '%', '%');
			}
		}
		
		$this->dbh->OrderBy($this->order_by ? $this->order_by : $this->column_id, $this->order);
		if( $this->itemsPerPage > 0 )
			$this->dbh->Limit($this->itemsPerPage, $offset);
		$this->dbh->Query(null);
		//var_dump($this->dbh->lastQuery);
		$this->items	= $this->dbh->FetchResults();
		
		if( $this->query /*&& $this->useTableColumns*/ )
		{
			$this->columns = array();
			$this->dbColumns = array();
			foreach($this->items[0] as $db_col => $value)
			{
				$this->dbColumns[] = $db_col;
				$this->columns[$db_col] = array();
			}
			//print_r($this->columns);die();
		}
		//##check for export
		if( $export && in_array($export, $this->exportTypes) )
		{
			call_user_func(array($this, "Export$export"));
		}
	}
	public function Show()
	{
		//$order		= SB_Request::getString('order', $this->order);
		//$orderby	= $this-
		$view		= SB_Request::getString('view');
		if( $view )
			$view = 'view=' . $view . '&';
			
		$new_order = $this->order == 'asc' ? 'desc' : 'asc';
		$current_view = SB_Request::getString('view', 'default');
		
		?>
		<div class="table-list-container">
			<?php if($this->showExport): ?>
			<div class="form-group" class="table-export-buttons">
				<?php foreach($this->exportTypes as $type): ?>
				<a href="<?php print 'index.php?' . sb_querystring_append($_SERVER['QUERY_STRING'], array('export' => $type)); ?>" class="btn btn-info btn-xs btn-export-<?php print strtolower($type); ?>">
					<span class="glyphicon glyphicon-export"></span> <?php printf(__('Export to %s', 'lt'), $type); ?>
				</a>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
			<div class="table-responsive">
				<table class="table table-condensed <?php print $this->cssClass; ?>">
				<thead>
				<tr>
					<?php if( $this->showSelector ): ?>
					<th class="col-selector text-center"><input type="checkbox" name="cb_selector" value="1" class="tcb-select-all" /></th>
					<?php endif; ?>
					<?php if( $this->showCount ): ?>
					<th class="col-count text-center"><?php _e('Num', 'lt'); ?></th>
					<?php endif; ?>
					<?php foreach($this->columns as $db_col => $_col): $col = (array)$_col; if( isset($col['show']) && !$col['show'] ) continue; ?>
					<th class="db-col-<?php print $db_col; ?>">
						<?php if( isset($col['can_order']) && $col['can_order'] ): ?>
						<?php 
						$link = SB_Route::_('index.php?mod='.$this->module.'&'.$view.'order_by='.$db_col.'&order='.$new_order);
						?>
						<a href="<?php print $link; ?>">
						<?php endif; ?>
							<?php print isset($col['label']) ? $col['label'] : $db_col; ?>
						<?php if( isset($col['can_order']) && $col['can_order'] ): ?>
							<span class="glyphicon glyphicon-triangle-<?php print ($this->order_by == $db_col && $this->order == 'asc') ? 'bottom' : 'top'; ?>"></span>
						</a>
						<?php endif; ?>
					</th>
					<?php endforeach; ?>
					<?php if( $this->rowActions && is_array($this->rowActions) ): ?>
					<th class="col-actions"><?php _e('Actions', 'lt'); ?></th>
					<?php endif; ?>
				</tr>
				</thead>
				<tbody>
				<?php if( is_array($this->items) && count($this->items) ): $i = 1; foreach($this->items as $item): SB_Module::do_action('table_list_before_show_item', $item); ?>
				<tr <?php foreach($item as $prop => $value){print "data-$prop=\"$value\" ";}?>>
					<?php if( $this->showSelector ): ?>
					<td class="col-selector text-center"><input type="checkbox" name="ids[]" value="<?php print $item->{$this->column_id}?>" class="tcb-select" /></td>
					<?php endif; ?>
					<?php if( $this->showCount ): ?>
					<td class="col-count text-center"><?php print $i; ?></td>
					<?php endif; ?>
					<?php foreach($this->columns as $db_col => $_col): $col = (array)$_col;if( isset($col['show']) && !$col['show'] ) continue; ?>
					<td class="db-col-<?php print $db_col; ?> <?php print isset($col['class']) ? $col['class'] : ''; ?>">
						<?php 
						$res = '';
						if( isset($col['callback']) )
						{
							//if( isset($item->$db_col) )
							//	$res = call_user_func($col['callback'], $item->$db_col);
							//else
							$res = call_user_func($col['callback'], $item);
						}
						elseif( isset($item->$db_col) )
							$res = $item->$db_col;
						else 
							$res = '';
						?>
						<div class="value"><?php print $res; ?></div>
					</td>
					<?php endforeach; ?>
					<?php if( $this->rowActions && is_array($this->rowActions) ): ?>
					<td class="col-actions">
						<?php foreach($this->rowActions as $_action => $data): ?>
						<?php
						$action = $_action;
						$link = "mod=$this->module&";
						if( isset($data['link']) && $data['link'] )
						{
							$link = $data['link'];
						}
						else
						{
							if( strstr($_action, ':') )
							{
								list($arg, $value) = explode(':', $_action);
								$link .= "$arg=$value&";
								$action = $value;
							}
							else
							{
								$link .= "view=$action&";
							} 
							$link .= "id=" . $item->{$this->column_id};
							$link = SB_Route::_('index.php?'.$link);
						}
						
						?>
						<a href="<?php print $link; ?>" class="btn btn-default btn-xs btn-action-<?php print $action; ?> <?php print isset($data['class']) ? $data['class'] : ''; ?>" 
							<?php print isset($data['icon']) ? 'title="'.$data['label'].'"' : ''; ?>
							data-id="<?php print $item->{$this->column_id}; ?>"
							<?php if( isset($data['data']) ) foreach((array)$data['data'] as $key => $d) print "data-$key=\"$d\" "; ?>>
							<?php if( isset($data['icon']) ): ?>
							<span class="<?php print $data['icon']; ?>"></span>
							<?php else: ?>
							<span><?php print $data['label']; ?></span>
							<?php endif; ?>
						</a>
						<?php endforeach; ?>
					</td>
					<?php endif; ?>
				</tr>
				<?php $i++; endforeach;else: ?>
				<tr>
					<td colspan="<?php print count($this->columns); ?>"><?php _e('There are no records found yet', 'lt'); ?></td>
				</tr>
				<?php endif; ?>
				</tbody>
				</table>
			</div>
			<p>
				<?php print lt_pagination($_SERVER['REQUEST_URI'], $this->totalPages, $this->currentPage); ?>
			</p>
		</div><!-- end class="table-list-container" -->
		<?php 
	}
	protected function BuildConditions()
	{
		if( is_array($this->conditions) && count($this->conditions) )
		{
			foreach($this->conditions as $cond)
			{
				if( $cond['join'] )
				{
					$this->dbh->Join(array($cond['column'] => $cond['value']));
				}
				elseif( strtoupper($cond['and_or']) == 'AND' )
				{
					$this->dbh->SqlAND(array($cond['column'] => $cond['value']), $cond['operator']);
				}
				elseif( strtoupper($cond['and_or']) == 'AND' )
				{
					$this->dbh->SqlOR(array($cond['column'] => $cond['value']), $cond['operator']);
				}
			}
		}
	}
	public function ExportCSV()
	{
		$csv = '';
		foreach($this->columns as $db_col => $_col)
		{
			$col = (array)$_col;
			if( isset($col['show']) && !$col['show'] ) continue;
			$label = isset($col['label']) ? $col['label'] : $db_col;
			$csv .= "\"{$label}\";";
		}
		$csv = rtrim($csv, ';') . "\n";
		//var_dump(count($this->items));
		foreach($this->items as $item)
		{
			$line = '';
			foreach($this->columns as $db_col => $_col)
			{
				$col = (array)$_col;
				if( isset($col['show']) && !$col['show'] ) continue;
				$line .= "\"{$item->{$db_col}}\";";
			}
			$line = rtrim($line, ';');
			$csv .= $line . "\n";
			//die($csv);
		}
		
		$filename = "export.csv";
		Header('Content-Description: File Transfer');
		Header('Content-Type: text/csv');
		Header('Content-Disposition: attachment; filename=' . $filename);
		header('Content-Length: ' . strlen($csv));
		die($csv);
	}
}