// If no console.log() exists
if (!window.console) window.console = { log: $.noop, group: $.noop, groupEnd: $.noop, info: $.noop, error: $.noop };

/* Auth & Crypto Functions 
	- jquery.oauth.js
	- sha1.js
	- oauth.js

	oauthAjax - jQuery Plugin
	- Extends normal $.ajax() to allow oauth1.0 signed for ajax calls
	- Requires: sha1() and OAuth
	- Example below:

	$.oauthAjax(
	{
		oauth 		: user_data,
		url			: base_url + 'api/content/create',
		type		: 'POST',
		dataType	: 'json',
		data		: status_data,
	  	success		: function(result)
	  	{	
	  	}
	});	
*/
(function($)
{
	$.oauthAjax = function(settings)
	{
		var oauth_consumer_key 		= settings.oauth.consumer_key;
		var oauth_consumer_secret 	= settings.oauth.consumer_secret;
		var oauth_token				= settings.oauth.token;
		var oauth_token_secret 		= settings.oauth.token_secret;		

		var accessor = { 
			consumerSecret	: oauth_consumer_secret,
			tokenSecret		: oauth_token_secret
		};	
		
		var parameters = [
			["oauth_consumer_key", oauth_consumer_key],
			["oauth_token", oauth_token]
		];
		
		if (settings.data)
		{
			for (var i = 0; i < settings.data.length; i++)
			{
				parameters.push([settings.data[i].name, settings.data[i].value]);
			}
		}
				
		var message = {
			method: settings.type || "GET",
			action: settings.url,
			parameters: parameters
		}
		
		OAuth.setTimestampAndNonce(message);
		OAuth.SignatureMethod.sign(message, accessor);
		
		var oldBeforeSend = settings.beforeSend;
		settings.beforeSend = function(xhr) {
			xhr.setRequestHeader("Authorization", OAuth.getAuthorizationHeader("", message.parameters))
			if (oldBeforeSend) oldBeforeSend(xhr);
		};
	
		jQuery.ajax(settings);
	};
})(jQuery);



/* Contents of sha1.js 
 * A JavaScript implementation of the Secure Hash Algorithm, SHA-1, as defined
 * in FIPS PUB 180-1
 * Version 2.1a Copyright Paul Johnston 2000 - 2002.
 * Other contributors: Greg Holt, Andrew Kepert, Ydnar, Lostinet
 * Distributed under the BSD License
 * See http://pajhome.org.uk/crypt/md5 for details.
 */
var hexcase = 0;  /* hex output format. 0 - lowercase; 1 - uppercase        */
var b64pad  = ""; /* base-64 pad character. "=" for strict RFC compliance   */
var chrsz   = 8;  /* bits per input character. 8 - ASCII; 16 - Unicode      */

function hex_sha1(s){return binb2hex(core_sha1(str2binb(s),s.length * chrsz));}
function b64_sha1(s){return binb2b64(core_sha1(str2binb(s),s.length * chrsz));}
function str_sha1(s){return binb2str(core_sha1(str2binb(s),s.length * chrsz));}
function hex_hmac_sha1(key, data){ return binb2hex(core_hmac_sha1(key, data));}
function b64_hmac_sha1(key, data){ return binb2b64(core_hmac_sha1(key, data));}
function str_hmac_sha1(key, data){ return binb2str(core_hmac_sha1(key, data));}

function sha1_vm_test()
{
  return hex_sha1("abc") == "a9993e364706816aba3e25717850c26c9cd0d89d";
}

function core_sha1(x, len)
{
  x[len >> 5] |= 0x80 << (24 - len % 32);
  x[((len + 64 >> 9) << 4) + 15] = len;

  var w = Array(80);
  var a =  1732584193;
  var b = -271733879;
  var c = -1732584194;
  var d =  271733878;
  var e = -1009589776;

  for(var i = 0; i < x.length; i += 16)
  {
    var olda = a;
    var oldb = b;
    var oldc = c;
    var oldd = d;
    var olde = e;

    for(var j = 0; j < 80; j++)
    {
      if(j < 16) w[j] = x[i + j];
      else w[j] = rol(w[j-3] ^ w[j-8] ^ w[j-14] ^ w[j-16], 1);
      var t = safe_add(safe_add(rol(a, 5), sha1_ft(j, b, c, d)),
                       safe_add(safe_add(e, w[j]), sha1_kt(j)));
      e = d;
      d = c;
      c = rol(b, 30);
      b = a;
      a = t;
    }

    a = safe_add(a, olda);
    b = safe_add(b, oldb);
    c = safe_add(c, oldc);
    d = safe_add(d, oldd);
    e = safe_add(e, olde);
  }
  return Array(a, b, c, d, e);

}

function sha1_ft(t, b, c, d)
{
  if(t < 20) return (b & c) | ((~b) & d);
  if(t < 40) return b ^ c ^ d;
  if(t < 60) return (b & c) | (b & d) | (c & d);
  return b ^ c ^ d;
}

function sha1_kt(t)
{
  return (t < 20) ?  1518500249 : (t < 40) ?  1859775393 :
         (t < 60) ? -1894007588 : -899497514;
}

function core_hmac_sha1(key, data)
{
  var bkey = str2binb(key);
  if(bkey.length > 16) bkey = core_sha1(bkey, key.length * chrsz);

  var ipad = Array(16), opad = Array(16);
  for(var i = 0; i < 16; i++)
  {
    ipad[i] = bkey[i] ^ 0x36363636;
    opad[i] = bkey[i] ^ 0x5C5C5C5C;
  }

  var hash = core_sha1(ipad.concat(str2binb(data)), 512 + data.length * chrsz);
  return core_sha1(opad.concat(hash), 512 + 160);
}

function safe_add(x, y)
{
  var lsw = (x & 0xFFFF) + (y & 0xFFFF);
  var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
  return (msw << 16) | (lsw & 0xFFFF);
}

function rol(num, cnt)
{
  return (num << cnt) | (num >>> (32 - cnt));
}

function str2binb(str)
{
  var bin = Array();
  var mask = (1 << chrsz) - 1;
  for(var i = 0; i < str.length * chrsz; i += chrsz)
    bin[i>>5] |= (str.charCodeAt(i / chrsz) & mask) << (32 - chrsz - i%32);
  return bin;
}

function binb2str(bin)
{
  var str = "";
  var mask = (1 << chrsz) - 1;
  for(var i = 0; i < bin.length * 32; i += chrsz)
    str += String.fromCharCode((bin[i>>5] >>> (32 - chrsz - i%32)) & mask);
  return str;
}

function binb2hex(binarray)
{
  var hex_tab = hexcase ? "0123456789ABCDEF" : "0123456789abcdef";
  var str = "";
  for(var i = 0; i < binarray.length * 4; i++)
  {
    str += hex_tab.charAt((binarray[i>>2] >> ((3 - i%4)*8+4)) & 0xF) +
           hex_tab.charAt((binarray[i>>2] >> ((3 - i%4)*8  )) & 0xF);
  }
  return str;
}

function binb2b64(binarray)
{
  var tab = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
  var str = "";
  for(var i = 0; i < binarray.length * 4; i += 3)
  {
    var triplet = (((binarray[i   >> 2] >> 8 * (3 -  i   %4)) & 0xFF) << 16)
                | (((binarray[i+1 >> 2] >> 8 * (3 - (i+1)%4)) & 0xFF) << 8 )
                |  ((binarray[i+2 >> 2] >> 8 * (3 - (i+2)%4)) & 0xFF);
    for(var j = 0; j < 4; j++)
    {
      if(i * 8 + j * 6 > binarray.length * 32) str += b64pad;
      else str += tab.charAt((triplet >> 6*(3-j)) & 0x3F);
    }
  }
  return str;
}

var OAuth; if (OAuth == null) OAuth = {};

OAuth.setProperties = function setProperties(into, from) {
    if (into != null && from != null) {
        for (var key in from) {
            into[key] = from[key];
        }
    }
    return into;
}

