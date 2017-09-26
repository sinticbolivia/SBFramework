<?php
abstract class LT_BaseTheme
{
	protected $settingsTab = null;
	protected $settingsFields = array();
	protected $settings;
	protected $settingsKey = null;
	protected $fieldPrefix = null;
	protected $languageDomain = null;
	protected $languagePath		= null;
	protected function __construct()
	{
		if( $this->settingsTab )
		{
			$this->settingsKey = sb_build_slug($this->settingsTab);
			//##load theme settings
			$this->LoadSettings();
		}
		if( $this->languageDomain && $this->languagePath && is_dir($this->languagePath) )
		{
			SB_Language::loadLanguage(LANGUAGE, $this->languageDomain, $this->languagePath);
		}
		$this->AddActions();
		$this->AddShortcodes();
	}
	/**
	 * Get the template instance
	 * 
	 * @return LT_BaseTheme
	 */
	public static function GetInstance()
	{
		static $instance;
		if( !$instance )
		{
			$class = static::class; //get_called_class();
			$instance = new $class();
		}
		
		return $instance;
	}
	protected function AddActions()
	{
		if( lt_is_admin() && $this->settingsTab && count($this->settingsFields) )
		{
			SB_Module::add_action('settings_tabs', array($this, 'SettingsTabs'));
			SB_Module::add_action('settings_tabs_content', array($this, 'SettingsTabsContent'));
			SB_Module::add_action('save_settings', array($this, 'SaveSettings'));
		}
	}
	protected function AddShortcodes()
	{
	}
	public function SaveSettings()
	{
		$data = array();
		$prefix = $this->fieldPrefix ? $this->fieldPrefix : $this->settingsKey . '_';
		foreach($this->settingsFields as $field)
		{
			$param = $prefix . $field['name'];
			if( $field['type'] == 'file' )
			{
			}
			else
			{
				$data[$param] = SB_Request::getString($param);
			}
			
		}
		
		sb_update_parameter($this->settingsKey, $data);
	}
	public function LoadSettings()
	{
		$this->settings = (object)sb_get_parameter($this->settingsKey, array());
		//print_r($this->settings);
	}
	public function SettingsTabs()
	{
		if( !$this->settingsKey )
			return false;
		?>
		<li>
			<a href="#<?php print $this->settingsKey; ?>"><?php print $this->settingsTab; ?></a>
		</li>
		<?php
	}
	public function SettingsTabsContent()
	{
		if( !is_array($this->settingsFields) )
			return false;
		//##build field name and id
		$prefix = $this->fieldPrefix ? $this->fieldPrefix : $this->settingsKey . '_';
		$types = array('text', 'email', 'number', 'checkbox', 'radio', 'file');
		?>
		<div id="<?php print $this->settingsKey; ?>" class="tab-pane">
			<?php foreach($this->settingsFields as $field):  ?>
				<?php
				$id = "$prefix{$field['name']}"; 
				$class = 'form-control';
				?>
			<?php if( in_array($field['type'], $types) ): ?>
			<div class="form-group">
				<label class="control-label"><?php print $field['label']; ?></label>
				<input type="<?php print $field['type'] ?>" 
					id="<?php print $id ?>" 
					name="<?php print $id ?>"
					value="<?php print $this->GetValue($field['name']); ?>"
					class="<?php print $class; ?>" />
			</div>
			<?php elseif( $field['type'] == 'textarea' ): ?>
			<textarea id="<?php print $id; ?>" name="<?php print $id; ?>" class="form-control"><?php print $this->$field['name']; ?></textarea>
			<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<?php
	}
	public function __get($var)
	{
		if( isset( $this->$var ) ) 
			return $this->$var;
		if( isset( $this->settings->$var ) ) 
			return $this->settings->$var;
		return null;
	}
	/**
	 * Get a value from theme settings
	 * 
	 * @param string $var 
	 * @return string
	 */
	public function GetValue($var, $prefix = null)
	{
		if( !$prefix )
			$prefix = $this->fieldPrefix ? $this->fieldPrefix : $this->settingsKey . '_';
		$var = $prefix . $var;
		return isset($this->settings->$var) ? $this->settings->$var : null;
	}
}