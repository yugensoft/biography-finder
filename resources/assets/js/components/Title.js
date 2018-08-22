import React, { Component } from 'react';

const Title = ({reset}) => {
    return (
        <div className="title">
            <div><h1>Biography Finder</h1></div>
            <div><button className="btn" onClick={reset}><i className="fas fa-undo"></i> Reset</button></div>
        </div>
    );
};

export default Title;