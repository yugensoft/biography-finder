import React, { Component } from 'react';
import Create from './Create.js';
import Title from './Title.js';


export default class Bar extends Component {
    constructor(props){
        super(props);

        this.baseState = {
            fields: [],
            countries: [],
            gender: 'either',
            born_before: '',
            born_after: '',
        };

        this.state = this.baseState;
    }

    static solChange (sol, elements) {
        const selection = sol.getSelection();
        let values = [];
        for(let i = 0; i < selection.length; i++) {
            values.push(selection[i].value);
        }
        return values;
    }

    fieldsChange(sol, elements) {
        this.setState({fields: Bar.solChange(sol, elements)}, this.reload);
    }

    countriesChange(sol, elements) {
        this.setState({countries: Bar.solChange(sol, elements)}, this.reload);
    }

    componentDidMount() {
        $('#fields').searchableOptionList({
            showSelectAll: false,
            showSelectionBelowList: true,
            data: this.props.fields.map((field) => {
                const fieldTitle = field.field.charAt(0).toUpperCase() + field.field.slice(1);
                return { "type": "option", "value": field.id, "label": fieldTitle};
            }),
            events: {
                onChange: this.fieldsChange.bind(this),
            },
            maxHeight: 200,
        });
        $('#countries').searchableOptionList({
            showSelectAll: false,
            showSelectionBelowList: true,
            data: Object.entries(this.props.countries).map(([code, country]) => {
                return { "type": "option", "value": code.toLowerCase(), "label": country};
            }),
            events: {
                onChange: this.countriesChange.bind(this),
            },
            maxHeight: 200,
        });
    }

    reload(){
        this.props.reloadData(this.state);
    }

    genderChange(event) {
        this.setState({gender: event.target.value}, this.reload);
    }
    bornBeforeChange(event) {
        this.setState({born_before: event.target.value});
    }
    bornAfterChange(event) {
        this.setState({born_after: event.target.value});
    }

    handleKeyPress(event) {
        if (event.key === 'Enter') {
            this.reload();
        }
    }

    resetFilter() {
        $('#fields').searchableOptionList().deselectAll();
        $('#countries').searchableOptionList().deselectAll();
        this.setState(this.baseState, this.reload);
    }

    render() {
        return (
            <div>
                <Title reset={this.resetFilter.bind(this)} />
                <div className="bar">

                    <fieldset>
                        <label htmlFor="fields">Fields</label>
                        <select id="fields" name="fields" multiple="multiple"
                        >
                        </select>
                    </fieldset>

                    <fieldset>
                        <label htmlFor="countries">Countries</label>
                        <select id="countries" name="countries" multiple="multiple"
                        >
                        </select>
                    </fieldset>

                    <fieldset id="gender">
                        <label htmlFor="gender">Gender</label><br />

                        <div className="flex">
                            <div>
                                <label>
                                    <input type="radio" name="gender" value="either"
                                           onChange={ this.genderChange.bind(this) }
                                           checked={this.state.gender === 'either'}
                                    />
                                    Either
                                </label>
                            </div>

                            <div>
                                <label>
                                    <input type="radio" name="gender" value="male"
                                           onChange={ this.genderChange.bind(this) }
                                           checked={this.state.gender === 'male'}
                                    />
                                    Male
                                </label>
                            </div>

                            <div>
                                <label>
                                    <input type="radio" name="gender" value="female"
                                           onChange={ this.genderChange.bind(this) }
                                           checked={this.state.gender === 'female'}
                                    />
                                    Female
                                </label>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset id="born">
                        <label htmlFor="born_after">Born between years</label><br />

                        <div className="flex dark-inputs">
                            <div>
                                <input type="number" id="born_after" name="born_after" placeholder="start"
                                       value={this.state.born_after} onChange={this.bornAfterChange.bind(this)}
                                       onKeyPress={this.handleKeyPress.bind(this)}
                                />
                            </div>

                            <div><label htmlFor="born_before">and</label></div>

                            <div>
                                <input type="number" id="born_before" name="born_before" placeholder="end"
                                       value={this.state.born_before} onChange={this.bornBeforeChange.bind(this)}
                                       onKeyPress={this.handleKeyPress.bind(this)}
                                />
                            </div>
                        </div>
                    </fieldset>

                    <fieldset id="create">
                        {this.props.canEdit ? <Create rootUrl={this.props.rootUrl} /> : ''}
                    </fieldset>
                </div>
            </div>
        );
    }
}
