import React, { Component } from 'react';

/**
 * Field & button for creating new people
 */
export default class Create extends Component {
    constructor(props) {
        super(props);
        this.state = {
            create_name: '',
        }
    }

    createNameChange(event) {
        this.setState({'create_name': event.target.value});
    }

    getUrl() {
        const name = encodeURI(this.state.create_name);
        return `${this.props.rootUrl}/person/create?name=${name}`
    }

    handleKeyPress(event) {
        if (event.key === 'Enter') {
            window.location = this.getUrl();
        }
    }

    render() {
        return (
            <div className="create dark-inputs">
                <label htmlFor="create_name">Create New</label><br />
                <input type="text" name="create_name" id="create_name" value={this.state.create_name}
                       onChange={this.createNameChange.bind(this)}
                       onKeyPress={this.handleKeyPress.bind(this)}
                />
                <a href={this.getUrl()}><button className="btn btn-sm">Create</button></a>
            </div>
        );
    }
}