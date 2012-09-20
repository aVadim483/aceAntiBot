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

class PluginAceantibot_HookAceantibot extends Hook
{

    public function RegisterHook()
    {
        if (Config::Get('plugin.aceantibot.enable')) {
            $this->Init();
            $this->AddHook('template_form_login_popup_begin', 'LoginPopupBegin');
            $this->AddHook('template_form_login_popup_end', 'LoginPopupEnd');
            $this->AddHook('template_form_registration_begin', 'LoginPopupBegin');
            $this->AddHook('template_form_registration_end', 'LoginPopupEnd');
        }
    }

    protected function Init()
    {
        $aFields = array();
        $sSuffix = Config::Get('plugin.aceantibot.fake_suffix');
        foreach (Config::Get('plugin.aceantibot.fake_names') as $sName) {
            $sClass = 'login-input-var' . rand(1, 2);
            $aFields[] = '<p class="' . $sClass . '"><input type="text" name="'
                . $sName . $sSuffix . '" id="popup-'
                . $sName . $sSuffix . '" placeholder="'
                . $sName . $sSuffix . '" class="input-text input-width-full"></p>';
        }

        shuffle($aFields);
        $nRand = rand(round(sizeof($aFields) / 2), sizeof($aFields));
        $a = array('before'=>array(), 'after'=>array());
        for ($i = 0; $i < $nRand; $i++) {
            if ($i % 2) $a['before'][] = $aFields[$i];
            else $a['after'][] = $aFields[$i];
        }
        $this->Session_Set('plugin.aceantibot.fake_fields', serialize($a));
        $this->Session_Set('plugin.aceantibot.fake_suffix', Config::Get('plugin.aceantibot.fake_suffix'));
    }

    public function LoginPopupBegin()
    {
        $s = $this->Session_Get('plugin.aceantibot.fake_fields');
        if ($s) {
            $a = unserialize($s);
            if (is_array($a) AND isset($a['before'])) {
                return implode("\n", $a['before']);
            }
        }
    }

    public function LoginPopupEnd()
    {
        $s = $this->Session_Get('plugin.aceantibot.fake_fields');
        if ($s) {
            $a = unserialize($s);
            if (is_array($a) AND isset($a['after'])) {
                return implode("\n", $a['after']);
            }
        }
    }
}

// EOF