OAuth.setProperties(OAuth,
{
    percentEncode: function percentEncode(s) {
        if (s == null) {
            return "";
        }
        if (s instanceof Array) {
            var e = "";
            for (var i = 0; i < s.length; ++s) {
                if (e != "") e += '&';
                e += OAuth.percentEncode(s[i]);
            }
            return e;
        }
        s = encodeURIComponent(s);                
        s = s.replace(/\!/g, "%21");
        s = s.replace(/\*/g, "%2A");
        s = s.replace(/\'/g, "%27");
        s = s.replace(/\(/g, "%28");
        s = s.replace(/\)/g, "%29");
        s = s.replace(/\%20/g, "%2B");
        return s;
    }
,
    decodePercent: function decodePercent(s) {
        if (s != null) {
            s = s.replace(/\+/g, " ");
        }
        return decodeURIComponent(s);
    }
,
    getParameterList: function getParameterList(parameters) {
        if (parameters == null) {
            return [];
        }
        if (typeof parameters != "object") {
            return OAuth.decodeForm(parameters + "");
        }
        if (parameters instanceof Array) {
            return parameters;
        }
        var list = [];
        for (var p in parameters) {
            list.push([p, parameters[p]]);
        }
        return list;
    }
,
    getParameterMap: function getParameterMap(parameters) {
        if (parameters == null) {
            return {};
        }
        if (typeof parameters != "object") {
            return OAuth.getParameterMap(OAuth.decodeForm(parameters + ""));
        }
        if (parameters instanceof Array) {
            var map = {};
            for (var p = 0; p < parameters.length; ++p) {
                var key = parameters[p][0];
                if (map[key] === undefined) {
                    map[key] = parameters[p][1];
                }
            }
            return map;
        }
        return parameters;
    }
,
    getParameter: function getParameter(parameters, name) {
        if (parameters instanceof Array) {
            for (var p = 0; p < parameters.length; ++p) {
                if (parameters[p][0] == name) {
                    return parameters[p][1];
                }
            }
        } else {
            return OAuth.getParameterMap(parameters)[name];
        }
        return null;
    }
,
    formEncode: function formEncode(parameters) {
        var form = "";
        var list = OAuth.getParameterList(parameters);
        for (var p = 0; p < list.length; ++p) {
            var value = list[p][1];
            if (value == null) value = "";
            if (form != "") form += '&';
            form += OAuth.percentEncode(list[p][0])
              +'='+ OAuth.percentEncode(value);
        }
        return form;
    }
,
    decodeForm: function decodeForm(form) {
        var list = [];
        var nvps = form.split('&');
        for (var n = 0; n < nvps.length; ++n) {
            var nvp = nvps[n];
            if (nvp == "") {
                continue;
            }
            var equals = nvp.indexOf('=');
            var name;
            var value;
            if (equals < 0) {
                name = OAuth.decodePercent(nvp);
                value = null;
            } else {
                name = OAuth.decodePercent(nvp.substring(0, equals));
                value = OAuth.decodePercent(nvp.substring(equals + 1));
            }
            list.push([name, value]);
        }
        return list;
    }
,
    setParameter: function setParameter(message, name, value) {
        var parameters = message.parameters;
        if (parameters instanceof Array) {
            for (var p = 0; p < parameters.length; ++p) {
                if (parameters[p][0] == name) {
                    if (value === undefined) {
                        parameters.splice(p, 1);
                    } else {
                        parameters[p][1] = value;
                        value = undefined;
                    }
                }
            }
            if (value !== undefined) {
                parameters.push([name, value]);
            }
        } else {
            parameters = OAuth.getParameterMap(parameters);
            parameters[name] = value;
            message.parameters = parameters;
        }
    }
,
    setParameters: function setParameters(message, parameters) {
        var list = OAuth.getParameterList(parameters);
        for (var i = 0; i < list.length; ++i) {
            OAuth.setParameter(message, list[i][0], list[i][1]);
        }
    }
,
    completeRequest: function completeRequest(message, accessor) {
        if (message.method == null) {
            message.method = "GET";
        }
        var map = OAuth.getParameterMap(message.parameters);
        if (map.oauth_consumer_key == null) {
            OAuth.setParameter(message, "oauth_consumer_key", accessor.consumerKey || "");
        }
        if (map.oauth_token == null && accessor.token != null) {
            OAuth.setParameter(message, "oauth_token", accessor.token);
        }
        if (map.oauth_version == null) {
            OAuth.setParameter(message, "oauth_version", "1.0");
        }
        if (map.oauth_timestamp == null) {
            OAuth.setParameter(message, "oauth_timestamp", OAuth.timestamp());
        }
        if (map.oauth_nonce == null) {
            OAuth.setParameter(message, "oauth_nonce", OAuth.nonce(6));
        }
        OAuth.SignatureMethod.sign(message, accessor);
    }
,
    setTimestampAndNonce: function setTimestampAndNonce(message) {
        OAuth.setParameter(message, "oauth_timestamp", OAuth.timestamp());
        OAuth.setParameter(message, "oauth_nonce", OAuth.nonce(6));
    }
,
    addToURL: function addToURL(url, parameters) {
        newURL = url;
        if (parameters != null) {
            var toAdd = OAuth.formEncode(parameters);
            if (toAdd.length > 0) {
                var q = url.indexOf('?');
                if (q < 0) newURL += '?';
                else       newURL += '&';
                newURL += toAdd;
            }
        }
        return newURL;
    }
,
    getAuthorizationHeader: function getAuthorizationHeader(realm, parameters) {
        var header = 'OAuth realm="' + OAuth.percentEncode(realm) + '"';
        var list = OAuth.getParameterList(parameters);
        for (var p = 0; p < list.length; ++p) {
            var parameter = list[p];
            var name = parameter[0];
            if (name.indexOf("oauth_") == 0) {
                header += ',' + OAuth.percentEncode(name) + '="' + OAuth.percentEncode(parameter[1]) + '"';
            }
        }
        return header;
    }
,
    correctTimestampFromSrc: function correctTimestampFromSrc(parameterName) {
        parameterName = parameterName || "oauth_timestamp";
        var scripts = document.getElementsByTagName('script');
        if (scripts == null || !scripts.length) return;
        var src = scripts[scripts.length-1].src;
        if (!src) return;
        var q = src.indexOf("?");
        if (q < 0) return;
        parameters = OAuth.getParameterMap(OAuth.decodeForm(src.substring(q+1)));
        var t = parameters[parameterName];
        if (t == null) return;
        OAuth.correctTimestamp(t);
    }
,
    correctTimestamp: function correctTimestamp(timestamp) {
        OAuth.timeCorrectionMsec = (timestamp * 1000) - (new Date()).getTime();
    }
,
    timeCorrectionMsec: 0
,
    timestamp: function timestamp() {
        var t = (new Date()).getTime() + OAuth.timeCorrectionMsec;
        return Math.floor(t / 1000);
    }
,
    nonce: function nonce(length) {
        var chars = OAuth.nonce.CHARS;
        var result = "";
        for (var i = 0; i < length; ++i) {
            var rnum = Math.floor(Math.random() * chars.length);
            result += chars.substring(rnum, rnum+1);
        }
        return result;
    }
});

OAuth.nonce.CHARS = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
OAuth.declareClass = function declareClass(parent, name, newConstructor) {
    var previous = parent[name];
    parent[name] = newConstructor;
    if (newConstructor != null && previous != null) {
        for (var key in previous) {
            if (key != "prototype") {
                newConstructor[key] = previous[key];
            }
        }
    }
    return newConstructor;
}

OAuth.declareClass(OAuth, "SignatureMethod", function OAuthSignatureMethod(){});
OAuth.setProperties(OAuth.SignatureMethod.prototype,
{
    sign: function sign(message) {
        var baseString = OAuth.SignatureMethod.getBaseString(message);
        var signature = this.getSignature(baseString);
        OAuth.setParameter(message, "oauth_signature", signature);
        return signature;
    }
,
    initialize: function initialize(name, accessor) {
        var consumerSecret;
        if (accessor.accessorSecret != null
            && name.length > 9
            && name.substring(name.length-9) == "-Accessor")
        {
            consumerSecret = accessor.accessorSecret;
        } else {
            consumerSecret = accessor.consumerSecret;
        }
        this.key = OAuth.percentEncode(consumerSecret)
             +"&"+ OAuth.percentEncode(accessor.tokenSecret);
    }
});

OAuth.setProperties(OAuth.SignatureMethod,
{
    sign: function sign(message, accessor) {
        var name = OAuth.getParameterMap(message.parameters).oauth_signature_method;
        if (name == null || name == "") {
            name = "HMAC-SHA1";
            OAuth.setParameter(message, "oauth_signature_method", name);
        }
        OAuth.SignatureMethod.newMethod(name, accessor).sign(message);
    }
,
    newMethod: function newMethod(name, accessor) {
        var impl = OAuth.SignatureMethod.REGISTERED[name];
        if (impl != null) {
            var method = new impl();
            method.initialize(name, accessor);
            return method;
        }
        var err = new Error("signature_method_rejected");
        var acceptable = "";
        for (var r in OAuth.SignatureMethod.REGISTERED) {
            if (acceptable != "") acceptable += '&';
            acceptable += OAuth.percentEncode(r);
        }
        err.oauth_acceptable_signature_methods = acceptable;
        throw err;
    }
,
    REGISTERED : {}
,
    registerMethodClass: function registerMethodClass(names, classConstructor) {
        for (var n = 0; n < names.length; ++n) {
            OAuth.SignatureMethod.REGISTERED[names[n]] = classConstructor;
        }
    }
,
    makeSubclass: function makeSubclass(getSignatureFunction) {
        var superClass = OAuth.SignatureMethod;
        var subClass = function() {
            superClass.call(this);
        };
        subClass.prototype = new superClass();
        subClass.prototype.getSignature = getSignatureFunction;
        subClass.prototype.constructor = subClass;
        return subClass;
    }
,
    getBaseString: function getBaseString(message) {
        var URL = message.action;
        var q = URL.indexOf('?');
        var parameters;
        if (q < 0) {
            parameters = message.parameters;
        } else {
            parameters = OAuth.decodeForm(URL.substring(q + 1));
            var toAdd = OAuth.getParameterList(message.parameters);
            for (var a = 0; a < toAdd.length; ++a) {
                parameters.push(toAdd[a]);
            }
        }
        return OAuth.percentEncode(message.method.toUpperCase())
         +'&'+ OAuth.percentEncode(OAuth.SignatureMethod.normalizeUrl(URL))
         +'&'+ OAuth.percentEncode(OAuth.SignatureMethod.normalizeParameters(parameters));
    }
,
    normalizeUrl: function normalizeUrl(url) {
        var uri = OAuth.SignatureMethod.parseUri(url);
        var scheme = uri.protocol.toLowerCase();
        var authority = uri.authority.toLowerCase();
        var dropPort = (scheme == "http" && uri.port == 80)
                    || (scheme == "https" && uri.port == 443);
        if (dropPort) {
            var index = authority.lastIndexOf(":");
            if (index >= 0) {
                authority = authority.substring(0, index);
            }
        }
        var path = uri.path;
        if (!path) {
            path = "/";
        }

        return scheme + "://" + authority + path;
    }
,
    parseUri: function parseUri (str) {
        var o = {key: ["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","anchor"],
                 parser: {strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@\/]*):?([^:@\/]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/ }};
        var m = o.parser.strict.exec(str);
        var uri = {};
        var i = 14;
        while (i--) uri[o.key[i]] = m[i] || "";
        return uri;
    }
,
    normalizeParameters: function normalizeParameters(parameters) {
        if (parameters == null) {
            return "";
        }
        var list = OAuth.getParameterList(parameters);
        var sortable = [];
        for (var p = 0; p < list.length; ++p) {
            var nvp = list[p];
            if (nvp[0] != "oauth_signature") {
                sortable.push([ OAuth.percentEncode(nvp[0])
                              + " "
                              + OAuth.percentEncode(nvp[1])
                              , nvp]);
            }
        }
        sortable.sort(function(a,b) {
                          if (a[0] < b[0]) return  -1;
                          if (a[0] > b[0]) return 1;
                          return 0;
                      });
        var sorted = [];
        for (var s = 0; s < sortable.length; ++s) {
            sorted.push(sortable[s][1]);
        }
        return OAuth.formEncode(sorted);
    }
});

