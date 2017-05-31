const path = require('path');

module.exports = {
  entry: path.resolve(__dirname, 'resources/assets/js/app.js'),
  output: {
    path: path.resolve(__dirname, 'public'),
    filename: 'bundle.js'       
  },
  resolve: {
    modules: [path.resolve(__dirname, './node_modules')]
  },
  devServer: {
    contentBase: path.join(__dirname, "public"),
    compress: true,
    port: 9000
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /(node_modules|bower_components)/,
        use: {
          loader: 'babel-loader',
          options: {
            "presets": [
               "react", 'es2015'
            ],
            "plugins": [
              'babel-plugin-transform-es2015-arrow-functions','transform-class-properties'
            ]
          }
        },
      }
    ]
  }
};