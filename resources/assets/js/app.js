import React from 'react';
import ReactDOM from 'react-dom';
import Index from  './components/index'
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';

const App = () => (
  <MuiThemeProvider>
    <Index />
  </MuiThemeProvider>
);


ReactDOM.render(
  <App />,
  document.getElementById('root')
);