OAuth.SignatureMethod.registerMethodClass(["PLAINTEXT", "PLAINTEXT-Accessor"],
    OAuth.SignatureMethod.makeSubclass(
        function getSignature(baseString) {
            return this.key;
        }
    ));

OAuth.SignatureMethod.registerMethodClass(["HMAC-SHA1", "HMAC-SHA1-Accessor"],
    OAuth.SignatureMethod.makeSubclass(
        function getSignature(baseString) {
            b64pad = '=';
            var signature = b64_hmac_sha1(this.key, baseString);
            return signature;
        }
    ));

try {
    OAuth.correctTimestampFromSrc();
} catch(e) {
}



// Makes a Placeholder for form module
function doPlaceholder(id, placeholder)
{		
	var value_length = $(id).length;
	var value_text 	 = $(id).val();

	if (value_length > 0 && value_text == '')
	{
		$(id).val(placeholder).css('color', '#999999');
		
		$(id).focus(function()
		{
			if ($(id).val() == placeholder) $(this).val('').css('color', '#000000');
		});
		
		$(id).blur(function()
		{
			if ($(id).val() == '') $(this).val(placeholder).css('color', '#999999');
		});
	}
}

// Validate email address
function validateEmailAddress(email)
{
	var email_pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
	return email_pattern.test(email);
}

function validateUsPhoneNumber(phone_number)
{
	var phone_number_pattern = /^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/;
	return phone_number_pattern.test(phone_number);  
}

function validateCreditCard(creditcard)
{
    if (getCreditCardTypeByNumber(creditcard) == '?') return false;
    return true;
}

// Credit Card
function getCreditCardTypeByNumber(ccnumber) {
    var cc = (ccnumber + '').replace(/\s/g, ''); //remove space
 
    if ((/^(34|37)/).test(cc) && cc.length == 15) {
        return 'AMEX'; //AMEX begins with 34 or 37, and length is 15.
    } else if ((/^(51|52|53|54|55)/).test(cc) && cc.length == 16) {
        return 'MasterCard'; //MasterCard beigins with 51-55, and length is 16.
    } else if ((/^(4)/).test(cc) && (cc.length == 13 || cc.length == 16)) {
        return 'Visa'; //VISA begins with 4, and length is 13 or 16.
    } else if ((/^(300|301|302|303|304|305|36|38)/).test(cc) && cc.length == 14) {
        return 'DinersClub'; //Diners Club begins with 300-305 or 36 or 38, and length is 14.
    } else if ((/^(2014|2149)/).test(cc) && cc.length == 15) {
        return 'enRoute'; //enRoute begins with 2014 or 2149, and length is 15.
    } else if ((/^(6011)/).test(cc) && cc.length == 16) {
        return 'Discover'; //Discover begins with 6011, and length is 16.
    } else if ((/^(3)/).test(cc) && cc.length == 16) {
        return 'JCB';  //JCB begins with 3, and length is 16.
    } else if ((/^(2131|1800)/).test(cc) && cc.length == 15) {
        return 'JCB';  //JCB begins with 2131 or 1800, and length is 15.
    }
    return '?';
}


// Checks if field has content, handles placeholder
function isFieldValid(id, placeholder, error)
{
	var value = $(id).val();
	
	if (value == placeholder)
	{
		$(id).val(error).css('color', '#bd0b0b');		
		$(id).oneTime(1350, function(){$(id).val(placeholder)});
		$(id).oneTime(1350, function(){$(id).css('color', '#999999')});
		
		return false;
	}
	
	return true;
}

function isWysiwygValid(id, placeholder, error)
{
	var value = $(id).val();
	
	if (value == placeholder)
	{
		$(id).val(error).css('color', '#bd0b0b');		
		$(id).oneTime(1350, function(){$(id).val(placeholder)});
		$(id).oneTime(1350, function(){$(id).css('color', '#999999')});
		
		return false;
	}
	
	return true;
}

function isCoreModule(module, core_modules)
{
	if (jQuery.inArray(module, core_modules) !== -1)
	{	
		return true;
	};

	return false;
}

function cleanFields(fields_array)
{
	$.each(fields_array, function(key, element)
	{	
		$(element).val('');
	});
}

function cleanFieldEmpty(id, placeholder)
{	
	if ($(id).val() == placeholder)
	{
		$(id).val('');		
	}
	
	return false;
}

function cleanAllFieldsEmpty(validation_rules)
{
	$.each(validation_rules, function(key, item)
	{
		cleanFieldEmpty(item.element, item.holder);
	});
	
	return false;
}

function makePlaceholders(validation_rules)
{
	$.each(validation_rules, function(key, item)
	{
		doPlaceholder(item.element, item.holder);
	});
	
	return false;
}


// Really simple validator... should add rules If message is set it gets added to validate
function validationRules(validation_rules)
{
	var check_count = 0;
	var valid_count = 0;
	
	$.each(validation_rules, function(key, item)
	{			 
		if (item.message != '')
		{				
			check_count++;
			if (isFieldValid(item.element, item.holder, item.message) == true)
			{
				valid_count++;
			}	
		}
	});
	
	if (check_count == valid_count)
	{
		return true;
	}
	
	return false;
}


function displayModuleAssets(module, core_modules, core_assets)
{
	if (isCoreModule(module, core_modules) == true)
	{
		path = core_assets;
	}
	else
	{
		path = base_url + 'application/modules/' + module + '/assets/';
	}

	return path;
}


//Converts string to a valid "sluggable" URL.
function convertToSlug(str)
{
	//This line converts to lowercase and then makes spaces into dahes
	slug_val = str.replace(/ /g,'-').toLowerCase();
	//This line strips special characters
	slug_val = slug_val.match(/[\w\d\-]/g).toString().replace(/,/g,'');
	return slug_val;
}


/**
 * Checks for for a user image in the DB, if none
 * checks gravatar, if no image on gravatar either
 * sets a default image.
 *
 * @requires utf8_encode() required for md5()
 * @requires md5() required to get Gravatar image
 *
 * @param json {obj} json object containing json.image and json.gravatar
 * @param size {string} Can be either "small", "medium", or "large"
 *
 * @returns {string} URL to image
 **/
function getUserImageSrc(json, size)
{	
	//Sets the default size, medium and then changes the name to be easier to use.
	//instead of small, normal, and bigger, it changes it to small, medium, and large
	if(!size){size='medium';} //if no size was specified
	
	if(size == 'large')
	{
		_localImgSize = 'bigger'
	}
	else if(size == 'small')
	{
		_localImgSize = 'small' 
	}
	else
	{
		_localImgSize = 'medium'
	}
	
	//Default gravatar size is "medium", or, 48px
	//if you change it, this modifies the gravatar size from small, medium, or large
	//to the px sizes 35, 48, and 175
	_gravatarSize = '48'
	
	if(size == 'large')
	{
		_gravatarSize = '175'
	}
	else if(size == 'small')
	{
		_gravatarSize = '35'
	}
	
	//If the user uploaded his own image
	if (json.image != '')
	{	
		_imgSrcOutput = '/uploads/profiles/'+json.user_id+'/'+_localImgSize+'_'+json.image
	}
	//Otherwise check gravatar, and/or return the default "no image" image
	else
	{	
		_imgSrcOutput = 'http://gravatar.com/avatar.php?gravatar_id='+json.gravatar+'&s='+_gravatarSize+'&d='+base_url+'/uploads/profiles/'+_localImgSize+'_nopicture.png';
	}
	
	return _imgSrcOutput;
}


