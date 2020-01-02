const path = require('path');

module.exports = {
  mode: 'development',
  // mode: 'production',
  devtool: 'sourcemap',
  entry: {
    'calendar.js': './src/calendar.js',
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
    filename: '[name]',
    filename: (chunkData) => {
      let dir_map = {
        '.js': 'scripts',
        '.css': 'stylesheets'
      };
      let ext = path.extname(chunkData.chunk.name);
      let dir = dir_map[ext];
      return dir + '/external/[name]';
    },
    path: path.join(__dirname, '..', '..', 'htdocs', 'resources')
  }
}
