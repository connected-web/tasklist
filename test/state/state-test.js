const state = require('../../state/tasklist.json');
const assert = require('assert');

describe('Tasklist Data', () => {
  it('should be structured as an array', () => {
    assert.ok(Array.isArray(state));
  });

  state.forEach((task) => {
    describe(`'${task.text}'`, () => {
      it(`should have a valid description`, () => {
        assert.ok(/^[A-z\s'\-,:\.!\/0-9&@()é£?+]+$/i.test(task.text));
      });

      it(`should have a valid date string: '${task.dateString}'`, () => {
        assert.ok(/^[A-z\s'\-,:\.!\/0-9@()]+$/i.test(task.dateString));
      });

      it(`should have a valid entry date: '${task.entryDate}'`, () => {
        assert.ok(Number.isFinite(task.entryDate));
      });
    });
  });
});
