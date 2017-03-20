const express = require('express')
const path = require('path')
const app = express()

const fs = require('fs')
const utf8 = 'utf8'

var stubAuth = false;

app.get('/tasklist/', function (req, res) {
  if (req.originalUrl !== '/tasklist/') {
    return res.redirect(301, '/tasklist/')
  }

  const template = fs.readFileSync('./web/templates/template.inc.html', utf8)
  const navigation = fs.readFileSync('./web/templates/navigation.inc.html', utf8)

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

function provider(label, id) {
  return {
    label,
    id,
    url: `/tasklist/auth/${id}`
  }
}

app.get('/tasklist/auth/status/json', function (req, res) {
  var result;
  if (stubAuth) {
    result = {
      auth: stubAuth,
      message: 'Stubbed auth info found; you have been signed in!',
      providers: [{
        label: 'Sign out',
        id: 'sign-out',
        url: './auth/forget'
      }]
    }
  } else {
    result = {
      auth: false,
      message: 'Auth info unavailable; please sign in using an appropriate provider.',
      providers: [
        provider('Facebook', 'facebook'),
        provider('Twitter', 'twitter'),
        provider('Google', 'google'),
        provider('GitHub', 'github')
      ]
    }
  }
  res.json(result)
})

app.get('/tasklist/auth/forget', function (req, res) {
  stubAuth = false
  res.redirect('/tasklist')
})

app.get('/tasklist/auth/:provider', function (req, res) {
  stubAuth = {
    info: {
      name: 'Stub User',
      nickname: 'stubby'
    },
    provider: `Stub ${req.params.provider}`
  }
  res.redirect('/tasklist')
})

const port = 8000;
app.listen(port, function () {
  console.log(`Taklist listening on http://localhost:${port}/tasklist/`)
})
