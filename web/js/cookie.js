(function () {
  function readCookies (cookieString) {
    cookieString = cookieString || document.cookie
    const cookiesArray = cookieString.split(';')
    const cookiesObject = {}
    cookiesArray.forEach(function (cookie) {
      const keyAndValue = cookie.split('=')
      const key = keyAndValue[0].trim()
      const value = keyAndValue[1]
      if (key && value) {
        try {
          cookiesObject[key] = JSON.parse(decodeURIComponent(value))
        } catch (ex) {
          cookiesObject[key] = value
        }
      }
    })
    return cookiesObject
  }

  function expiryString (expdays) {
    expdays = expdays || 365
    const d = new Date()
    d.setTime(d.getTime() + (expdays * 24 * 60 * 60 * 1000))

    console.log('Expiry string', d.toUTCString(), 'Days', expdays)
    return ';expires=' + d.toUTCString()
  }

  function pathString (path) {
    return ';path=' + path
  }

  function writeCookies (cookies, expdays, path) {
    path = path || '/'
    delete cookies.expires
    for (const key in cookies) {
      if (Object.prototype.hasOwnProperty.call(cookies, key)) {
        const value = cookies[key]
        document.cookie = key + '=' + encodeURIComponent(JSON.stringify(value)) + expiryString(expdays) + pathString(path)
      }
    }
  }

  function removeItem (sKey, sPath, sDomain) {
    document.cookie = encodeURIComponent(sKey) +
      '=; expires=Thu, 01 Jan 1970 00:00:00 GMT' +
      (sDomain ? '; domain=' + sDomain : '') +
      (sPath ? '; path=' + sPath : '')
  }

  function clearAllCookies (paths) {
    paths = paths || ['/']
    const cookies = readCookies()
    for (let i = 0; i < paths.length; i++) {
      const path = paths[i]
      for (const key in cookies) {
        removeItem(key, path)
      }
    }
  }

  const Cookies = {
    read: readCookies,
    write: writeCookies,
    clear: clearAllCookies
  }

  if (typeof module !== 'undefined') {
    module.exports = Cookies
  }

  if (typeof window !== 'undefined') {
    window.Cookies = Cookies
  }
})()