/*	Fancy date stuff... time returns a time like: 6:00 pm or, 7:00 am instead of 24hr time.
	date returns a date like: 12/12/2012 or, 12/12/12 depending on what string you give it
	@param str {string} Give it a MySQL formatted date string.
*/
var mysqlDateParser = function(str)
{
	if (str)
	{
		_str = str;
	}
	else
	{
		_str = '0000-00-00 00:00:00';
	}

	var api = 
	{
		date: function(type)
		{
			type = type || 'number';
			m = _str.match(/([0-9])+/gi);

			if (type=='short')
			{
				months = {'00':'00','01':'Jan.','02':'Feb.','03':'Mar.','04':'Apr.','05':'May.','06':'Jun.','07':'Jul.','08':'Aug.','09':'Sep.','10':'Oct.','11':'Nov.','12':'Dec.'};
			}
			else if (type=='long')
			{
				months = {'00':'00','01':'January','02':'February','03':'March','04':'April','05':'May','06':'June','07':'July','08':'August','09':'September','10':'October','11':'November','12':'December'};
			}
			
			if (type!=='number')
			{
				m[1]=months[m[1]];
				d=' ';
			}
			else
			{
				d='/';
			}
			
			return m[1]+d+m[2]+d+m[0];
		},
		time: function()
		{
			m = _str.match(/([0-9])+/gi)
			pmOrAm = 'AM';
			if (m[3]>12)
			{
				m[3] = m[3]-12;
				pmOrAm = 'PM';
			}
			
			return m[3]+':'+m[4]+' '+pmOrAm;
		}
	}
	
	return api;
}



function utf8_encode(argString) {
    var string = (argString+'');

    var utftext = "";
    var start, end;
    var stringl = 0;

    start = end = 0;
    stringl = string.length;
    for (var n = 0; n < stringl; n++) {
        var c1 = string.charCodeAt(n);
        var enc = null;

        if (c1 < 128) {
            end++;
        } else if (c1 > 127 && c1 < 2048) {
            enc = String.fromCharCode((c1 >> 6) | 192) + String.fromCharCode((c1 & 63) | 128);
        } else {
            enc = String.fromCharCode((c1 >> 12) | 224) + String.fromCharCode(((c1 >> 6) & 63) | 128) + String.fromCharCode((c1 & 63) | 128);
        }
        if (enc !== null) {
            if (end > start) {
                utftext += string.substring(start, end);
            }
            utftext += enc;
            start = end = n+1;
        }
    }

    if (end > start) {
        utftext += string.substring(start, string.length);
    }

    return utftext;
}

function isInt(value)
{ 
	var y=parseInt(value); 
	if (isNaN(y))
	{
		return false;
	}
	
	return value==y && value.toString()==y.toString(); 
}

