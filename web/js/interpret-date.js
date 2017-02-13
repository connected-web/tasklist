var LOG_TABS = '     ';
const SECOND = 1000;
const MINUTE = SECOND * 60;
const HOUR = MINUTE * 60;
const DAY = HOUR * 24;
const WEEK = DAY * 7;

function interpretDate(dateContext, dateString) {
  var entry = new Date(dateFor(dateString, dateContext));
  if (interpretDate.debug) {
    console.log(LOG_TABS, 'Intepreted date', dateContext.toUTCString(), dateString, 'as', entry.toUTCString());
  }
  return entry;
}

var matchers = [{
  regex: /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun)u?s?n?e?s?r?s?d?a?y?$/i,
  handler: matchDayOfWeek
}, {
  regex: /^even?i?n?g?$/i,
  handler: matchEvening
}];

function matchEvening(token, tokens, context) {
  return {
    setUTCHours: 18,
    setUTCMinutes: 0,
    setUTCSeconds: 0
  };
}

function matchDayOfWeek(token, tokens, context) {
  var matcher = /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun)u?s?n?e?s?r?s?d?a?y?$/i;
  var key = token.match(matcher)[1].toLowerCase();
  var dayOffset = {
    'sun': 0,
    'mon': 1,
    'tue': 2,
    'wed': 3,
    'thu': 4,
    'fri': 5,
    'sat': 6
  }[key];

  if (!Number.isInteger(dayOffset)) {
    throw 'Unrecognised day of the week: ' + dayOffset;
  }

  dayOffset = (dayOffset - context.getDay() + 7) % 7 || 7;
  var now = context.getTime();
  var future = new Date(now + (dayOffset * DAY));

  return {
    setUTCFullYear: future.getUTCFullYear(),
    setUTCMonth: future.getUTCMonth(),
    setUTCDate: future.getUTCDate(),
    setUTCHours: 8,
    setUTCMinutes: 0,
    setUTCSeconds: 0
  };
}

function tokenize(inputString) {
  var plainText = inputString.replace(/[^a-z0-9+:\/\-]+/gi, ' ');
  var sanitized = plainText.replace(/\s+/gi, ' ');
  var tokens = sanitized.split(' ');
  return tokens;
}

function dateFor(dateString, context) {
  var tokens = tokenize(dateString);
  var token;

  var results = [];
  while (tokens.length > 0) {
    token = tokens.shift();
    matchers.forEach(function(matcher) {
      if (matcher.regex.test(token)) {
        var result = matcher.handler(token, tokens, context);
        if (interpretDate.debug) {
          console.log(LOG_TABS, 'Matched', JSON.stringify(result));
        }
        results.push(result);
      };
    });
  }

  var date = new Date(context);
  results.forEach(function(item) {
    Object.keys(item).forEach(function(key) {
      var value = item[key];
      date[key](value);
    });
  });

  return date;
}

if (typeof module !== 'undefined') {
  module.exports = interpretDate;
}
