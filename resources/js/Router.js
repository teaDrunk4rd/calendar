import React, {Component} from 'react';
import {Route, Switch} from "react-router-dom";
import PrivateRoute from "./components/PrivateRoute";
import NavMenu from "./components/NavMenu";
import Login from "./components/Login";
import Registration from "./components/Registration";
import Profile from "./components/Profile";
import Calendar from "./components/Calendar";
import Event from "./components/Event";
import EventForm from "./components/EventForm";

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
                        <PrivateRoute exact path="/profile" component={Profile}/>
                        <PrivateRoute exact path="/" component={Calendar}/>
                        <PrivateRoute exact path="/event" component={Event}/>
                        <PrivateRoute exact path="/eventForm" component={EventForm}/>
                    </Switch>
                </div>
            </div>
        );
    }
}