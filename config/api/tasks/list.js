var endpoint = function() {}

var server = false;

endpoint.route = '/api/tasks/list';
endpoint.cacheDuration = '5 seconds';
endpoint.description = 'A list of tasks ordered by date'

endpoint.configure = function(config) {
  server = config.server;
}

endpoint.render = function(req, res) {
  var data = {};

  // read parameter from route
  var name = req.params.name || false;

  // form response
  var data = {
    tasks: [{
      text: `Superbowl Party at Alan and Sophie's`,
      dateString: 'Sunday evening',
      dateValue: Date.now()
    }]
  }

  // send response
  res.jsonp(data);
}

module.exports = endpoint;
