(function () {
  const SECOND = 1000
  const MINUTE = SECOND * 60
  const HOUR = MINUTE * 60
  const DAY = HOUR * 24
  const WEEK = DAY * 7
  const MONTH = DAY * 30.417
  const YEAR = DAY * 365.25

  function describeTimeUntil (input) {
    let unit, value, plural, past
    const ms = Math.abs(input)

    if (ms < SECOND) {
      return {
        value: ms,
        unit: 'millisecond',
        string: 'now',
        past: (input < 0)
      }
    } else if (ms < 2 * MINUTE) {
      value = Math.floor(ms / SECOND)
      unit = 'second'
    } else if (ms < 2 * HOUR) {
      value = Math.floor(ms / MINUTE)
      unit = 'minute'
    } else if (ms < 2 * DAY) {
      value = Math.floor(ms / HOUR)
      unit = 'hour'
    } else if (ms < 3 * WEEK) {
      value = Math.round(ms / DAY)
      unit = 'day'
    } else if (ms < 3 * MONTH) {
      value = Math.round(ms / WEEK)
      unit = 'week'
    } else if (ms < 2 * YEAR) {
      value = Math.round(ms / MONTH)
      unit = 'month'
    } else {
      value = Math.floor(ms / YEAR)
      unit = 'year'
    }

    plural = (value > 1) ? 's' : ''
    past = (input < 0) ? 'ago' : ''

    return {
      unit: unit,
      value: value,
      string: [value, unit + plural, past].join(' ').trim(),
      past: (input < 0)
    }
  }

  function timeUntil (date, context) {
    context = context || new Date()
    var diff = date.getTime() - context.getTime()

    return describeTimeUntil(diff)
  }

  if (typeof module !== 'undefined') {
    module.exports = timeUntil
  }

  if (typeof window !== 'undefined') {
    window.timeUntil = timeUntil
  }
})()
