const path = require('path');
const scripts_path = path.join(__dirname, '..', '..', 'htdocs', 'resources', 'scripts', 'external');

module.exports = {
  mode: 'development',
  // mode: 'production',
  devtool: 'sourcemap',

  entry: {
    'calendar': './src/calendar.js',
  },
  module: {
    rules: [
      {
        test: /\.css$/i,
        use: ['style-loader', 'css-loader'],
      },
    ],
  },
  resolve: {
    extensions: ['.js'],
    extensions: ['.css']
  },
  output: {
    filename: '[name].js',
    path: scripts_path
  }
}
