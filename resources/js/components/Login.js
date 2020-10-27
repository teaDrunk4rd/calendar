import React, {Component} from 'react';

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
        //sessionStorage.setItem('userLogin', 'userData');
        const response = axios.post('api/login', {
            email: this.state.email,
            password: this.state.password
        });
        console.log(response.data);
    }

    render() {
        const {email, password} = this.state;
        return (
            <div className="col-md-8">
                <div className="card">
                    <div className="card-header">Вход</div>
                    <div className="card-body">
                        <form onSubmit={this.handleSubmit}>
                            <div className="form-group row">
                                <label htmlFor="email"
                                       className="col-md-4 col-form-label text-md-right">E-Mail</label>
                                <div className="col-md-6">
                                    <input id="email" type="email" name="email"
                                           required="required"
                                           value={email}
                                           onChange={event => this.setState({email: event.target.value})}
                                           autoComplete="email" autoFocus="autofocus"
                                           className="form-control "/></div>
                            </div>
                            <div className="form-group row">
                                <label htmlFor="password"
                                       className="col-md-4 col-form-label text-md-right">Пароль</label>
                                <div className="col-md-6">
                                    <input id="password" type="password" name="password"
                                           required="required"
                                           value={password}
                                           onChange={event => this.setState({password: event.target.value})}
                                           autoComplete="current-password"
                                           className="form-control "/></div>
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
