<?php
/**
 * Multilanguage Class
 * 
 * Linux: locale-gen es_ES.utf8;sudo locale-gen es_ES.utf8
 * @author marcelo
 *
 */
namespace SinticBolivia\SBFramework\Classes;

class SB_Language
{
    public static $isPomo 			= false;
    public static $pomo 			= null;
	public static $pomoDomains 	= array();
	
	public static function UsePOMO()
    {
		require_once INCLUDE_DIR . SB_DS . 'libs' . SB_DS . 'pomo' . SB_DS . 'mo.php';
        if( !function_exists('bindtextdomain') ):
        function bindtextdomain($domain, $lang_path)
        {
            SB_Language::POMOLoadDomain($domain, $lang_path);
		}
        endif;
        if( !function_exists('bind_textdomain_codeset') ):
        function bind_textdomain_codeset($domain, $code_set)
        {
        }
        endif;
        if( !function_exists('textdomain') ):
        function textdomain($domain)
        {
        }
        endif;
        if( !function_exists('gettext') ):
        function gettext($message)
        {
            return isset(SB_Language::$pomoDomains['default']) ? 
						SB_Language::$pomoDomains['default']->translate($message) : $message;
        }
        endif;
        if( !function_exists('dgettext') ):
        function dgettext($domain, $text)
        {
			//print_r($domain);var_dump($text);
            return isset(SB_Language::$pomoDomains[$domain]) ? 
						SB_Language::$pomoDomains[$domain]->translate($text) : gettext($text);
        }
        endif;
        self::$isPomo = true;
    }
	public static function getSupportedLanguages()
	{
		$_supported_languages = null;
		$dbh 	= SB_Factory::getDbh();
		$query 	= "SELECT * FROM languages ORDER BY language_name ASC";
		$res 	= $dbh->Query($query);
		if( !$res )
		{
			$_supported_languages = array(
					(object)array('language_code' => 'es_ES', 'language_name' => SB_Text::_('Spanish')),
					(object)array('language_code' => 'en_US', 'language_name' => SB_Text::_('English')),
					//'fr' => array('code' => 'fr_CH', 'text' => SB_Text::_('French'))
			);
		}
		else
		{
			$_supported_languages = $dbh->FetchResults(); 
		}
		return $_supported_languages;
	}
	/**
	 * Load language domain
	 * 
	 * @param string $lang
	 * @param string $domain
	 * @param string $path
	 * @return string
	 */
	public static function loadLanguage($lang_code, $domain, $path)
	{
		$locale = '';
		
		if( !is_dir($path) )
			return false;//throw new Exception('Locale '.$path.' dir does not exists');
		$code_set = '';
		$lang_path = $path . SB_DS . $lang_code;
		if( defined('OS_LINUX') )
		{
			$system_lang = getenv('LC_NAME');
		}
		//$full_code = sprintf("%s%s", $lang_code, $code_set);
		if( !is_dir($lang_path) )
		{
			return false;
		}
		//##include language php file
		if( is_file($lang_path . SB_DS . 'messages.php') )
		{
			ob_start();
			require_once $lang_path . SB_DS . 'messages.php';
			ob_get_clean();
		}
        
		if( self::$isPomo )
		{
			self::POMOLoadDomain($domain, $lang_path);
			return true;
		}
		//var_dump($lang_path, is_dir($lang_path . '.utf-8'));
		//##create language link to utf-8 codeset
		if( !is_dir($lang_path . '.utf-8') && is_writable($path) )
		{
			if( function_exists('symlink') )
			{
				//var_dump($path, $lang_code);
				$slink = $lang_path . '.utf-8';
				$res = symlink($lang_path, $slink);
				if( !$res )
				{
					sb_copy_recursive($path . SB_DS . $lang_code, $path . SB_DS . $lang_code . '.utf-8');
				}
			}
			else
			{
				sb_copy_recursive($path . SB_DS . $lang_code, $path . SB_DS . $lang_code . '.utf-8');
			}
		}
		$locale_dir = $path . SB_DS . $lang_code . '.utf-8';
		$locale_file = $locale_dir . SB_DS . 'LC_MESSAGES' . SB_DS . $domain . '.mo';
		if( !file_exists($locale_file) || !is_readable($locale_file) )
		{
			return false;//throw new Exception(sprintf(SB_Text::_('Locale file "%s" is not readeable or does not exists'), $locale_file));
		}
		$full_code = $lang_code . '.utf-8';
		// Set the language
		if( defined('OS_WIN') )
		{
			putenv('LANGUAGE='.$full_code);
			putenv('LANG='.$full_code);
			putenv('LC_ALL='.$full_code);
			putenv('LC_MESSAGES='.$full_code);
		}
		elseif( defined('OS_LINUX') )
		{
			setlocale(LC_ALL, $full_code);
			setlocale(LC_MESSAGES, $full_code);
		}
        //var_dump($locale_file);
		// Specify location of translation tables
		//bindtextdomain($domain, './locale/nocache');
		bindtextdomain($domain, $path);
		bind_textdomain_codeset($domain, $code_set);
	}
	public static function addTranslate($string_id, $translated_str, $language_id)
	{
		$date = date('Y-m-d H:i:s');
		$dbh = SB_Factory::getDbh();
		$dbh->Insert('translations', array('language_id' => $language_id, 
											'string_id' => $string_id, 
											'translated_string' => $translated_str,
											'last_modification_date' => $date,
											'creation_date' => $date
									)
		);
	}
	/**
	 * Get language by code
	 * 
	 * @param string $code
	 * @return NULL | language database row 
	 */
	public static function getLanguageByCode($code)
	{
		$query = "SELECT * FROM languages WHERE language_code = '$code'";
		$dbh = SB_Factory::getDbh();
		$res = $dbh->Query($query);
		if( !$res )
			return null;
		return $dbh->FetchRow();
	} 
    public static function POMOLoadDomain($domain, $lang_path)
    {
        $mo_file = $lang_path . SB_DS . 'LC_MESSAGES' . SB_DS . $domain . '.mo';
        if( !is_file ($mo_file) )
            return false;
            
        $pomo = new \MO();
        $pomo->import_from_file($mo_file);
        SB_Language::$pomoDomains[$domain] = $pomo;
    }
}
