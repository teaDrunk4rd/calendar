import React, {Component} from 'react'
import Select from "react-select";
import DatePicker, { registerLocale } from "react-datepicker";
import ru from "date-fns/locale/ru";
import {NotificationManager} from "react-notifications";
import Preloader from "./Preloader";
registerLocale("ru", ru);

export default class EventForm extends Component {
    constructor(props) {
        super(props);

        this.state = {
            id : props.location.id,
            name: '',
            description: '',
            date: '',
            typeId: '',
            eventTypes: [],
            closedDate: '',
            isLoaded: false
        };
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    componentDidMount() {
        axios.get('/api/eventTypes').then(response => {
            if (response.status === 200) {
                this.setState({
                    eventTypes: response.data
                });
            }
        }).catch(error => {
            NotificationManager.error("Произошла ошибка");
            if (error.response.status === 401)
                this.props.history.push('/login');
        });
        if (this.state.id !== undefined) {
            axios.get(`/api/events/read/${this.state.id}`).then(response => {
                if (response.status === 200) {
                    this.setState({
                        name: response.data.name,
                        description: response.data.description,
                        date: new Date(response.data.date),
                        typeId: response.data.event_type.id,
                        closedDate: response.data.closed_at != null ? new Date(response.data.closed_at) : null,
                        isLoaded: true
                    });
                }
            }).catch(error => {
                NotificationManager.error("Произошла ошибка");
                if (error.response.status === 401)
                    this.props.history.push('/login');
            });
        } else {
            this.setState({
                isLoaded: true
            });
        }
    }

    handleSubmit(event) {
        event.preventDefault();

        if (this.state.name === '') {
            return NotificationManager.error('Заполните наименование');
        } else if (this.state.date === '' || this.state.date === null) {
            return NotificationManager.error('Заполните дату');
        } else if (this.state.typeId === '') {
            return NotificationManager.error('Заполните тип');
        } else if (this.state.name.length > 255) {
            return NotificationManager.error('Слишком длинное наименование');
        }

        if (this.state.id !== undefined) {
            axios.put('/api/events/update', {
                id: this.state.id,
                name: this.state.name,
                description: this.state.description,
                date: (this.state.date.getTime() - (this.state.date.getTimezoneOffset() * 60000)) / 1000,
                type_id: this.state.typeId,
                closed_at: this.state.closedDate !== null && this.state.closedDate !== '' ?
                    (this.state.closedDate.getTime() - (this.state.closedDate.getTimezoneOffset() * 60000)) / 1000 : null,
                creator_id: JSON.parse(localStorage["user"]).id
            }).then(response => {
                if (response.status === 200 && !response.data.message) {
                    this.props.history.push({
                        pathname: '/',
                        date: this.state.date
                    });
                } else {
                    NotificationManager.error(response.data.message);
                }
            }).catch(error => {
                NotificationManager.error("Произошла ошибка");
                if (error.response.status === 401)
                    this.props.history.push('/login');
            });
        } else {
            axios.post('/api/events/create', {
                name: this.state.name,
                description: this.state.description,
                date: (this.state.date.getTime() - (this.state.date.getTimezoneOffset() * 60000)) / 1000,
                type_id: this.state.typeId,
                closed_at: this.state.closedDate !== null && this.state.closedDate !== '' ?
                    (this.state.closedDate.getTime() - (this.state.closedDate.getTimezoneOffset() * 60000)) / 1000 : null,
                creator_id: JSON.parse(localStorage["user"]).id
            }).then(response => {
                if (response.status === 201 && !response.data.message) {
                    this.props.history.push({
                        pathname: '/',
                        date: this.state.date
                    });
                } else {
                    NotificationManager.error(response.data.message);
                }
            }).catch(error => {
                NotificationManager.error("Произошла ошибка");
                if (error.response.status === 401)
                    this.props.history.push('/login');
            });
        }
    }

    render() {
        const {id, name, description, date, closedDate, typeId} = this.state;
        return (
            <div className="col-6 m-auto">
                <div className="card">
                    {!this.state.isLoaded ? <Preloader /> : <div/>}
                    <div className="card-header">Событие</div>
                    <div className="card-body">
                        <form onSubmit={this.handleSubmit} autoComplete='false'>
                            <div className="form-group row">
                                <label className="col-md-4 col-form-label text-md-right">Наименование</label>
                                <div className="col-md-6">
                                    <input type="text"
                                           autoComplete="false"
                                           required="required"
                                           value={name}
                                           onChange={event => this.setState({name: event.target.value})}
                                           className="form-control "/>
                                </div>
                            </div>

                            <div className="form-group row">
                                <label className="col-md-4 col-form-label text-md-right">
                                    Описание
                                </label>
                                <div className="col-md-6">
                                    <textarea className="form-control"
                                              value={description}
                                              onChange={event => this.setState({description: event.target.value})}
                                              rows="3" />
                                </div>
                            </div>

                            <div className="form-group row">
                                <label className="col-md-4 col-form-label text-md-right">Дата начала</label>
                                <div className="col-md-6">
                                    <DatePicker
                                        selected={date}
                                        onChange={date => this.setState({date: date})}
                                        showTimeSelect
                                        timeFormat="HH"
                                        locale="ru"
                                        timeIntervals={60}
                                        timeCaption="time"
                                        dateFormat="dd.MM.yyyy HH:00"
                                    />
                                </div>
                            </div>

                            <div className="form-group row">
                                <label className="col-md-4 col-form-label text-md-right">Дата закрытия</label>
                                <div className="col-md-6">
                                    <DatePicker
                                        selected={closedDate}
                                        onChange={date => this.setState({closedDate: date})}
                                        timeFormat="HH"
                                        locale="ru"
                                        timeCaption="time"
                                        dateFormat="dd.MM.yyyy"
                                    />
                                </div>
                            </div>

                            <div className="form-group row">
                                <label className="col-md-4 col-form-label text-md-right">Тип</label>
                                <div className="col-md-6">
                                    <Select required='required'
                                            placeholder='Выберите тип'
                                            value={this.state.eventTypes.filter(eventType => eventType.id === typeId)[0]}
                                            onChange={type => this.setState({typeId: type.id})}
                                            getOptionLabel={eventType => eventType.name}
                                            getOptionValue={eventType => eventType.id}
                                            options={this.state.eventTypes}/>
                                </div>
                            </div>

                            <div className="form-group row mb-0">
                                <div className="col-md-8 offset-md-4">
                                    <button type="submit" className="btn btn-primary">
                                        {id === undefined ? 'Добавить' : 'Изменить'}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        );
    }
}
