const state = require('../../state/tasklist.json');
const assert = require('assert');

describe('Tasklist Data', () => {
  it('should be structured as an array', () => {
    assert.ok(Array.isArray(state));
  });
});
