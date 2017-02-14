(function () {
  var LOG_TABS = '     ';
  const SECOND = 1000;
  const MINUTE = SECOND * 60;
  const HOUR = MINUTE * 60;
  const DAY = HOUR * 24;
  const WEEK = DAY * 7;
  const MONTH = DAY * 30.417;
  const YEAR = DAY * 365.25;

  var DayOfWeekMatcher = /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun)u?s?n?e?s?r?s?d?a?y?$/i;
  var MonthOfYearMatcher = /^(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)[ruarychileyustemober]*$/i;
  var DateInMonthMatcher = /^(\d\d?)[nrdsth]{2}$/i;

  var matchers = [{
    regex: DayOfWeekMatcher,
    handler: matchDayOfWeek
  }, {
    regex: MonthOfYearMatcher,
    handler: matchMonthOfYear
  }, {
    regex: /^even?i?n?g?$/i,
    handler: matchEvening
  }, {
    regex: /^before$/i,
    handler: matchBefore
  }, {
    regex: /^after$/i,
    handler: matchAfter
  }, {
    regex: /^morn?i?n?g?$/i,
    handler: matchMorning
  }, {
    regex: /^tomor[row]*$/i,
    handler: matchTomorrow
  }, {
    regex: DateInMonthMatcher,
    handler: matchDateInMonth
  }];

  const applyHours = (hours) => {
    return {
      setUTCHours: hours
    };
  };

  function matchDateInMonth(token, tokens, context) {
    var dateInMonth = Number.parseInt(token.match(DateInMonthMatcher)[1]);

    return {
      setUTCDate: dateInMonth
    };
  }

  function matchTomorrow(token, tokens, context) {
    var now = context.getTime();
    var future = new Date(now + DAY);

    return {
      setUTCFullYear: future.getUTCFullYear(),
      setUTCMonth: future.getUTCMonth(),
      setUTCDate: future.getUTCDate()
    };
  }

  function matchAfter(token, tokens, context) {
    const subject = tokens.shift();
    const subjects = {
      work: applyHours(17),
      lunch: applyHours(13),
      school: applyHours(16),
      breakfast: applyHours(7)
    };

    return subjects[subject] || {};
  }

  function matchBefore(token, tokens, context) {
    const subject = tokens.shift();
    const subjects = {
      work: applyHours(7),
      lunch: applyHours(11),
      school: applyHours(7),
      breakfast: applyHours(6)
    };

    return subjects[subject] || {};
  }

  function matchEvening(token, tokens, context) {
    return {
      setUTCHours: 18
    };
  }

  function matchMorning(token, tokens, context) {
    return {
      setUTCHours: 8
    };
  }

  function matchMonthOfYear(token, tokens, context) {
    var key = token.match(MonthOfYearMatcher)[1].toLowerCase();
    var monthOffset = {
      'jan': 0,
      'feb': 1,
      'mar': 2,
      'apr': 3,
      'may': 4,
      'jun': 5,
      'jul': 6,
      'aug': 7,
      'sep': 8,
      'oct': 9,
      'nov': 10,
      'dec': 11
    }[key];

    if (!Number.isInteger(monthOffset)) {
      throw 'Unrecognised month of the year: ' + monthOffset;
    }

    monthOffset = (monthOffset - context.getMonth() + 12) % 12 || 12;
    var now = context.getTime();
    var future = new Date(now + (monthOffset * MONTH));

    return {
      setUTCFullYear: future.getUTCFullYear(),
      setUTCMonth: future.getUTCMonth()
    };
  }

  function matchDayOfWeek(token, tokens, context) {
    var key = token.match(DayOfWeekMatcher)[1].toLowerCase();
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
      setUTCDate: future.getUTCDate()
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
      matchers.forEach(function (matcher) {
        if (matcher.regex.test(token)) {
          var result = matcher.handler(token, tokens, context);
          if (interpretDate.debug) {
            console.log(LOG_TABS, 'Matched', JSON.stringify(result));
          }
          results.push(result);
        };
      });
    }

    const decisions = {};
    if (results.length > 0) {
      results.forEach(function (item) {
        Object.keys(item).forEach(function (key) {
          var value = item[key];
          decisions[key] = value;
        });
      });

      if (Number.isInteger(decisions.setUTCFullYear)) {
        decisions.setUTCMonth = decisions.setUTCMonth || 0;
      }
      if (Number.isInteger(decisions.setUTCMonth)) {
        decisions.setUTCDate = decisions.setUTCDate || 1;
      }
      if (Number.isInteger(decisions.setUTCDate)) {
        decisions.setUTCHours = decisions.setUTCHours || 8;
      }
      if (Number.isInteger(decisions.setUTCHours)) {
        decisions.setUTCMinutes = decisions.setUTCMinutes || 0;
      }
      if (Number.isInteger(decisions.setUTCMinutes)) {
        decisions.setUTCSeconds = decisions.setUTCSeconds || 0;
      }
    }

    var date = new Date(context);
    Object.keys(decisions).forEach(function (key) {
      var value = decisions[key];
      date[key](value);
    });

    return date;
  }

  function interpretDate(dateContext, dateString) {
    var entry = new Date(dateFor(dateString, dateContext));
    if (interpretDate.debug) {
      console.log(LOG_TABS, 'Intepreted date', dateContext.toUTCString(), dateString, 'as', entry.toUTCString());
    }
    return entry;
  }

  if (typeof module !== 'undefined') {
    module.exports = interpretDate;
  }

  if (typeof window !== 'undefined') {
    window.interpretDate = interpretDate;
  }
})();
