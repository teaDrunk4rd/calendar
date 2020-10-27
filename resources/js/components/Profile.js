import React, {Component} from 'react';


export default class Profile extends Component {
    constructor(props) {
        super(props);

        this.state = {
            email: '',
            password: '',
            fullName: ''
        };

        this.handleSubmit = this.handleSubmit.bind(this);
        this.logout = this.logout.bind(this);
    }

    componentDidMount() {
        axios.get('api/profile/' + JSON.parse(localStorage["user"]).id).then(response => {
            if (response.status === 200) {
                this.setState({
                    email: response.data.email,
                    fullName: response.data.full_name
                })
            }
        });
    }

    handleSubmit(event) {
        event.preventDefault();
        axios.put('api/profile/update', {
            id: JSON.parse(localStorage["user"]).id,
            email: this.state.email,
            password: this.state.password,
            full_name: this.state.fullName
        }).then(response => {
            if (response.status === 200) {
                localStorage['user'] = JSON.stringify(response.data);
                this.props.history.push('/calendar');
            }
        });
    }

    logout(event) {
        event.preventDefault();
        localStorage.clear();
        this.props.history.push('/login');
    }

    render() {
        const {email, password, fullName} = this.state;
        return (
            <div className="col-6 m-auto">
                <div className="card">
                    <div className="card-header">Профиль</div>
                    <div className="card-body">
                        <form onSubmit={this.handleSubmit} autoComplete='false'>
                            <div className="form-group d-flex justify-content-end col-10 pr-2">
                                <button onClick={this.logout} className='btn btn-primary'>
                                    Выйти
                                </button>
                            </div>

                            <div className="form-group row">
                                <label className="col-md-4 col-form-label text-md-right">E-Mail</label>
                                <div className="col-md-6">
                                    <input type="text"
                                           autoComplete="false"
                                           required="required"
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