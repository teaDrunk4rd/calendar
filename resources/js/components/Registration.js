import React, {Component} from 'react';

export default class Registration extends Component {
    constructor(props) {
        super(props);

        this.state = {
            email: '',
            password: '',
            fullName: ''
        };
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleSubmit(event) {
        event.preventDefault();

        axios.post('api/registration', {
            email: this.state.email,
            password: this.state.password,
            full_name: this.state.fullName
        }).then(response => {
            if (response.status === 201 && !response.data.message) {
                localStorage['user'] = JSON.stringify(response.data);
                this.props.history.push('/');
            }
        });
    }

    render() {
        const {email, password, fullName} = this.state;
        return (
            <div className="col-6 m-auto">
                <div className="card">
                    <div className="card-header">Регистрация</div>
                    <div className="card-body">
                        <form onSubmit={this.handleSubmit} autoComplete='false'>
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

                            <div className="form-group row">
                                <label className="col-md-4 col-form-label text-md-right">Пароль</label>
                                <div className="col-md-6">
                                    <input type="password"
                                           autoComplete="new-password"
                                           value={password}
                                           onChange={event => this.setState({password: event.target.value})}
                                           className="form-control "/>
                                </div>
                            </div>

                            <div className="form-group row mb-0">
                                <div className="col-md-8 offset-md-4">
                                    <button type="submit" className="btn btn-primary">
                                        Регистрация
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
