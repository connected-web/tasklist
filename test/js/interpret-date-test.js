const assert = require('assert');
const interpretDate = require('../../web/js/interpret-date.js');

const fixedTime = new Date(1486940936 * 1000); // Sun Feb 12 2017 23:08:56 GMT
const second = 1000;
const minute = second * 60;
const hour = minute * 60;
const day = hour * 24;
const week = day * 7;

interpretDate.debug = false;

function test(input, expected, description) {
  it(description || `should match '${input}' as ${expected}`, () => {
    const actual = interpretDate(fixedTime, input).toUTCString();
    assert.deepEqual(actual, expected);
  });
}

describe('Interpret Date', () => {

  describe('No change', () => {
    test('', 'Sun, 12 Feb 2017 23:08:56 GMT');
  });

  describe('Day of Week Matchers', () => {
    test('Next sunday', 'Sun, 19 Feb 2017 08:00:00 GMT', 'should match the next Sunday from a Sunday');
    test('on Monday', 'Mon, 13 Feb 2017 08:00:00 GMT');
    test('Tues', 'Tue, 14 Feb 2017 08:00:00 GMT');
    test('wednesday', 'Wed, 15 Feb 2017 08:00:00 GMT');
    test('thursda', 'Thu, 16 Feb 2017 08:00:00 GMT');
    test('Fri', 'Fri, 17 Feb 2017 08:00:00 GMT');
    test('Satur', 'Sat, 18 Feb 2017 08:00:00 GMT');
    test('Sun', 'Sun, 19 Feb 2017 08:00:00 GMT');
  });

  describe('Time of day strings', () => {
    test('evening', 'Sun, 12 Feb 2017 18:00:00 GMT');
    test('Monday evenig', 'Mon, 13 Feb 2017 18:00:00 GMT');
    test('Friday eve', 'Fri, 17 Feb 2017 18:00:00 GMT');

    test('morning', 'Sun, 12 Feb 2017 08:00:00 GMT');
    test('Monday morn', 'Mon, 13 Feb 2017 08:00:00 GMT');
    test('Friday mornin', 'Fri, 17 Feb 2017 08:00:00 GMT');
  });

  describe('After points in time', () => {
    test('Monday after breakfast', 'Mon, 13 Feb 2017 07:00:00 GMT');
    test('Tuesday after work', 'Tue, 14 Feb 2017 17:00:00 GMT');
    test('Wednesday after lunch', 'Wed, 15 Feb 2017 13:00:00 GMT');
    test('Thursday after school', 'Thu, 16 Feb 2017 16:00:00 GMT');
  });

  describe('Before points in time', () => {
    test('Monday before breakfast', 'Mon, 13 Feb 2017 06:00:00 GMT');
    test('Tuesday before work', 'Tue, 14 Feb 2017 07:00:00 GMT');
    test('Wednesday before lunch', 'Wed, 15 Feb 2017 11:00:00 GMT');
    test('Thursday before school', 'Thu, 16 Feb 2017 07:00:00 GMT');
  });
});
