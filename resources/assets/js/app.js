require('./bootstrap');
require('../3rdparty/searchable-option-list/sol');

import React from 'react';
import { render } from 'react-dom';

import Root from './components/Root';

render(<Root/>, document.getElementById('root'));