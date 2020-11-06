import React, {Component} from 'react';
import {Link} from "react-router-dom";
import {NotificationManager} from "react-notifications";

export default class Login extends Component {
    constructor(props) {
        super(props);
        this.state = {
            email: '',
            password: '',
        };
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleSubmit(event) {
        event.preventDefault();

        if (this.state.email === '') {
            return NotificationManager.error('Заполните e-mail');
        } else if (this.state.password === '') {
            return NotificationManager.error('Заполните пароль');
        } else if (this.state.email.length > 255) {
            return NotificationManager.error('Слишком длинный email');
        } else if (this.state.password.length > 255) {
            return NotificationManager.error('Слишком длинный пароль');
        }

        axios.post('api/login', {
            email: this.state.email,
            password: this.state.password
        }).then(response => {
            if (response.status === 200 && !response.data.message) {
                localStorage['user'] = JSON.stringify(response.data);
                this.props.history.push('/');
            }
        });
    }

    render() {
        const {email, password} = this.state;
        return (
            <div className="col-6 m-auto">
                <div className="card">
                    <div className="card-header">Вход</div>
                    <div className="card-body">
                        <form onSubmit={this.handleSubmit}>
                            <div className="form-group row">
                                <label className="col-md-4 col-form-label text-md-right">E-Mail</label>
                                <div className="col-md-6">
                                    <input id="email" type="email" name="email"
                                           required="required"
                                           value={email}
                                           onChange={event => this.setState({email: event.target.value})}
                                           autoComplete="email" autoFocus="autofocus"
                                           className="form-control "/>
                                </div>
                            </div>

                            <div className="form-group row">
                                <label className="col-md-4 col-form-label text-md-right">Пароль</label>
                                <div className="col-md-6">
                                    <input id="password" type="password" name="password"
                                           required="required"
                                           value={password}
                                           onChange={event => this.setState({password: event.target.value})}
                                           autoComplete="current-password"
                                           className="form-control "/>
                                </div>
                            </div>

                            <div className="form-group d-flex justify-content-end col-10 pr-2">
                                <Link to="/registration">
                                    Регистрация
                                </Link>
                            </div>

                            <div className="form-group row mb-0">
                                <div className="col-md-8 offset-md-4">
                                    <button type="submit" className="btn btn-primary">
                                        Вход
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
