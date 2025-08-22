<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Loads configuration from database into global CI config
 */
function load_config()
{
    $CI =& get_instance();

    // Only check migrations if they are enabled
    if ($CI->config->item('migration_enabled') === TRUE) {
        try {
            $migration = $CI->load->library('migration');
            if (!$CI->migration->is_latest())
            {
                $CI->session->sess_destroy();
            }
        } catch (Exception $e) {
            // Suppress migration errors - migrations are disabled or having issues
            log_message('error', 'Migration error: ' . $e->getMessage());
        }
    }

    foreach($CI->Appconfig->get_all()->result() as $app_config)
    {	
        $CI->config->set_item($CI->security->xss_clean($app_config->key), $CI->security->xss_clean($app_config->value));
    }

    // fallback to English if language settings are not correct
    $file_exists = !file_exists('../application/language/' . current_language_code());
    if(current_language_code() == null || current_language() == null || $file_exists)
    {
        $CI->config->set_item('language', 'english');
        $CI->config->set_item('language_code', 'en-US');
    }

    _load_language_files($CI, '../vendor/codeigniter/framework/system/language', current_language(), FALSE);
    _load_language_files($CI, '../application/language', current_language_code(), TRUE);

    //Set timezone from config database
    if($CI->config->item('timezone'))
    {
        date_default_timezone_set($CI->config->item('timezone'));
    }
    else
    {
        date_default_timezone_set('America/New_York');
    }

    bcscale(max(2, totals_decimals() + tax_decimals()));
}

/**
 * @param $CI
 * @param $path
 * @param $language
 * @param $fallback
 */
function _load_language_files($CI, $path, $language, $fallback)
{
    $map = directory_map($path . DIRECTORY_SEPARATOR . $language);

    foreach($map as $file)
	{

        if(!is_array($file) && substr(strrchr($file, '.'), 1) == 'php')
		{
            $filename = strtr($file, '', '_lang.php');
            if ($fallback) {
                $CI->lang->load($filename, 'en-US');

                $array = $CI->lang->load($filename, $language, TRUE);
                foreach($array as $lang_key => $lang_value) {
                    if ($lang_value !== '') {
                        $CI->lang->language[$lang_key] = $lang_value;
                    }
                }
            }
            else
            {
                $CI->lang->load($filename, $language);
            }

        }
    }
}

?>
