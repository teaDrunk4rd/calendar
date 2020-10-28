import React, {Component} from 'react'
import Select from "react-select";
import DatePicker, { registerLocale } from "react-datepicker";
import ru from "date-fns/locale/ru";
registerLocale("ru", ru);
import {absoluteUrl} from '../Router'

export default class EventForm extends Component {
    constructor(props) {
        super(props);

        this.state = {
            name: '',
            description: '',
            date: '',
            typeId: '',
            eventTypes: [] //props.location.eventTypes
        };
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    componentDidMount() {
        axios.get(`${absoluteUrl}/api/eventTypes`).then(response => {
            if (response.status === 200) {
                this.setState({
                    eventTypes: response.data
                });
            }
        });
    }

    handleSubmit(event) {
        event.preventDefault();
        axios.post(`${absoluteUrl}/api/events/create`, {
            name: this.state.name,
            description: this.state.description,
            date: (this.state.date.getTime()-(this.state.date.getTimezoneOffset()*60000))/1000,
            type_id: this.state.typeId,
            creator_id: JSON.parse(localStorage["user"]).id
        })
            .then(response => {
                if (response.status === 201) {
                    this.props.history.push({
                        pathname: '/calendar',
                        date: this.state.date
                    });
                }
            });
    }

    render() {
        const {name, description, date, typeId} = this.state;
        return (
            <div className="col-6 m-auto">
                <div className="card">
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
                                <label className="col-md-4 col-form-label text-md-right">Дата</label>
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
                                        Добавить
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
