import React, {Component} from 'react';


export default class Calendar extends Component {
    constructor(props) {
        super(props);
        this.state = {
            events: [],
            eventTypes: {},
            dates: this.getDaysInMonth(10, 2020) // TODO: fix this
        };
    }

    getDaysInMonth(month, year) {
        let today = new Date();

        let dates = new Array(31)
            .fill('')
            .map(function (v, i) {
                return {
                    'date': new Date(year, month - 1, i + 1),
                    'status': year === today.getFullYear() && month - 1 === today.getMonth() && i + 1 === today.getDate() ? 'current' : 'active'
                }
            }).filter(v => v['date'].getMonth() === month - 1);

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

    componentDidMount() {
        axios.get('api/eventTypes').then(response => {
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
        axios.get('api/events').then(response => {
            if (response.status === 200) {
                this.setState({events: response.data});
            }
        });
    }

    render() {
        return (
            <div className='row mb-3'>
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
                                date['status'] === 'current' ? 'current-card' : ''}`
                            }>
                                <div className='card-body'>
                                    <h5 className='card-title'>{date['date'].getDate()}</h5>
                                    {date['status'] !== 'inactive' && this.state.events && this.state.events.filter(event =>
                                        event.type_id === this.state.eventTypes.EVERY_DAY ||
                                        event.type_id === this.state.eventTypes.EVERY_WEEK && event.day_of_week === date['date'].getDay() ||
                                        event.type_id === this.state.eventTypes.EVERY_MONTH && event.day_of_month === date['date'].getDate() ||
                                        event.type_id === this.state.eventTypes.EVERY_YEAR && event.day_of_month === date['date'].getDate() &&
                                            event.month_of_year === date['date'].getMonth()
                                    ).map((event, eventIndex) => {
                                        return (
                                            <div key={index + '' + eventIndex}
                                                className={`card-text row p-0 pl-2 mb-2 
                                                ${event.creator_id === JSON.parse(localStorage["user"]).id ? 'own-event' : ''}`}
                                                 title={`${event.hour_of_day}:00 — ${event.name}`}>
                                                <div className="col-4 p-0">{event.hour_of_day}:00 —</div>
                                                <div className="col-8 p-0 event-name">{event.name}</div>
                                            </div>
                                        )
                                    })}
                                </div>
                            </div>
                        </div>
                    )
                })}
            </div>
        );
    }
}
