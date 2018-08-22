import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import Modal from 'react-modal';

import Thumb from './Thumb.js';
import MainLoading from './MainLoading.js';
import Bar from './Bar.js';
import None from './None.js';
import Person from './Person.js';

const SCROLL_END_MARGIN = 20;

export default class Root extends Component {
    constructor(props) {
        super(props);
        this.state = {
            people: [],
            selected_person: null,
            loading: false,
        };
    }

    componentDidMount() {
        this.reloadData();
        window.addEventListener('scroll', this.handleScroll.bind(this))
    }

    componentWillUnmount() {
        window.removeEventListener('scroll', this.handleScroll.bind(this))
    }

    handleScroll(event) {
        const scrollEnd = window.innerHeight + window.scrollY;
        const height = document.documentElement.scrollHeight;
        if (scrollEnd >= height - SCROLL_END_MARGIN && !this.state.loading){
            this.reloadData(null, this.state.people.length + 1)
        }
    }

    haveAllPages() {
        const people = this.state.people;
        return people.length && people.length == people[0]['last_page'];
    }

    reloadData(filter = null, page = null) {
        if(page && this.haveAllPages()){
            return;
        }
        const filter_dict = filter === null ? {} : filter;
        const page_number = page === null ? 1 : page;
        const should_clear_people = page === null;

        const params = Object.assign({}, filter_dict, {page: page_number});
        let query = '';
        if(Object.keys(params).length) {
            const esc = encodeURIComponent;
            query = '?' + Object.keys(params)
                .map(k => esc(k) + '=' + esc(params[k]))
                .join('&');
        }
        this.setState({loading:true}, () => {
            axios.get(`${ROOT_URL}/api/person${query}`)
            .then((people) => {
                let newPeople;
                if(should_clear_people) {
                    newPeople = [people.data];
                } else {
                    newPeople = this.state.people;
                    newPeople[page_number] = people.data;
                }
                this.setState({people: newPeople, loading: false});
            });
        });
    }

    thumbClick(person) {
        this.setState({selected_person: person});
    }

    afterOpenModal() {

    }

    onCloseModal() {
        this.setState({selected_person: null});
    }

    render() {
        const people = this.state.people.map((page)=>{
            return page.data.map((person)=> {
                return <Thumb key={person.id} person={person} rootUrl={ROOT_URL} onClick={this.thumbClick.bind(this)} />;
            });
        });
        const hasPeople = this.state.people.length && this.state.people[0].data.length;

        return (
            <div className="root">
                <Bar reloadData={this.reloadData.bind(this)} countries={countries} fields={fields}
                     rootUrl={ROOT_URL} canEdit={can_edit}
                />

                <div className="people">
                    {hasPeople ? people : (this.state.loading ? '' : <None/>)}
                </div>

                {this.state.loading ? <MainLoading/> : null}

                { this.state.selected_person !== null ?
                    <Modal
                        isOpen={true}
                        onAfterOpen={this.afterOpenModal.bind(this)}
                        onRequestClose={this.onCloseModal.bind(this)}
                        appElement={document.getElementById('root')}
                    >
                        <Person person={this.state.selected_person} onClose={this.onCloseModal.bind(this)}
                                rootUrl={ROOT_URL} canEdit={can_edit}
                        />
                    </Modal>
                    : ''
                }
            </div>
        );
    }
}