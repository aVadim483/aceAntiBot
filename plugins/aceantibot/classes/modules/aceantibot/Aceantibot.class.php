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

class PluginAceantibot_ModuleAceantibot extends Module
{

    public function Init()
    {

    }

    public function checkFake()
    {
        $bResult = true;
        if (Config::Get('plugin.aceantibot.enable')) {
            if (
                $this->Session_Get('plugin.aceantibot.fake_fields')
                AND $this->Session_Get('plugin.aceantibot.fake_suffix') == Config::Get('plugin.aceantibot.fake_suffix')
            ) {
                // все, что заканчивается фейковым суффиксом - обманка
                $nLen = strlen($sSuffix = Config::Get('plugin.aceantibot.fake_suffix'));
                foreach ($_POST as $sKey=>$sVal) {
                    if (substr($sKey, -$nLen) == $sSuffix AND $sVal) {
                        // если хоть что-то заполнено - это бот
                        $bResult = false;
                        break;
                    }
                }
            } else {
                $bResult = false;
            }
        }
        return $bResult;
    }

}

// EOF
