<?php
/*---------------------------------------------------------------------------
 * @Plugin Name: aceAntiBot
 * @Plugin Id: aceantibot
 * @Plugin URI:
 * @Description:
 * @Version: 1.0.0
 * @Author: Vadim Shemarov (aka aVadim)
 * @Author URI:
 * @LiveStreet Version: 1.0.0
 * @File Name:
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

define('PLUGIN_ACEANTIBOT_VERSION', '1.0');
define('PLUGIN_ACEANTIBOT_VERSION_BUILD', '2');

$config = array('version' => PLUGIN_ACEANTIBOT_VERSION . '.' . PLUGIN_ACEANTIBOT_VERSION_BUILD);

$config['enable'] = true;

// названия ложных полей
$config['fake_names'] = array(
    'name', 'first_name', 'last_name', 'phone', 'address', 'telephone', 'city', 'country', 'street', 'job', 'title',
);

// суффикс для ложных полей
// он не должен быть пустым!
$config['fake_suffix'] = '_real';

return $config;

// EOF