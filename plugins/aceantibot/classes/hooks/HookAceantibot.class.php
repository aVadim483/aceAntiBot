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
 * @File Name: %%filename%%
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

class PluginAceantibot_HookAceantibot extends Hook
{

    public function RegisterHook()
    {
        if (Config::Get('plugin.aceantibot.enable')) {
            $this->AddHook('template_html_head_begin', 'HtmlHeadBegin');
            $this->AddHook('template_html_head_end', 'HtmlHeadEnd');

            $this->AddHook('template_form_login_popup_begin', 'LoginPopupBegin');
            $this->AddHook('template_form_login_popup_end', 'LoginPopupEnd');
            $this->AddHook('template_form_registration_begin', 'LoginPopupBegin');
            $this->AddHook('template_form_registration_end', 'LoginPopupEnd');

            $this->AddHook('init_action', 'InitAction', __CLASS__);
        }
    }

    protected function _init()
    {
        $aFields = array();
        $sSuffix = Config::Get('plugin.aceantibot.fake_suffix');
        foreach (Config::Get('plugin.aceantibot.fake_names') as $sName) {
            if ($sName != 'login') {
                $sClass = 'login-input-var' . rand(1, 2);
                $aFields[] = '<p class="' . $sClass . '"><input type="text" name="'
                    . $sName . $sSuffix . '" id="popup-'
                    . $sName . $sSuffix . '" placeholder="'
                    . $sName . $sSuffix . '" class="input-text input-width-full"></p>';
            }
        }

        shuffle($aFields);
        $nRand = rand(round(sizeof($aFields) / 2), sizeof($aFields));
        $a = array('before' => array(), 'after' => array());
        for ($i = 0; $i < $nRand; $i++) {
            if ($i % 2) $a['before'][] = $aFields[$i];
            else $a['after'][] = $aFields[$i];
        }
        $this->Session_Set('plugin.aceantibot.fake_fields', serialize($a));
        $this->Session_Set('plugin.aceantibot.fake_suffix', $sSuffix);

        if (Config::Get('plugin.aceantibot.js')) {
            $aInputSets = array(
                'cnt' => $nCnt = rand(4, 6),
                'num' => rand(0, $nCnt),
                'style' => 'x' . substr(uniqid(), 0, 7),
            );
            $this->Session_Set('plugin.aceantibot.fake_login', serialize($aInputSets));
        }
    }

    public function HtmlHeadBegin()
    {
        $this->_init();
    }

    public function HtmlHeadEnd()
    {
        if (Config::Get('plugin.aceantibot.enable') AND Config::Get('plugin.aceantibot.js')) {
            if ($s = $this->Session_Get('plugin.aceantibot.fake_login')) {
                if (is_array($aInputSets = unserialize($s))) {
                    $aAttributes = array(
                        'position:relative',
                        'top:0',
                        'left:0',
                    );
                    $sStyles = '';
                    for ($i = 0; $i <= $aInputSets['cnt']; $i++) {
                        $aParams = $aAttributes;
                        if ($i != $aInputSets['num']) {
                            $aParams[] = 'display:none';
                        }
                        shuffle($aParams);
                        $sStyles .= 'input.' . $aInputSets['style']
                            . Config::Get('plugin.aceantibot.fake_suffix') . '-' . $i
                            . '{' . implode(';', $aParams) . '} ' . "\n";
                    }

                    $sScript = 'var login_input_real={';
                    $sScript .= 'style:"' . $aInputSets['style'] . '",';
                    $sScript .= 'cnt:"' . $aInputSets['cnt'] . '",';
                    $sScript .= 'suf:"' . Config::Get('plugin.aceantibot.fake_suffix') . '",';

                    $sFile = Plugin::GetTemplatePath(__CLASS__) . '/css/style.css';
                    if (is_file($sFile) AND ($sData = file_get_contents($sFile))) {
                        $sStyles .= ' ' . $sData;
                    }
                    $bEncode = true;
                    if ($bEncode) {
                        $sScript .= 'st_hash:"' . base64_encode($sStyles) . '"}';
                        $sScript = '<script type="text/javascript">' . $sScript . '</script>';
                        $sResult = $sScript;
                    } else {
                        $sScript = '<script type="text/javascript">' . $sScript . '</script>';
                        $sStyles = '<style type="text/css">' . $sStyles . '</style>';
                        $sResult = $sStyles . $sScript;
                    }

                    if (Config::Get('plugin.aceantibot.js')) {
                        $sResult .= '<script type="text/javascript" src="'
                            . Plugin::GetTemplateWebPath(__CLASS__) . '/js/script.js" /></script>';
                    }
                    return $sResult;
                }
            }
        }
    }

    public function LoginPopupBegin()
    {
        if ($s = $this->Session_Get('plugin.aceantibot.fake_fields')) {
            if (is_array($a = unserialize($s)) AND isset($a['before'])) {
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

    public function InitAction()
    {
        if (Config::Get('plugin.aceantibot.enable')) {
            if (in_array(Router::GetAction(), array('login', 'registration')) AND isset($_POST['login'])) {
                if (!$this->PluginAceantibot_Aceantibot_BotFree()) exit;
            }
        }
    }

}

// EOF
