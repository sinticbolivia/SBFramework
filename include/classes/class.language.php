<?php
/**
 * Multilanguage Class
 * 
 * Linux: locale-gen es_ES.utf8;sudo locale-gen es_ES.utf8
 * @author marcelo
 *
 */
class SB_Language
{
	
	public static function getSupportedLanguages()
	{
		$_supported_languages = null;
		$dbh = SB_Factory::getDbh();
		$query = "SELECT * FROM languages ORDER BY language_name ASC";
		$res = $dbh->Query($query);
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
		if( strtolower(PHP_OS) == 'linux' )
		{
			$system_lang = getenv('LC_NAME');
			//if( stristr($system_lang, '.utf-8') )
			//{
				//$code_set = '.utf-8';
			//}
		}
		//$full_code = sprintf("%s%s", $lang_code, $code_set);
		if( !is_dir($path . SB_DS . $lang_code) )
		{
			return false;
		}
		//##create language link to utf-8 codeset
		if( !is_dir($path . SB_DS . $lang_code . '.utf-8') && is_writable($path) )
		{
			if( function_exists('symlink') )
			{
				$res = @symlink($path . SB_DS . $lang_code, $path . SB_DS . $lang_code . '.utf-8');
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
		/*
		if( !is_dir($path . SB_DS . $full_code) )
			symlink($path . SB_DS . $lang_code, $path . SB_DS . $full_code);
		*/
		$locale_dir = $path . SB_DS . $lang_code . '.utf-8';
		
		//##include language file
		if( file_exists($locale_dir . SB_DS . 'messages.php') )
		{
			ob_start();
			require_once $locale_dir . SB_DS . 'messages.php';
			ob_get_clean();
		}
		$locale_file = $locale_dir . SB_DS . 'LC_MESSAGES' . SB_DS . $domain . '.mo';
		if( !file_exists($locale_file) || !is_readable($locale_file) )
		{
			return false;//throw new Exception(sprintf(SB_Text::_('Locale file "%s" is not readeable or does not exists'), $locale_file));
		}
		$full_code = $lang_code . '.utf-8';
		// Set the language
		@putenv('LANGUAGE='.$full_code);
		@putenv('LANG='.$full_code);
		//@putenv('LC_ALL='.$full_code);
		@putenv('LC_MESSAGES='.$full_code);
		@setlocale(LC_ALL, $full_code);
		@setlocale(LC_MESSAGES, $full_code);
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
}
class SB_Text
{
	public static function _($text, $domain = 'lt')
	{
		if( empty($text) )
			return '';
		if( defined($text) )
			return constant($text);
		
		textdomain($domain);
		//$text = gettext($text);
		//$text = dcgettext($domain, $text, 1);
		$text = dgettext($domain, $text);
		return SB_Module::do_action('lang_text', $text);
	}
}
class SBText extends SB_Text{}
function __($text, $domain = 'lt')
{
	return SBText::_($text, $domain);
}
function _e($text, $domain = 'lt')
{
	print SBText::_($text, $domain);
}