var endpoint = function() {}

var server = false;

endpoint.route = '/api/tasks/list';
endpoint.cacheDuration = '5 seconds';
endpoint.description = 'A list of tasks ordered by date';

endpoint.configure = function(config) {
  server = config.server;
}

endpoint.render = function(req, res) {
  var data = {};

  // read parameter from route
  var name = req.params.name || false;

  // form response
  var tasks = require('../../../state/sample-tasks.json');

  // send response
  res.jsonp({
    tasks
  });
}

module.exports = endpoint;
