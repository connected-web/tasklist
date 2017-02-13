const assert = require('assert');
const timeUntil = require('../../web/js/time-until.js');

const fixedTime = new Date(1486940936 * 1000); // Sun Feb 12 2017 23:08:56 GMT
const second = 1000;
const minute = second * 60;
const hour = minute * 60;
const day = hour * 24;
const week = day * 7;

function test(input, expected, description) {
  it(description || `should interpret '${input}' as ${expected}`, () => {
    const actual = timeUntil(new Date(Date.parse(input)), fixedTime).string;
    assert.deepEqual(actual, expected);
  });
}

describe('Interpret Date', () => {

  describe('No change', () => {
    test('Sun, 12 Feb 2017 23:08:56 GMT', 'now');
  });

  describe('Seconds', () => {
    test('Sun, 12 Feb 2017 23:09:01 GMT', '5 seconds');
    test('Sun, 12 Feb 2017 23:09:58 GMT', '62 seconds');
  });

  describe('Minutes', () => {
    test('Sun, 12 Feb 2017 23:13:56 GMT', '5 minutes');
    test('Sun, 13 Feb 2017 01:07:56 GMT', '119 minutes');
  });

  describe('Hours', () => {
    test('Sun, 13 Feb 2017 04:08:56 GMT', '5 hours');
    test('Sun, 14 Feb 2017 22:08:56 GMT', '47 hours');
  });

  describe('Days', () => {
    test('Sun, 17 Feb 2017 23:08:56 GMT', '5 days');
    test('Sun, 20 Feb 2017 23:08:56 GMT', '8 days');
    test('Sun, 04 Mar 2017 23:08:56 GMT', '20 days');
  });

  describe('Weeks', () => {
    test('Sun, 05 Mar 2017 23:08:56 GMT', '3 weeks');
    test('Sun, 12 Mar 2017 23:08:56 GMT', '4 weeks');
    test('Sun, 22 Mar 2017 23:08:56 GMT', '5 weeks');
    test('Sun, 12 Apr 2017 23:08:56 GMT', '8 weeks');
  });

  describe('Months', () => {
    test('Sun, 22 May 2017 23:08:56 GMT', '3 months');
    test('Sun, 12 Jul 2017 23:08:56 GMT', '5 months');
    test('Sun, 12 Feb 2018 23:08:56 GMT', '12 months');
    test('Sun, 12 Jan 2019 23:08:56 GMT', '23 months');
    test('Sun, 12 Feb 2019 23:08:56 GMT', '24 months');
  });

  describe('Years', () => {
    test('Sun, 13 Feb 2019 23:08:56 GMT', '2 years');
    test('Sun, 13 Feb 2022 23:08:56 GMT', '5 years');
  });
});
