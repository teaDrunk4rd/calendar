import React, {Component} from 'react';
import Preloader from "./Preloader";

export default class Event extends Component {
    constructor(props) {
        super(props);

        this.state = {
            id: props.location.id,
            name: '',
            description: '',
            date: '',
            type: '',
            isLoaded: false
        };
    }

    componentDidMount() {
        if (this.state.id !== undefined) {
            axios.get(`/api/events/read/${this.state.id}`).then(response => {
                if (response.status === 200) {
                    this.setState({
                        name: response.data.name,
                        description: response.data.description,
                        date: new Date(response.data.date),
                        type: response.data.event_type.name,
                        isLoaded: true
                    });
                }
            });
        }
    }

    render() {
        const {name, description, date, type} = this.state;
        return (
            <div className="col-6 m-auto">
                <div className="card text-center">
                    {!this.state.isLoaded ? <Preloader className='event-loader' /> : <div/>}
                    <div className="card-header">Событие</div>
                    <div className="card-body">
                        <h5 className="card-title">{name}</h5>
                        <h6 className="card-subtitle mb-2 text-muted">{date.toLocaleString('ru')}</h6>
                        <p className="card-text">{description}</p>
                    </div>
                    <div className="card-footer text-muted">{type}</div>
                </div>
            </div>
        );
    }
}
