(function () {
  function readCookies(cookieString) {
    cookieString = cookieString || document.cookie;
    var cookiesArray = cookieString.split(';');
    var cookiesObject = {};
    cookiesArray.forEach(function (cookie) {
      var keyAndValue = cookie.split('=');
      var key = keyAndValue[0].trim();
      var value = keyAndValue[1];
      if (key && value) {
        try {
          cookiesObject[key] = JSON.parse(decodeURIComponent(value));
        } catch (ex) {
          cookiesObject[key] = value;
        }
      }
    });
    return cookiesObject;
  }

  function expiryString(expdays) {
    expdays = expdays || 365;
    var d = new Date();
    d.setTime(d.getTime() + (expdays * 24 * 60 * 60 * 1000));

    console.log('Expiry string', d.toUTCString(), 'Days', expdays)
    return ';expires=' + d.toUTCString();
  }

  function pathString(path) {
    return ';path=' + path;
  }

  function writeCookies(cookies, expdays, path) {
    path = path || '/';
    var result = '';
    delete cookies.expires;
    for (var key in cookies) {
      if (cookies.hasOwnProperty(key)) {
        var value = cookies[key];
        document.cookie = key + '=' + encodeURIComponent(JSON.stringify(value)) + expiryString(expdays) + pathString(path);
      }
    }
  }

  function removeItem(sKey, sPath, sDomain) {
    document.cookie = encodeURIComponent(sKey) +
      '=; expires=Thu, 01 Jan 1970 00:00:00 GMT' +
      (sDomain ? '; domain=' + sDomain : '') +
      (sPath ? '; path=' + sPath : '');
  }

  function clearAllCookies(paths) {
    paths = paths || ['/'];
    var cookies = readCookies();
    for (var i = 0; i < paths.length; i++) {
      var path = paths[i];
      for (var key in cookies) {
        removeItem(key, path);
      }
    }
  }

  var Cookies = {
    read: readCookies,
    write: writeCookies,
    clear: clearAllCookies
  };

  if (typeof module !== 'undefined') {
    module.exports = Cookies;
  }

  if (typeof window !== 'undefined') {
    window.Cookies = Cookies;
  }
})();