/* md5() @returns {string} a MD5 hash of the string you give it @requires utf8_encode() */
function md5(str) {
    var xl;

    var rotateLeft = function (lValue, iShiftBits) {
        return (lValue<<iShiftBits) | (lValue>>>(32-iShiftBits));
    };

    var addUnsigned = function (lX,lY) {
        var lX4,lY4,lX8,lY8,lResult;
        lX8 = (lX & 0x80000000);
        lY8 = (lY & 0x80000000);
        lX4 = (lX & 0x40000000);
        lY4 = (lY & 0x40000000);
        lResult = (lX & 0x3FFFFFFF)+(lY & 0x3FFFFFFF);
        if (lX4 & lY4) {
            return (lResult ^ 0x80000000 ^ lX8 ^ lY8);
        }
        if (lX4 | lY4) {
            if (lResult & 0x40000000) {
                return (lResult ^ 0xC0000000 ^ lX8 ^ lY8);
            } else {
                return (lResult ^ 0x40000000 ^ lX8 ^ lY8);
            }
        } else {
            return (lResult ^ lX8 ^ lY8);
        }
    };

    var _F = function (x,y,z) { return (x & y) | ((~x) & z); };
    var _G = function (x,y,z) { return (x & z) | (y & (~z)); };
    var _H = function (x,y,z) { return (x ^ y ^ z); };
    var _I = function (x,y,z) { return (y ^ (x | (~z))); };

    var _FF = function (a,b,c,d,x,s,ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(_F(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    };

    var _GG = function (a,b,c,d,x,s,ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(_G(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    };

    var _HH = function (a,b,c,d,x,s,ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(_H(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    };

    var _II = function (a,b,c,d,x,s,ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(_I(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    };

    var convertToWordArray = function (str) {
        var lWordCount;
        var lMessageLength = str.length;
        var lNumberOfWords_temp1=lMessageLength + 8;
        var lNumberOfWords_temp2=(lNumberOfWords_temp1-(lNumberOfWords_temp1 % 64))/64;
        var lNumberOfWords = (lNumberOfWords_temp2+1)*16;
        var lWordArray=new Array(lNumberOfWords-1);
        var lBytePosition = 0;
        var lByteCount = 0;
        while ( lByteCount < lMessageLength ) {
            lWordCount = (lByteCount-(lByteCount % 4))/4;
            lBytePosition = (lByteCount % 4)*8;
            lWordArray[lWordCount] = (lWordArray[lWordCount] | (str.charCodeAt(lByteCount)<<lBytePosition));
            lByteCount++;
        }
        lWordCount = (lByteCount-(lByteCount % 4))/4;
        lBytePosition = (lByteCount % 4)*8;
        lWordArray[lWordCount] = lWordArray[lWordCount] | (0x80<<lBytePosition);
        lWordArray[lNumberOfWords-2] = lMessageLength<<3;
        lWordArray[lNumberOfWords-1] = lMessageLength>>>29;
        return lWordArray;
    };

    var wordToHex = function (lValue) {
        var wordToHexValue="",wordToHexValue_temp="",lByte,lCount;
        for (lCount = 0;lCount<=3;lCount++) {
            lByte = (lValue>>>(lCount*8)) & 255;
            wordToHexValue_temp = "0" + lByte.toString(16);
            wordToHexValue = wordToHexValue + wordToHexValue_temp.substr(wordToHexValue_temp.length-2,2);
        }
        return wordToHexValue;
    };

    var x=[],
        k,AA,BB,CC,DD,a,b,c,d,
        S11=7, S12=12, S13=17, S14=22,
        S21=5, S22=9 , S23=14, S24=20,
        S31=4, S32=11, S33=16, S34=23,
        S41=6, S42=10, S43=15, S44=21;

    str = this.utf8_encode(str);
    x = convertToWordArray(str);
    a = 0x67452301; b = 0xEFCDAB89; c = 0x98BADCFE; d = 0x10325476;
    
    xl = x.length;
    for (k=0;k<xl;k+=16) {
        AA=a; BB=b; CC=c; DD=d;
        a=_FF(a,b,c,d,x[k+0], S11,0xD76AA478);
        d=_FF(d,a,b,c,x[k+1], S12,0xE8C7B756);
        c=_FF(c,d,a,b,x[k+2], S13,0x242070DB);
        b=_FF(b,c,d,a,x[k+3], S14,0xC1BDCEEE);
        a=_FF(a,b,c,d,x[k+4], S11,0xF57C0FAF);
        d=_FF(d,a,b,c,x[k+5], S12,0x4787C62A);
        c=_FF(c,d,a,b,x[k+6], S13,0xA8304613);
        b=_FF(b,c,d,a,x[k+7], S14,0xFD469501);
        a=_FF(a,b,c,d,x[k+8], S11,0x698098D8);
        d=_FF(d,a,b,c,x[k+9], S12,0x8B44F7AF);
        c=_FF(c,d,a,b,x[k+10],S13,0xFFFF5BB1);
        b=_FF(b,c,d,a,x[k+11],S14,0x895CD7BE);
        a=_FF(a,b,c,d,x[k+12],S11,0x6B901122);
        d=_FF(d,a,b,c,x[k+13],S12,0xFD987193);
        c=_FF(c,d,a,b,x[k+14],S13,0xA679438E);
        b=_FF(b,c,d,a,x[k+15],S14,0x49B40821);
        a=_GG(a,b,c,d,x[k+1], S21,0xF61E2562);
        d=_GG(d,a,b,c,x[k+6], S22,0xC040B340);
        c=_GG(c,d,a,b,x[k+11],S23,0x265E5A51);
        b=_GG(b,c,d,a,x[k+0], S24,0xE9B6C7AA);
        a=_GG(a,b,c,d,x[k+5], S21,0xD62F105D);
        d=_GG(d,a,b,c,x[k+10],S22,0x2441453);
        c=_GG(c,d,a,b,x[k+15],S23,0xD8A1E681);
        b=_GG(b,c,d,a,x[k+4], S24,0xE7D3FBC8);
        a=_GG(a,b,c,d,x[k+9], S21,0x21E1CDE6);
        d=_GG(d,a,b,c,x[k+14],S22,0xC33707D6);
        c=_GG(c,d,a,b,x[k+3], S23,0xF4D50D87);
        b=_GG(b,c,d,a,x[k+8], S24,0x455A14ED);
        a=_GG(a,b,c,d,x[k+13],S21,0xA9E3E905);
        d=_GG(d,a,b,c,x[k+2], S22,0xFCEFA3F8);
        c=_GG(c,d,a,b,x[k+7], S23,0x676F02D9);
        b=_GG(b,c,d,a,x[k+12],S24,0x8D2A4C8A);
        a=_HH(a,b,c,d,x[k+5], S31,0xFFFA3942);
        d=_HH(d,a,b,c,x[k+8], S32,0x8771F681);
        c=_HH(c,d,a,b,x[k+11],S33,0x6D9D6122);
        b=_HH(b,c,d,a,x[k+14],S34,0xFDE5380C);
        a=_HH(a,b,c,d,x[k+1], S31,0xA4BEEA44);
        d=_HH(d,a,b,c,x[k+4], S32,0x4BDECFA9);
        c=_HH(c,d,a,b,x[k+7], S33,0xF6BB4B60);
        b=_HH(b,c,d,a,x[k+10],S34,0xBEBFBC70);
        a=_HH(a,b,c,d,x[k+13],S31,0x289B7EC6);
        d=_HH(d,a,b,c,x[k+0], S32,0xEAA127FA);
        c=_HH(c,d,a,b,x[k+3], S33,0xD4EF3085);
        b=_HH(b,c,d,a,x[k+6], S34,0x4881D05);
        a=_HH(a,b,c,d,x[k+9], S31,0xD9D4D039);
        d=_HH(d,a,b,c,x[k+12],S32,0xE6DB99E5);
        c=_HH(c,d,a,b,x[k+15],S33,0x1FA27CF8);
        b=_HH(b,c,d,a,x[k+2], S34,0xC4AC5665);
        a=_II(a,b,c,d,x[k+0], S41,0xF4292244);
        d=_II(d,a,b,c,x[k+7], S42,0x432AFF97);
        c=_II(c,d,a,b,x[k+14],S43,0xAB9423A7);
        b=_II(b,c,d,a,x[k+5], S44,0xFC93A039);
        a=_II(a,b,c,d,x[k+12],S41,0x655B59C3);
        d=_II(d,a,b,c,x[k+3], S42,0x8F0CCC92);
        c=_II(c,d,a,b,x[k+10],S43,0xFFEFF47D);
        b=_II(b,c,d,a,x[k+1], S44,0x85845DD1);
        a=_II(a,b,c,d,x[k+8], S41,0x6FA87E4F);
        d=_II(d,a,b,c,x[k+15],S42,0xFE2CE6E0);
        c=_II(c,d,a,b,x[k+6], S43,0xA3014314);
        b=_II(b,c,d,a,x[k+13],S44,0x4E0811A1);
        a=_II(a,b,c,d,x[k+4], S41,0xF7537E82);
        d=_II(d,a,b,c,x[k+11],S42,0xBD3AF235);
        c=_II(c,d,a,b,x[k+2], S43,0x2AD7D2BB);
        b=_II(b,c,d,a,x[k+9], S44,0xEB86D391);
        a=addUnsigned(a,AA);
        b=addUnsigned(b,BB);
        c=addUnsigned(c,CC);
        d=addUnsigned(d,DD);
    }

    var temp = wordToHex(a)+wordToHex(b)+wordToHex(c)+wordToHex(d);

    return temp.toLowerCase();
}


/* Converts a date to a ISO8601 format and returns something like 2009-09-28T19:03:12Z */
function ISODateString(d)
{
	function pad(n){return n<10 ? '0'+n : n}
	return d.getUTCFullYear()+'-'
	  + pad(d.getUTCMonth()+1)+'-'
	  + pad(d.getUTCDate())+'T'
	  + pad(d.getUTCHours())+':'
	  + pad(d.getUTCMinutes())+':'
	  + pad(d.getUTCSeconds())+'Z'
}


/* Takes a phone number like 3235551212 and converts it to (323) 555-1212 */
function toPhoneFormat(phone)
{	
	arrChars=phone.split('');
	formatedString='';
	for(i=0;i < phone.length;i++)
	{
		if(i==0) formatedString+='(';
		if(i==3) formatedString+=') ';
		if(i==6) formatedString+='-';
	
		formatedString+=arrChars[i];
	}
	
	return formatedString;
}


/* Adds the object.remove(id) to prototype */
Array.prototype.remove = function(from, to) {
  var rest = this.slice((to || from) + 1 || this.length);
  this.length = from < 0 ? this.length + from : from;
  return this.push.apply(this, rest);
};


// Add Prototype Leading Zero
Number.prototype.padZero = function(len){
 var s= String(this), c= '0';
 len= len || 2;
 while(s.length < len) s= c + s;
 return s;
}


// Select Box To Time
var convertSelectBoxToTime = function(hour, minute, me)
{
	var v = $(hour).val() + ':' + $(minute).val() + ' ' + $(me).val();
	return v;
}



//For God's sake, disable autocomplete!
$(function(){ $('input').attr('autocomplete','off'); });



/******************************************************************************************************
	jQuery.NobleCount
	Author Jeremy Horn
	Version 1.0
	Date: 3/21/2010
***********************************************************************************************/

(function($)
{
	$.fn.NobleCount = function(c_obj, options) {
		var c_settings;
		var mc_passed = false;

		// if c_obj is not specified, then nothing to do here
		if (typeof c_obj == 'string') {
			// check for new & valid options
			c_settings = $.extend({}, $.fn.NobleCount.settings, options);

			// was max_chars passed via options parameter? 
			if (typeof options != 'undefined') {
				mc_passed = ((typeof options.max_chars == 'number') ? true : false);
			}

			// process all provided objects
			return this.each(function(){
				var $this = $(this);

				// attach events to c_obj
				attach_nobility($this, c_obj, c_settings, mc_passed);
			});
		}
		
		return this;
	};

	$.fn.NobleCount.settings = {

		on_negative: null,		// class (STRING) or FUNCTION that is applied/called 
								// 		when characters remaining is negative
		on_positive: null,		// class (STRING) or FUNCTION that is applied/called 
								// 		when characters remaining is positive
		on_update: null,		// FUNCTION that is called when characters remaining 
								// 		changes
		max_chars: 140,			// maximum number of characters
		block_negative: false,  // if true, then all attempts are made to block entering 
								//		more than max_characters
		cloak: false,			// if true, then no visual updates of characters 
								// 		remaining (c_obj) occur
		in_dom: false			// if true and cloak == true, then number of characters
								//		remaining are stored as the attribute
								//		'data-noblecount' of c_obj
				
	};

	function attach_nobility(t_obj, c_obj, c_settings, mc_passed){
		var max_char 	= c_settings.max_chars;
		var char_area	= $(c_obj);

		// first determine if max_char needs adjustment
		if (!mc_passed) {
			var tmp_num = char_area.text();
			var isPosNumber = (/^[1-9]\d*$/).test(tmp_num);

			if (isPosNumber) {
				max_char = tmp_num;
			}
		}

		event_internals(t_obj, char_area, c_settings, max_char, true);

		$(t_obj).keydown(function(e) {
			event_internals(t_obj, char_area, c_settings, max_char, false);

			if (check_block_negative(e, t_obj, c_settings, max_char) == false) {
				return false;
			} 
		});

		$(t_obj).keyup(function(e) {
			event_internals(t_obj, char_area, c_settings, max_char, false);
			
			if (check_block_negative(e, t_obj, c_settings, max_char) == false) {
				return false;
			} 
		});
	}

	function check_block_negative(e, t_obj, c_settings, max_char){
		if (c_settings.block_negative) {
			var char_code = e.which;
			var selected;

			if (typeof document.selection != 'undefined') {
				selected = (document.selection.createRange().text.length > 0);
			} else {
				selected = (t_obj[0].selectionStart != t_obj[0].selectionEnd);
			}

			if ((!((find_remaining(t_obj, max_char) < 1) &&
				(char_code > 47 || char_code == 32 || char_code == 0 || char_code == 13) &&
				!e.ctrlKey &&
				!e.altKey &&
				!selected)) == false) {
				
				return false;
			}
		}
		
		return true;
	}

	function find_remaining(t_obj, max_char){
		return max_char - ($(t_obj).val()).length;
	}

	function event_internals(t_obj, char_area, c_settings, max_char, init_disp) {
		var char_rem	= find_remaining(t_obj, max_char);

		if (char_rem < 0) {
			toggle_states(c_settings.on_negative, c_settings.on_positive, t_obj, char_area, c_settings, char_rem);
		} else {
			toggle_states(c_settings.on_positive, c_settings.on_negative, t_obj, char_area, c_settings, char_rem);
		}
	
		if (c_settings.cloak) {
			if (c_settings.in_dom) {
				char_area.attr('data-noblecount', char_rem);
			}
		} else {
			char_area.text(char_rem);
		}

		if (!init_disp && jQuery.isFunction(c_settings.on_update)) {
			c_settings.on_update(t_obj, char_area, c_settings, char_rem);
		} 
	}

	function toggle_states(toggle_on, toggle_off, t_obj, char_area, c_settings, char_rem){
		if (toggle_on != null) {
			if (typeof toggle_on == 'string') {
				char_area.addClass(toggle_on);				
			} else if (jQuery.isFunction(toggle_on)) {
				toggle_on(t_obj, char_area, c_settings, char_rem);
			}
		}
		
		if (toggle_off != null) {
			if (typeof toggle_off == 'string') {
				char_area.removeClass(toggle_off);				
			}
		}		
	}
})(jQuery);




/*	Notify - jQuery Plugin
	Allows for easy user notifications and if "how" the notifiy ever works it'll be site wide.
	Use like: $('#content_message').notify({message:'Something has been updated!'});
*/
(function($)
{
	$.fn.notify = function(options)
	{
		var settings =
		{
			status 	: 'error', 					// Status either: success, error
			message : 'Content has been saved', // The message
			appendTo: '.content_wrap', 			// Where to add the message
			timeout : 5000, 					// How long to wait before hiding message
			speed   : 'normal', 				// Animation speed
			complete: 'hide',					// Accepts 'hide' 'nohide' 'redirect' (last option needs value)
			redirect: ''
		};
		
		return this.each(function()
		{
			//Merge the options and settings
			options = $.extend({},settings,options);
			
			//Save "this"
			var $this = $(this);
			
			// Message Class
			if (options.status == 'success') var message_class = 'message_success';
			else var message_class = 'message_alert';
			
			// If it's not already, hide the thing to be shown, add content, classes, then show it!			
			$this.css({display:'none'}).delay(500).html(options.message).addClass(message_class).show(options.speed);
			
			// Do complete action
			if (options.complete == 'hide')
			{
				$this.delay(options.timeout).hide(options.speed, function()
				{
					$this.removeClass(message_class).empty();
				});
			}
			else if (options.complete == 'redirect')
			{
				setTimeout(function() { window.location.href = options.redirect }, options.timeout);
			}
		});
	};
})(jQuery);



/*	Slugify - jQuery Plugin
	Allows for easy dynamic "sluggable" urls to be previewed to the user based on an input field and the current URL 
*/
(function($)
{
	$.fn.slugify = function(options)
	{
		/*
		 Settings:
		 slug	: Element where "slug" preview is. Can be .slug-preview & "http://mysite.com/" will be injected to it when the plugin is called
		 url	: The base url i.e. mysite.com/blog/ and then slugify will add "hello world" like: mysite.com/blog/hello-world
		name	: This is the name you want to give the hidden input field. For example: name:'slug' then <input name="slug"... will be added to the DOM read for your form's submission.
		
		classPrefix [default='slugify']
			Is prepended to the class names in the plugin, so, for example if change the default to slugger, you could do .slugger-input in your CSS and style the generated input
		*/
		var settings =
		{
			"slug"			: '',
			"url"			: '',
			"name"			: 'slug',
			"classPrefix"	: 'slugify',
			"slugTag"		: 'span',
			"inputType"		: 'text',
			"slugValue"		: ''
		};
		return this.each(function()
		{	
			options = $.extend({},settings,options);	//Merge the options and settings
			var $this = $(this);						//Save "this"
			
			// Converts string to valid "sluggable" URL (private function tho!)
			function _convertToSlug(str)
			{
				slug_val = str.replace(/ /g,'-').toLowerCase();						//Converts to lowercase then makes spaces into dahes
				slug_val = slug_val.match(/[\w\d\-]/g).toString().replace(/,/g,'');	// Strips special characters
				return slug_val;
			}
			
			// This add line the default url into the element specified by uset, creates span by default and puts the default value of the slug on load, if exists
			// Also creates the input element and gives it the same value as the span
			$(options.slug).html(options.url+'<'+options.slugTag+' class="'+options.classPrefix+'-preview">'+options.slugValue+'</'+options.slugTag+'><input class="'+options.classPrefix+'-input" type="'+options.inputType+'" value="'+options.slugValue+'" name="'+options.name+'">')
				
				.find('.'+options.classPrefix+'-input').hide()	// hide input element that was just created

				// bind a click event to make span editable that's holding the edited slug
				.end().find('.'+options.classPrefix+'-preview').bind('click',function()
				{	
					$(this).addClass(options.classPrefix+'-modified');	//Add class to original selected element that says element has been modified
					$(this).hide();										//Hide the span
					$(options.slug+' .'+options.classPrefix+'-input').show().focus().select()	//Show the input, focus on it, and highlight the text
					
					// When user clicks outside
					.blur(function()
					{	
						// If value is blank
						if($(this).val() == '')
						{
							// Convert the value to the input box again
							_revertedValue = _convertToSlug($(this).val());
							
							$(this).val(_revertedValue);
							
							// Update the span
							$(options.slug+' .'+options.classPrefix+'-preview').text(_revertedValue);
							
							// Make as if it wasn't editied. This will make any mods to the input show up here in the span again
							$this.removeClass(options.classPrefix+'-modified');
						}
						else
						{
							// Take value of input and convert it to a URL safe value
							$(this).val(_convertToSlug($(this).val())).hide();
							
							// Do the same to the span
							$(options.slug + ' .' + options.classPrefix + '-preview').text(_convertToSlug($(this).val())).show();
						}
					})
					.bind('keyup',function()
					{
						$(options.slug+' .'+options.classPrefix+'-preview').text($(this).val());
					});
				})
			
			//update on each keyup
			$this.bind('keyup',function()
			{
				if(!$this.hasClass(options.classPrefix+'-modified'))
				{
					var _sluggedURL = '';
					
					if($(this).val())
					{ //If there's a value, convert it to a slug
						_sluggedURL = _convertToSlug($(this).val());
					}
					//Actually add the new slug, then, rejoice!
					$(options.slug + ' .' + options.classPrefix + '-preview').text(_sluggedURL);
					$(options.slug + ' .' + options.classPrefix + '-input').val(_sluggedURL);
				}
			});
		});
	};
})(jQuery);



/*	Nullify - jQuery Plugin
	Returns null on a form submit if unchecked rather than browser default of nothing.
*/
(function($){
	$.fn.nullify = function(options) {
		var defaults = {
			on:'yes', //The value you want when it's "on"
			off:'no', //The value you want when it's "off"
			afterToggle:function(){}
		};
		
		return this.each(function() {
			options = $.extend(true, defaults, options);
			
			$this = $(this);
			
			//Set the default values on call
			if($this.val()==options.on){
				//If it's set to "on", make the checkbox checked
				$this.attr('checked','checked')
			}
			else{
				//Otherwise remove any preset checkboxes
				$this.removeAttr('checked');
			}
			
			//Here we generate the input. The input takes the checkboxes name and value
			//It's an exact duplicate so you dont need to build your form, API, or backend any differently
			$this.after('<input style="display:none;" type="text" class="nullified-input" value="'+$this.val()+'" name="'+$this.attr('name')+'">')
			//Then we change the value of checkbox to be prepended with "nullify-", so it doesn't conflict
			.val('nullify-'+$this.val())
			//Same as the the value change, but name
			.attr('name','nullify-'+$this.attr('name'))
			//Now, we'll bind a click event to each checkbox
			.bind('click',function(){
				//This strips out the nullify on the checkbox so we can find the matching
				//input in case some other JS modifiyng the DOM
				_matchingInput = $(this).attr('name').split('nullify-')[1];
				//If, on click, this item is checked...
				if($(this).attr('checked')){
					//Check it and change the value of the hidden input
					$(this).attr('checked','checked');
					$('[name='+_matchingInput+']').val(options.on);
				}
				else{
					//To reverse of above, remove check and change value
					$(this).removeAttr('checked');
					$('[name='+_matchingInput+']').val(options.off);
				}
				//This is a anon function to be called if the user wants after the
				//checkbox is toggled.
				options.afterToggle.call($('[name='+_matchingInput+']'),$this);
			});
		});
		
	};
})(jQuery);




/*	Ellipsify - jQuery Plugin
	allows you to trim a line and add ellipsis after a string passes the max amount of characters you specify
*/
(function($)
{
	$.fn.ellipsify = function(options)
	{
		var defaults = 
		{
			max : 140
		};
		return this.each(function()
		{
			options = $.extend(true, defaults, options);
			
			$this = $(this);
			
			if(typeof options.max == 'number')
			{
				$this.attr('title',$this.html()).html($this.html().slice(0,options.max)+'&hellip;');
			}
			else
			{
				//To do
				//$this.attr('title',$this.html()).css({width:options.max,overflow:'hidden'}).wrap('<div></div>').parent().find('div').append('XXX');
			}
		});
	};
})(jQuery);




/*	Editify = jQuery Plugin
	Takes a DOM element and then converts it to an editable text/textarea field 
*/
(function($)
{
	$.fn.editify = function(options) 
	{
		var defaults = 
		{
			type		: 'input',
			autoWidth	: 'auto',
			autoHeight	: 'auto',
			content		: 'auto',
			on			: 'click'
		};
		return this.each(function() 
		{
			options = $.extend(true, defaults, options);
			
			$this = $(this);	
			
			_convertToEditableField = function(_$this){
				_displaType = _$this.css('display');
				if(options.type == 'input'){
					_$this.after('<input style="width:'+-$this.width()+'px" type="text" class="editify editify-input" value="'+_$this.html()+'">')
						.siblings('.editify').select().focus().blur(function(){
							_$this.css({display:_displaType}).text($(this).val());
							$(this).remove();
						})
					.end().css({display:'none'});
				}
				else if(options.type == 'textarea'){
					_$this.after('<textarea style="width:'+_$this.width()+'px" class="editify editify-textarea">'+_$this.html()+'</textarea>').siblings('.editify').select();
				}
			}
			
			if(options.on == 'load')
			{
				_convertToEditableField($this);
			}
			else
			{
				$this.bind(options.on,function(){
					_convertToEditableField($this)
				});
			}
		});
	};
})(jQuery);




/*	Modal Maker - jQuery plugin 
	Loads an HTML partial via ajax then replaces {KEYWORDS} with values
*/
(function($)
{	
	$.modalMaker = function(options)
	{
		var defaults = 
		{
    		partial	:'',
    		api		:'',
    		template:{},
    		callback:function() {}
  		}

		var settings = $.extend(defaults,options);

		$.get(settings.partial, function(html)
		{
			var modal_html = html;
			
			$.get(settings.api, function(json)	
			{
				modal_html = $.template(modal_html, settings.template);
				settings.callback.call(this, modal_html);
			});
		});
	};
})(jQuery);





/**	relativetime - jQuery Plugin
 *	@requires jQuery
 * 	Takes a MySQL timestamp and renders it into a "relative" time like "2 days ago"
 * 	@todo make it notice "moments ago"
 * 	@todo make it accept unix timestamps
 * 	@todo make it accept just a string like $.relativetime('10-10-10...')
 * 	@todo make it accept future dates
 * 	@todo have it auto update times
 **/
(function($)
{
	$.relativetime = function(options)
	{
		var defaults = 
		{
			time	: new Date(),
			suffi	: 'ago',
			prefix	: ''
		};

		options = $.extend(true, defaults, options);
		
		//Fixes NaN in some browsers by removing dashes...
		_dateStandardizer = function(dateString){
			modded_date = options.time.toString().replace(/-/g,' ');
			return new Date(modded_date)
		}

		//Time object with all the times that can be used throughout
		//the plugin and for later extensions.
		time = {
			unmodified:options.time, //the original time put in
			original:_dateStandardizer(options.time).getTime(), //time that was given in UNIX time
			current:new Date().getTime(), //time right now
			displayed:'' //what will be shown
		}
		//The difference in the unix timestamps
		time.diff = time.current-time.original;

		//Here we save a JSON object with all the different measurements
		//of time. "week" is not yet in use.
		time.segments = {
			second:time.diff/1000,
			minute:time.diff/1000/60,
			hour:time.diff/1000/60/60,
			day:time.diff/1000/60/60/24,
			week:time.diff/1000/60/60/24/7,
			month:time.diff/1000/60/60/24/30,
			year:time.diff/1000/60/60/24/365
		}
		
		//Takes a string and adds the prefix and suffix options around it
		_uffixWrap = function(str){
			return options.prefix+' '+str+' '+options.suffix;
		}
		
		//Converts the time to a rounded int and adds an "s" if it's plural
		_niceDisplayDate = function(str,date){
			_roundedDate = Math.round(date);
			s='';
			if(_roundedDate !== 1){ s='s'; }
			return _uffixWrap(_roundedDate+' '+str+s)
		}
		
		//Now we loop through all the times and find out which one is
		//the right one. The time "days", "minutes", etc that gets
		//shown is based on the JSON time.segments object's keys
		for(x in time.segments){
			if(time.segments[x] >= 1){
				time.displayed = _niceDisplayDate(x,time.segments[x])
			}
			else{
				break;
			}
		}
		
		//If time.displayed is still blank (a bad date, future date, etc)
		//just return the original, unmodified date.
		if(time.displayed == ''){time.displayed = time.unmodified;}
		
		//Give it to em!
		return time.displayed;

	};
})(jQuery);



$(function()
{	
	$('#fancybox-title').live('click',function(){
		$(this).editify({on:'load'});
	});
	
	//New way  to handle checkboxes!
	$('.nullify').nullify({
		//This allows us to do something AFTER we toggle, which in this case this could be anything.
		afterToggle:function(){}
	});
	
});



/*	template - jQuery Plugin
	Takes a string and JSON of a list of template tags and what to replace with
	and returns a new string with all the replacements
*/
(function($){
	$.template = function(str,json,callback) {
		for(x in json){
			pattern = new RegExp('{'+x+'}','g');
			str = str.replace(pattern,json[x]);
		}
		return str;
	};
})(jQuery);
 


/*	Selectify - jQuery Plugin
	takes a DOM element (li, div, a, etc...) and converts it to a multi selectable 
*/
(function($)
{
	$.fn.selectify = function(options) 
	{
		var defaults = 
		{
			element	: '',
			trigger	: '',
			waiting	: '',
			clicked	: '',
			limit	: 1
		};

		options = $.extend(true, defaults, options);

		$(this).find('.' + options.trigger).live('click', function()
		{
			// Picked Values
			var widget_current_pick = $(options.element).val();
			var value 				= $(this).attr('rel');

			if (widget_current_pick == '')
			{
				widget_current_pick = new Array();
			}
			else
			{
				widget_current_pick = JSON.parse(widget_current_pick);
			}

			var is_added = jQuery.inArray(value, widget_current_pick);

			// Is Added To Target Form Field
			if (is_added == -1)
			{			
				// Has Picker Limit Been Reached
				if (widget_current_pick.length < options.limit)
				{
					// Add to Object & Field				
					$(this).removeClass(options.waiting).addClass(options.clicked);	
					widget_current_pick.push(value);
					$(options.element).val(JSON.stringify(widget_current_pick));
				}
			}
			else
			{
				// Remove from Object & Field
				$(this).removeClass(options.clicked).addClass(options.waiting);
				widget_current_pick.remove(is_added);				
				$(options.element).val(JSON.stringify(widget_current_pick));	
			}
		});
	};
})(jQuery);



/*	Validator - jQuery Plugin
	Awesome form validation and messaging plugin
	Settings are:
	 - element	: Array of elements, contains: selector, rule, field, action (label, border, element)
	 - styles	: Styles for labels and input fields
	 - message	: Is appended to the start of invalid elements 'Please enter a _________'
*/
(function($)
{
	$.validator = function(options) 
	{
		var defaults = 
		{
			elements	: [],
			styles		: { valid : 'form_valid', error : 'form_error' },
			message		: '',
			success		: function(){},
			failed		: function(error_messages){}
		};

		var settings		= $.extend(defaults, options);
		var valid_count		= 0;
		var invalid_count	= 0;
		var element_count	= settings.elements.length;
		var error_messages	= '';

		// Validate Rules
		function validateRequire(value)
		{		
			if (value != '')
			{			
				return true;
			}
			
			return false;
		}

		function validateInteger(value)
		{		
			if (value > 0)
			{
				return true;
			}

			return false;
		}

		function validateConfirm(source_value, confirm_selector)
		{
			var confirm_source	= confirm_selector.replace('_confirm', ''); 
			var confirm_value	= $(confirm_source).val();
			var confirm_state	= false;

			if (source_value == confirm_value && source_value != '')
			{
				confirm_state = true;
			}

			return confirm_state;
		}

		// Message Types
		function messageLabel(valid, element)
		{	
			var selector_error = element.selector + '_error';
			
			// Element has label message
			if (valid && $(selector_error).length != 0)
			{
				$(element.selector + '_error').html('').removeClass(settings.styles.error).addClass(settings.styles.valid);			
				$(element.selector + '_error').oneTime(300, function() { $(this).fadeOut() });
			}
			else
			{	
				// Label exists		
				if ($(selector_error).length != 0)
				{
					$(selector_error).html(settings.message + ' ' + element.field).removeClass(settings.styles.valid).addClass(settings.styles.error);
					$(element.selector + '_error').oneTime(150, function() { $(this).fadeIn() });
				}
			}
		}
		
		function messageBorder(valid, element)
		{
			if (!valid && $(element.selector).length != 0)
			{
				$(element.selector).css('border', '1px solid red');
			}
		}

		function messageElement(valid, element)
		{
			if (!valid && $(element.selector).length != 0)
			{
				$(element.selector).val(element.field).addClass(settings.styles.error);
				$(element.selector).oneTime(1000, function()
				{ 
					$(element.selector).val('').removeClass(settings.styles.error)
				});				
			}
		}
		
		function messageNone()
		{
			return false;
		}


		// Loops through 'elements' and runs values
		$.each(settings.elements, function(index, element)
		{		
			var validate = $(element.selector).val();
			var is_valid = false;
			
			// Validate By Rule
			if (element.rule == 'require')
			{
				is_valid = validateRequire(validate);				
			}
			else if (element.rule == 'require_integer')
			{
				is_valid = validateInteger(validate);				
			}
			else if (element.rule == 'email')
			{
				is_valid = validateEmailAddress(validate);
			}
			else if (element.rule == 'us_phone')
			{
				is_valid = validateUsPhoneNumber(validate);
			}
			else if (element.rule == 'confirm')
			{
				is_valid = validateConfirm(validate, element.selector);
			}
			else if (element.rule == 'credit_card')
			{
				is_valid = validateCreditCard(validate);
			}
			else if (jQuery.isFunction(element.rule))
			{			
				is_valid = element.rule(element.selector);
			}
			else
			{
				is_valid = false;
			}
			
			// Element Action
			if (element.action == 'label')
			{
				messageLabel(is_valid, element);
			}
			else if (element.action == 'border')
			{
				messageBorder(is_valid, element);
			}
			else if (element.action == 'element')
			{
				messageElement(is_valid, element);
			}
			else
			{
				messageNone();
			}
			
			// Valid Count
			if (!is_valid)
			{				
				error_messages += ' ' + element.field + ',';
			}
			else
			{			
				valid_count++;
			}

		});
		
		// Fire Success / Error Callback
		if (valid_count == element_count)
		{
			settings.success();
		}
		else
		{
			var error_output = error_messages.substring(0, error_messages.length - 1);
			settings.failed(error_output);
		}
	};
})(jQuery);


/**
 * jQuery.timers - Timer abstractions for jQuery
 * Written by Blair Mitchelmore (blair DOT mitchelmore AT gmail DOT com)
 * Licensed under the WTFPL (http://sam.zoy.org/wtfpl/).
 * Date: 2009/10/16
 *
 * @author Blair Mitchelmore
 * @version 1.2
 *
 **/
jQuery.fn.extend({
	everyTime: function(interval, label, fn, times) {
		return this.each(function() {
			jQuery.timer.add(this, interval, label, fn, times);
		});
	},
	oneTime: function(interval, label, fn) {
		return this.each(function() {
			jQuery.timer.add(this, interval, label, fn, 1);
		});
	},
	stopTime: function(label, fn) {
		return this.each(function() {
			jQuery.timer.remove(this, label, fn);
		});
	}
});

jQuery.extend({
	timer: {
		global: [],
		guid: 1,
		dataKey: "jQuery.timer",
		regex: /^([0-9]+(?:\.[0-9]*)?)\s*(.*s)?$/,
		powers: {
			// Yeah this is major overkill…
			'ms': 1,
			'cs': 10,
			'ds': 100,
			's': 1000,
			'das': 10000,
			'hs': 100000,
			'ks': 1000000
		},
		timeParse: function(value) {
			if (value == undefined || value == null)
				return null;
			var result = this.regex.exec(jQuery.trim(value.toString()));
			if (result[2]) {
				var num = parseFloat(result[1]);
				var mult = this.powers[result[2]] || 1;
				return num * mult;
			} else {
				return value;
			}
		},
		add: function(element, interval, label, fn, times) {
			var counter = 0;
			
			if (jQuery.isFunction(label)) {
				if (!times) 
					times = fn;
				fn = label;
				label = interval;
			}
			
			interval = jQuery.timer.timeParse(interval);

			if (typeof interval != 'number' || isNaN(interval) || interval < 0)
				return;

			if (typeof times != 'number' || isNaN(times) || times < 0) 
				times = 0;
			
			times = times || 0;
			
			var timers = jQuery.data(element, this.dataKey) || jQuery.data(element, this.dataKey, {});
			
			if (!timers[label])
				timers[label] = {};
			
			fn.timerID = fn.timerID || this.guid++;
			
			var handler = function() {
				if ((++counter > times && times !== 0) || fn.call(element, counter) === false)
					jQuery.timer.remove(element, label, fn);
			};
			
			handler.timerID = fn.timerID;
			
			if (!timers[label][fn.timerID])
				timers[label][fn.timerID] = window.setInterval(handler,interval);
			
			this.global.push( element );
			
		},
		remove: function(element, label, fn) {
			var timers = jQuery.data(element, this.dataKey), ret;
			
			if ( timers ) {
				
				if (!label) {
					for ( label in timers )
						this.remove(element, label, fn);
				} else if ( timers[label] ) {
					if ( fn ) {
						if ( fn.timerID ) {
							window.clearInterval(timers[label][fn.timerID]);
							delete timers[label][fn.timerID];
						}
					} else {
						for ( var fn in timers[label] ) {
							window.clearInterval(timers[label][fn]);
							delete timers[label][fn];
						}
					}
					
					for ( ret in timers[label] ) break;
					if ( !ret ) {
						ret = null;
						delete timers[label];
					}
				}
				
				for ( ret in timers ) break;
				if ( !ret ) 
					jQuery.removeData(element, this.dataKey);
			}
		}
	}
});

jQuery(window).bind("unload", function() {
	jQuery.each(jQuery.timer.global, function(index, item) {
		jQuery.timer.remove(item);
	});
});


// JQuery URL Parser
// Written by Mark Perkins, mark@allmarkedup.com
// License: http://unlicense.org/ (i.e. do what you want with it!)
jQuery.url = function()
{
	var segments = {};
	var parsed = {};

	/* Options object. Only the URI and strictMode values can be changed via the setters below. */
  	var options = {
	
		url 		: window.location, // default URI is the page in which the script is running
		strictMode	: false, // 'loose' parsing by default
		key			: ["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","anchor"], // keys available to query 
		q : {
			name: "queryKey",
			parser: /(?:^|&)([^&=]*)=?([^&]*)/g
		},
		parser		: {
			strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,  //less intuitive, more accurate to the specs
			loose:  /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/ // more intuitive, fails on relative paths and deviates from specs
		}
	};
	
    /* Deals with the parsing of the URI according to the regex above. Written by Steven Levithan - see credits at top. */		
	var parseUri = function()
	{
		str = decodeURI( options.url );
		
		var m = options.parser[ options.strictMode ? "strict" : "loose" ].exec( str );
		var uri = {};
		var i = 14;

		while ( i-- ) {
			uri[ options.key[i] ] = m[i] || "";
		}

		uri[ options.q.name ] = {};
		uri[ options.key[12] ].replace( options.q.parser, function ( $0, $1, $2 ) {
			if ($1) {
				uri[options.q.name][$1] = $2;
			}
		});

		return uri;
	};

    /* Returns the value of the passed in key from the parsed URI. 
       @param string key The key whose value is required 
    */		
	var key = function( key )
	{
		if ( jQuery.isEmptyObject(parsed) )
		{
			setUp(); // if the URI has not been parsed yet then do this first...	
		} 
		if ( key == "base" )
		{
			if ( parsed.port !== null && parsed.port !== "" )
			{
				return parsed.protocol+"://"+parsed.host+":"+parsed.port+"/";	
			}
			else
			{
				return parsed.protocol+"://"+parsed.host+"/";
			}
		}
	
		return ( parsed[key] === "" ) ? null : parsed[key];
	};
	
	/* Returns the value of the required query string parameter.
	   @param string item The parameter whose value is required
    */		
	var param = function( item )
	{
		if ( jQuery.isEmptyObject(parsed) )
		{
			setUp(); // if the URI has not been parsed yet then do this first...	
		}
		return ( parsed.queryKey[item] === null ) ? null : parsed.queryKey[item];
	};

    /* 'Constructor' (not really!) function.
       Called whenever the URI changes to kick off re-parsing of the URI and splitting it up into segments. 
    */	
	var setUp = function()
	{
		parsed = parseUri();
		getSegments();	
	};
	
    /* Splits up the body of the URI into segments (i.e. sections delimited by '/') */
	var getSegments = function()
	{
		var p = parsed.path;
		segments = []; // clear out segments array
		segments = parsed.path.length == 1 ? {} : ( p.charAt( p.length - 1 ) == "/" ? p.substring( 1, p.length - 1 ) : path = p.substring( 1 ) ).split("/");
	};
	
	return {
		
	    /* Sets the parsing mode - either strict or loose. Set to loose by default.
	       @param string mode The mode to set the parser to. Anything apart from a value of 'strict' will set it to loose!
	    */
		setMode : function( mode )
		{
			options.strictMode = mode == "strict" ? true : false;
			return this;
		},
		
		/* Sets URI to parse if you don't want to to parse the current page's URI. Calling the function with no value for newUri resets it to the current page's URI.
	       @param string newUri The URI to parse.
	     */		
		setUrl : function( newUri )
		{
			options.url = newUri === undefined ? window.location : newUri;
			setUp();
			return this;
		},		
		
		/* Returns the value of the specified URI segment. Segments are numbered from 1 to the number of segments. For example the URI http://test.com/about/company/ segment(1) would return 'about'.  If no integer is passed into the function it returns the number of segments in the URI.
	       @param int pos The position of the segment to return. Can be empty.
	    */	
		segment : function( pos )
		{
			if ( jQuery.isEmptyObject(parsed) )
			{
				setUp(); // if the URI has not been parsed yet then do this first...	
			} 
			if ( pos === undefined )
			{
				return segments.length;
			}
			return ( segments[pos] === "" || segments[pos] === undefined ) ? null : segments[pos];
		},
		
		attr 	: key, // provides public access to private 'key' function - see above
		param 	: param // provides public access to private 'param' function - see above		
	};
}();


/**
 * Turns on auto suggestions for any input with the name="tags" set
 * Simply call autocomplete();
 * @requires jQuery
 * @requires jQuery UI
 * @requires jQuery UI Autocomplete module and all of it's dependcies
 * @requires jQuery URL Plugin (located in this file)
 */
var autocomplete = function(trigger_element, api_data, field, callback)
{
	$.get(jQuery.url.attr('protocol') + '://' +jQuery.url.attr('host') + '/' + api_data, function(json)
	{
		var data = json.data;
		var tags = [];
	
	if(typeof field == 'string')
	{
		for(x in data)
		{
			tags[x] = data[x][field];
		}
	}
	else if(field instanceof Array)
	{
		for (x in data)
		{
			tags[x] = [];
			for (y in field)
			{
	  			tags[x][y] = data[x][field[y]];
			}
		}
	}
		suggestions(tags);
	});
		
	var suggestions = function(availableTags)
	{
		function split(val)
		{
			return val.split( /,\s*/ );
		}
		
		function extractLast( term )
		{
			return split( term ).pop();
		}

		// don't navigate away from the field on tab when selecting an item		
		$(trigger_element).bind("keydown", function(event)
		{
			if (event.keyCode === $.ui.keyCode.TAB && $(this).data("autocomplete").menu.active)
			{
				event.preventDefault();
			}
		}).bind('keydown.autocomplete',function(e) {
		/*
		if(e.keyCode == 13){
		callback.call(this,$(this).val());
		 $(this).blur(function(){
		    $(this).unbind('keydown.autocomplete');
		 }); 
		}
		*/
   		}).autocomplete(
		{
			minLength: 0,
			source: function(request, response)
			{
				// delegate back to autocomplete, but extract the last term
				response($.ui.autocomplete.filter(availableTags, extractLast(request.term)));
			},
			focus: function(event, ui)
			{
				// prevent value inserted on focus
				return false;
			},
			select: function(event, ui)
			{
			     if (callback)
			     {
			     	callback.call(this,ui.item);
			     }
			     else
			     {
			     	var terms = split(this.value);
			      
					// remove the current input
					// add the selected item
					// add placeholder to get the comma-and-space at the end
					terms.pop();
					terms.push(ui.item.value);
					terms.push("");
					this.value = terms.join(", ");
			     }
				
				return false;
			}
		}).data('autocomplete')._renderItem = function(ul, item)
		{
	    	var returnedValue = item.label;
	        if (field instanceof Array)
	        {
	            returnedValue = item[0];
	            item.label = item[0];
	            item.value = item[0];
	        }
	        
	        return $("<li>").data("item.autocomplete", item).append("<a>"+returnedValue+"</a>").appendTo(ul);
 	   };
	} 
}

function is_int(value)
{ 
  if ((parseFloat(value) == parseInt(value)) && !isNaN(value))
  {
      return true;
  }
  else
  { 
      return false;
  }
}

String.prototype.trunc = function(n)
{
	return this.substr(0,n-1)+(this.length>n?'':'');
};