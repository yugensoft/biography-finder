import React, { Component } from 'react';

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

    render() {
        const name = encodeURI(this.state.create_name);
        return (
            <div className="create dark-inputs">
                <label htmlFor="create_name">Create New</label><br />
                <input type="text" name="create_name" id="create_name" value={this.state.create_name}
                       onChange={this.createNameChange.bind(this)}
                />
                <a href={`${this.props.rootUrl}/person/create?name=${name}`}><button className="btn btn-sm">Create</button></a>
            </div>
        );
    }
}