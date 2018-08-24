import React, { Component } from 'react';

/**
 * Overlay containing person details
 * @param person Person data
 * @param onClose Function to run on overlay close
 * @param rootUrl URL of the app
 * @param canEdit User can edit this person (is an admin)
 * @returns {XML}
 * @constructor
 */
const Person = ({person, onClose, rootUrl, canEdit}) => {
    return (
        <div className="person">
            <h1>{person.name} <img src={person.image_url} alt={person.name} /></h1>

            <h2>{person.fields.reduce((a,b,idx)=>{
                const fieldTitle = b.field.charAt(0).toUpperCase() + b.field.slice(1);
                return idx == 0 ? fieldTitle : `${a}, ${fieldTitle}`;
            }, '')}</h2>

            <p>{person.description}</p>

            <p><a target="_blank" href={person.wiki}>Wikipedia</a></p>

            { person.achievements.length ?
                <div>
                    <h3>Achievements</h3>
                    <ul>
                        {person.achievements.map((achievement, index) => {
                            return <li key={index}>{achievement}</li>;
                        })}
                    </ul>
                </div> : ''
            }

            <h3>Biography books</h3>
            <ul>
                {person.books.map((book, index)=>{return <li key={index}><a target="_blank" href={book.url}>{book.title}</a></li>;})}
            </ul>

            <div className="buttons">
                {canEdit ? <a href={`${rootUrl}/person/${person.id}/edit`}><button className="btn">Edit</button></a> : ''}
                <button className="btn" onClick={onClose}>Close</button>
            </div>
        </div>
    );
};

export default Person;