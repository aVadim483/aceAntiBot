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

class PluginAceantibot_ModuleAceantibot extends Module
{

    public function Init()
    {
    }

    /**
     * Проверка на наличие ботов
     *
     * @return bool
     */
    public function BotFree()
    {
        if (!Config::Get('plugin.aceantibot.enable')) return true;

        $bResult = true;
        if (Config::Get('plugin.aceantibot.js')) {
            if (($s = $this->Session_Get('plugin.aceantibot.fake_login')) AND is_array($aInputSets = unserialize($s))) {
                $sLoginField = 'login-' . $aInputSets['num'];
            } else {
                $bResult = false;
            }
        }

        $bResult = ($bResult AND $this->_checkFakeFields($sLoginField) AND $this->_checkLogin($sLoginField));

        return $bResult;
    }

    protected function _checkFakeFields($sLoginField)
    {
        $bResult = true;
        if (Config::Get('plugin.aceantibot.enable')) {
            if (
                $this->Session_Get('plugin.aceantibot.fake_fields')
                AND $this->Session_Get('plugin.aceantibot.fake_suffix') == Config::Get('plugin.aceantibot.fake_suffix')
            ) {
                // все, что заканчивается фейковым суффиксом - обманка
                $nLen = strlen($sSuffix = Config::Get('plugin.aceantibot.fake_suffix'));
                foreach ($_POST as $sKey => $sVal) {
                    if (($sKey != $sLoginField) AND substr($sKey, -$nLen) == $sSuffix AND $sVal) {
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

    protected function _checkLogin($sLoginField)
    {
        $bResult = true;
        if (Config::Get('plugin.aceantibot.enable') AND Config::Get('plugin.aceantibot.js')) {
            if (($s = $this->Session_Get('plugin.aceantibot.fake_login')) AND is_array($aInputSets = unserialize($s))) {
                if (isset($_POST['fields'])) {
                    foreach ($_POST['fields'] as $nKey=>$aData) {
                        if (isset($aData['field']) AND $aData['field'] == $sLoginField) {
                            $_POST['fields'][$nKey]['field'] = 'login';
                            if (isset($_REQUEST['fields']) AND isset($_REQUEST['fields'][$nKey]) AND isset($_REQUEST['fields'][$nKey]['field'])) {
                                $_REQUEST['fields'][$nKey]['field'] = 'login';
                            }
                        }
                    }
                } else {
                    if (!isset($_POST[$sLoginField]) OR !isset($_POST['login'])) {
                        $bResult = false;
                    //} elseif (!$_POST[$sLoginField] AND $_POST['login']) {
                    //    $bResult = false;
                    } elseif ($_POST['login']) {
                        $bResult = false;
                    } else {
                        if ($_POST[$sLoginField] AND !$_POST['login']) {
                            $_POST['login'] = $_POST[$sLoginField];
                            if (isset($_REQUEST['login'])) {
                                $_REQUEST['login'] = $_POST[$sLoginField];
                            }
                        }
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
