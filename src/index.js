import './index.css';
import App from './App';

import React from 'react';
import ReactDOM from 'react-dom';

const target = document.getElementById('ketogenic-meal-planner');
if (target) { ReactDOM.render(<App />, target); }