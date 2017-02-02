const PORT = process.env.PORT || 8080;

var monitor = require('product-monitor');
var server = monitor({
  "serverPort": PORT,
  "productInformation": {
    "title": "Tasklist",
  },
  "userContentPath": "config"
});
