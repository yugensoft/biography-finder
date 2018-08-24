import React, { Component } from 'react';
import ReactLoading from 'react-loading';

/**
 * Overlay app data loading indicator
 */
const MainLoading = () => {
    return <div className="main-loading"><ReactLoading type="spin" color="blue" height="200" width="200" /></div>;
};
export default MainLoading;