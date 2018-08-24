import React, { Component } from 'react';
import ImageLoading from './ImageLoading';

/**
 * Thumbnail of a person for displaying in a grid
 */
export default class Thumb extends Component{
    constructor(props) {
        super(props);
        this.state = {
            loaded: false,
        };
    }
    imageLoaded() {
        this.setState({loaded:true});
    }
    onClick() {
        this.props.onClick(this.props.person)
    }

    render() {
        const person = this.props.person;

        return (
            <div className='thumb'
                 onClick={this.onClick.bind(this)}
            >
                <div className="image-container">
                    <img src={person.image_url} alt={person.name} onLoad={this.imageLoaded.bind(this)} />
                    {this.state.loaded ? '' : <ImageLoading/>}
                </div>

                <h3>{person.name}</h3>
                <p>{person.description}</p>
            </div>
        );
    }

};
