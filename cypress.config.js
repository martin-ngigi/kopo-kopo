const { defineConfig } = require('cypress')

module.exports = defineConfig({
  env: {
    apiUrl: 'http://localhost:8000/api',
  },
  e2e: {
    setupNodeEvents(on, config) {},
    supportFile: false,
  },
})