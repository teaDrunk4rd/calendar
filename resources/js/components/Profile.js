import React, {Component} from 'react';
import {NotificationManager} from 'react-notifications';
import Preloader from "./Preloader";


export default class Profile extends Component {
    constructor(props) {
        super(props);

        this.state = {
            email: '',
            fullName: '',
            changePasswordFlag: false,
            oldPassword: '',
            newPassword: '',
            passwordConfirmation: '',
            isLoaded: false
        };

        this.handleSubmit = this.handleSubmit.bind(this);
        this.logout = this.logout.bind(this);
    }

    componentDidMount() {
        axios.get('api/profiles').then(response => {
            if (response.status === 200) {
                this.setState({
                    email: response.data.email,
                    fullName: response.data.full_name,
                    isLoaded: true
                })
            }
        });
    }

    handleSubmit(event) {
        event.preventDefault();

        axios.put('api/profiles/update', {
            full_name: this.state.fullName,
            email: this.state.email,
            changePasswordFlag: this.state.changePasswordFlag,
            oldPassword: this.state.oldPassword,
            password: this.state.newPassword,
            password_confirmation: this.state.passwordConfirmation
        }).then(response => {
            if (response.status === 200 && !response.data.message) {
                localStorage['user'] = JSON.stringify(response.data);
                NotificationManager.success('Профиль успешно изменен');
                if (this.state.passwordConfirmation) {
                    this.setState({
                        changePasswordFlag: false,
                        oldPassword: '',
                        newPassword: '',
                        passwordConfirmation: ''
                    })
                }

            }
        });
    }

    logout(event) {
        event.preventDefault();
        axios.get('api/logout').then(response => {
            if (response.status === 200 && !response.data.message) {
                localStorage.clear();
                this.props.history.push('/login');
            }
        });
    }

    render() {
        const {email, fullName, changePasswordFlag, oldPassword, newPassword, passwordConfirmation} = this.state;
        return (
            <div className="col-6 m-auto">
                <div className="card">
                    {!this.state.isLoaded ? <Preloader /> : <div/>}
                    <div className="card-header">Профиль</div>
                    <div className="card-body">
                        <form onSubmit={this.handleSubmit} autoComplete='false'>
                            <div className="form-group d-flex justify-content-end col-10 pr-2">
                                <a href='#' onClick={this.logout}>
                                    Выйти
                                </a>
                            </div>

                            <div className="form-group row">
                                <label className="col-md-4 col-form-label text-md-right">E-Mail</label>
                                <div className="col-md-6">
                                    <input type="email"
                                           autoComplete="false"
                                           value={email}
                                           onChange={event => this.setState({email: event.target.value})}
                                           className="form-control "/>
                                </div>
                            </div>

                            <div className="form-group row">
                                <label className="col-md-4 col-form-label text-md-right">
                                    Полное имя
                                </label>
                                <div className="col-md-6">
                                    <input type="text"
                                           autoComplete="false"
                                           value={fullName}
                                           onChange={event => this.setState({fullName: event.target.value})}
                                           className="form-control "/>
                                </div>
                            </div>

                            {
                                changePasswordFlag ? (
                                    <div>
                                        <div className="form-group row">
                                            <label className="col-md-4 col-form-label text-md-right">Старый пароль</label>
                                            <div className="col-md-6">
                                                <input type="password"
                                                       autoComplete="new-password"
                                                       value={oldPassword}
                                                       onChange={event => this.setState({oldPassword: event.target.value})}
                                                       className="form-control "/>
                                            </div>
                                        </div>

                                        < div className = "form-group row" >
                                            < label className="col-md-4 col-form-label text-md-right">Новый пароль</label>
                                            <div className="col-md-6">
                                                <input type="password"
                                                       autoComplete="new-password"
                                                       value={newPassword}
                                                       onChange={event => this.setState({newPassword: event.target.value})}
                                                       className="form-control "/>
                                            </div>
                                        </div>

                                        <div className="form-group row">
                                            <label className="col-md-4 col-form-label text-md-right">Подтверждение пароля</label>
                                            <div className="col-md-6">
                                                <input type="password"
                                                       autoComplete="new-password"
                                                       value={passwordConfirmation}
                                                       onChange={event => this.setState({passwordConfirmation: event.target.value})}
                                                       className="form-control "/>
                                            </div>
                                        </div>
                                    </div>
                                ) : (

                                    <div className="form-group row">
                                        <div className="col-md-6 offset-md-4">
                                            <a href='#' onClick={event => this.setState({changePasswordFlag: !changePasswordFlag})}>
                                                Сменить пароль
                                            </a>
                                        </div>
                                    </div>
                                )
                            }

                            <div className="form-group row mb-0">
                                <div className="col-md-8 offset-md-4">
                                    <button type="submit" className="btn btn-primary">
                                        Изменить
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