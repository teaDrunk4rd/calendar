import React, {Component} from 'react';
import {Route, Switch} from "react-router-dom";
import Calendar from "./components/Calendar";
import Login from "./components/Login";
import PrivateRoute from "./components/PrivateRoute";
import Profile from "./components/Profile";
import {NavMenu} from "./components/NavMenu";
import Registration from "./components/Registration";

export class Router extends Component {
    render() {
        return (
            <div>
                <NavMenu/>
                <div className='row mx-5'>
                    {this.props.children}
                    <Switch>
                        <Route exact path="/login" component={Login}/>
                        <Route exact path="/registration" component={Registration}/>
                        <PrivateRoute exact path="/calendar" component={Calendar}/>
                        <PrivateRoute exact path="/profile" component={Profile}/>
                    </Switch>
                </div>
            </div>
        );
    }
}