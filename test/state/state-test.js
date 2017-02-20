const state = require('../../state/tasklist.json');
const assert = require('assert');

describe('Tasklist Data', () => {
  it('should be structured as an array', () => {
    assert.ok(Array.isArray(state));
  });

  state.forEach((task) => {
    describe(`${task.text}`, () => {
      it(`should have a description`, () => {
        assert.ok(/^[A-z\s'\-,:\.!\/0-9]+$/i.test(task.text));
      });

      it(`should have a date string`, () => {
        assert.ok(/^[A-z\s'\-,:\.!\/0-9]+$/i.test(task.dateString));
      });

      it(`should have an entry date`, () => {
        assert.ok(Number.isFinite(task.entryDate));
      });
    });
  });
});
