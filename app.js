const express = require('express')
const path = require('path')
const app = express()


const fs = require('fs')
const utf8 = 'utf8'
const template = fs.readFileSync('./web/templates/template.inc.html', utf8)
const navigation = fs.readFileSync('./web/templates/navigation.inc.html', utf8)

app.get('/tasklist', function (req, res) {
  var output = template.replace('{{NAVIGATION}}', navigation)
  output = output.replace('{{NOW}}', Date.now())
  res.send(output)
})

app.use('/tasklist', express.static(path.join(__dirname, 'web')))

const tasklist = {
  tasks: require(path.join(__dirname, 'state', 'tasklist.json'))
}

app.get('/tasklist/tasks/json', function (req, res) {
  res.json(tasklist)
})

const port = 8000;
app.listen(port, function () {
  console.log(`Taklist listening on port ${port}!`)
});
