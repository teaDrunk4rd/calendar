import React, {Component} from 'react';
import {Link} from "react-router-dom";
import DatePicker, { registerLocale } from "react-datepicker";
import ru from "date-fns/locale/ru";
import Preloader from "./Preloader";
registerLocale("ru", ru);


export default class Calendar extends Component {
    constructor(props) {
        super(props);

        let currentDate = props.location.date === undefined ? new Date() : props.location.date;
        this.state = {
            events: [],
            eventTypes: {},
            date: currentDate,
            dates: this.getDaysInMonth(currentDate.getMonth(), currentDate.getFullYear()),
            isLoaded: false
        };
    }

    componentDidMount() {
        this.updateEvents(this.state.date);

        axios.get('api/event_types').then(response => {
            if (response.status === 200) {
                this.setState({
                    eventTypes: {
                        EVERY_DAY: response.data.filter(t => t['key'] === 'every_day')[0].id,
                        EVERY_WEEK: response.data.filter(t => t['key'] === 'every_week')[0].id,
                        EVERY_MONTH: response.data.filter(t => t['key'] === 'every_month')[0].id,
                        EVERY_YEAR: response.data.filter(t => t['key'] === 'every_year')[0].id,
                    }
                });
            }
        });
    }

    updateEvents(date) {
        axios.get(`api/events/${(date.getTime() - (date.getTimezoneOffset() * 60000)) / 1000}`).then(response => {
            if (response.status === 200) {
                response.data.forEach(function (event) {
                    event.date = new Date(event.date);
                    event.closed_at = event.closed_at != null ? new Date(event.closed_at) : null;
                });
                this.setState({events: response.data, isLoaded: true});
            }
        });
    }

    getDaysInMonth(month, year) {
        let today = new Date();

        let dates = new Array(31)
            .fill('')
            .map(function (v, i) {
                return {
                    'date': new Date(year, month, i + 1),
                    'status': year === today.getFullYear() && month === today.getMonth() && i + 1 === today.getDate()
                        ? 'current'
                        : 'active'
                }
            }).filter(v => v['date'].getMonth() === month);

        while (dates[0]['date'].getDay() !== 1) {
            let date = dates[0]['date'];
            dates.splice(0, 0, {
                'date': new Date(date.getFullYear(), date.getMonth(), date.getDate() - 1),
                'status': 'inactive'
            });
        }

        while (dates[dates.length - 1]['date'].getDay() !== 0) {
            let date = dates[dates.length - 1]['date'];
            dates.push({
                'date': new Date(date.getFullYear(), date.getMonth(), date.getDate() + 1),
                'status': 'inactive'
            });
        }

        return dates;
    }

    setDate(date) {
        this.updateEvents(date);
        this.setState({
            date: date,
            dates: this.getDaysInMonth(date.getMonth(), date.getFullYear())
        });
    }

    render() {
        return (
            <div className='row mb-3'>
                <div className="col-12 p-0 my-2 d-flex justify-content-between">
                    <DatePicker
                        selected={this.state.date}
                        onChange={date => this.setDate(date)}
                        dateFormat="MM.yyyy"
                        locale="ru"
                        showMonthYearPicker
                        showFullMonthYearPicker
                    />
                    <Link to="/eventForm" className='btn btn-success'>
                        Добавить событие
                    </Link>
                </div>
                <table className='table'>
                    <thead className='calendar-header'>
                    <tr>
                        <th>Пн</th>
                        <th>Вт</th>
                        <th>Ср</th>
                        <th>Чт</th>
                        <th>Пт</th>
                        <th>Сб</th>
                        <th>Вс</th>
                    </tr>
                    </thead>
                </table>
                {this.state.dates && this.state.dates.map((date, index) => {
                    return (
                        <div className='calendar-item d-flex align-items-stretch' key={index}>
                            <div className={`card w-100 
                                ${date['status'] === 'inactive' ? 'inactive-card' :
                                date['status'] === 'current' ? 'bg-primary text-white' : ''}`
                            }>
                                <div className='card-body'>
                                    <h5 className='card-title'>{date['date'].getDate()}</h5>
                                    {date['status'] !== 'inactive' && this.state.events && this.state.events.filter(event =>
                                        (event.type_id === this.state.eventTypes.EVERY_DAY ||
                                            event.type_id === this.state.eventTypes.EVERY_WEEK && event.day_of_week === date['date'].getDay() ||
                                            event.type_id === this.state.eventTypes.EVERY_MONTH && event.day_of_month === date['date'].getDate() ||
                                            event.type_id === this.state.eventTypes.EVERY_YEAR && event.day_of_month === date['date'].getDate() &&
                                            event.month_of_year - 1 === date['date'].getMonth()) &&
                                        (new Date(event.date.getFullYear(), event.date.getMonth(), event.date.getDate()) <= date['date'])
                                        && (event.closed_at === null || event.closed_at >= date['date'])
                                    ).map((event, eventIndex) => {
                                        return (
                                            <Link key={index + '' + eventIndex}
                                                  to={event.creator_id === JSON.parse(localStorage["user"]).id
                                                      ? {pathname: '/eventForm', id: event.id}
                                                      : {pathname: '/event', id: event.id}}
                                                  className={`card-text row p-0 pl-2 mb-2
                                                ${event.creator_id === JSON.parse(localStorage["user"]).id ? 'own-event' :
                                                      date['status'] !== 'current' ? 'text-dark' : 'text-white'}`}
                                                  title={`${event.hour_of_day}:00 — ${event.name}`}>
                                                <div className="col-4 p-0">{event.hour_of_day}:00 —</div>
                                                <div className="col-8 p-0 event-name">{event.name}</div>
                                            </Link>
                                        )
                                    })}
                                </div>
                            </div>
                        </div>
                    )
                })}
                {!this.state.isLoaded ? <Preloader /> : <div/>}
            </div>
        );
    }
}
