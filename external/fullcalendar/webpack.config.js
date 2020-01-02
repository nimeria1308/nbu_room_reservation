const path = require('path')

module.exports = {
  mode: 'development',
  entry: './src/calendar.js',
  resolve: {
    extensions: [ '.js' ]
  },
  output: {
    filename: 'calendar.js',
    path: path.join(__dirname, 'dist')
  },
  devtool: 'sourcemap'
}
