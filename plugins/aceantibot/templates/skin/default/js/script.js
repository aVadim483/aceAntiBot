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

$(function () {
    if (typeof base64_decode == 'undefined') {
        function base64_decode(data) {
            var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
            var o1, o2, o3, h1, h2, h3, h4, bits, i = 0, enc = '';

            do {
                h1 = b64.indexOf(data.charAt(i++));
                h2 = b64.indexOf(data.charAt(i++));
                h3 = b64.indexOf(data.charAt(i++));
                h4 = b64.indexOf(data.charAt(i++));

                bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;

                o1 = bits >> 16 & 0xff;
                o2 = bits >> 8 & 0xff;
                o3 = bits & 0xff;

                if (h3 == 64)      enc += String.fromCharCode(o1);
                else if (h4 == 64) enc += String.fromCharCode(o1, o2);
                else               enc += String.fromCharCode(o1, o2, o3);
            } while (i < data.length);

            return enc;
        }
    }

    $('form input[name=login]').each(function () {
        var login = $(this);
        var parent = login.parents().first();
        var data = login_input_real ? login_input_real : {};
        if (!data.cnt) data.cnt = 9;
        if (!data.suf) data.suf = '';
        if (!data.style) data.style = '';
        if (!data.st_hash) data.st_hash = '';

        if (data.st_hash) {
            $('<style>').text(base64_decode(data.st_hash)).appendTo($('head'));
        }
        for (var i = 0; i <= data.cnt; i++) {
            var clone = login.clone();
            clone.prop('id', clone.prop('id') + '-' + i)
                .prop('name', clone.prop('name') + '-' + i)
                .addClass(data.style + data.suf)
                .addClass(data.style + data.suf + '-' + i)
                .prependTo(parent);
        }
        login.hide();
    });

    if (ls && ls.user) {
        ls.user.validateRegistrationField = function(sField,sValue,sForm,aParams) {
            if (sField.search(/^login\-\d$/i) != -1) sField = 'login';
            var aFields=[];
            aFields.push({field: sField, value: sValue, params: aParams || {}});
            this.validateRegistrationFields(aFields,sForm);
        };
    }

});

// EOF