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

class PluginAceantibot_ActionLogin extends PluginAceantibot_Inherit_ActionLogin
{

    protected function EventLogin()
    {
        if (!$this->PluginAceantibot_Aceantibot_checkFake()) exit;
        return parent::EventLogin();
    }

    protected function EventAjaxLogin()
    {
        if (!$this->PluginAceantibot_Aceantibot_checkFake()) exit;
        return parent::EventAjaxLogin();
    }

}

// EOF
