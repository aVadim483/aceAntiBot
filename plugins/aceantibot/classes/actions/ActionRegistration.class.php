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

class PluginAceantibot_ActionRegistration extends PluginAceantibot_Inherit_ActionRegistration
{

    protected function EventIndex()
    {
        if (!$this->PluginAceantibot_Aceantibot_checkFake()) exit;
        return parent::EventIndex();
    }

    protected function EventAjaxValidateFields()
    {
        if (!$this->PluginAceantibot_Aceantibot_checkFake()) exit;
        return parent::EventAjaxValidateFields();
    }

    protected function EventAjaxRegistration()
    {
        if (!$this->PluginAceantibot_Aceantibot_checkFake()) exit;
        return parent::EventAjaxRegistration();
    }

    protected function EventActivate()
    {
        if (!$this->PluginAceantibot_Aceantibot_checkFake()) exit;
        return parent::EventActivate();
    }

}

// EOF
