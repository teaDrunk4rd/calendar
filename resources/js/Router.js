import React, {Component} from 'react';
import {Route, Switch} from "react-router-dom";
import Calendar from "./components/Calendar";
import Login from "./components/Login";

export class Router extends Component {
    render() {
        return (
            <div className='row mx-5'>
                {this.props.children}
                <Switch>
                    <Route exact path="/calendar" component={Calendar}/>
                    <Route exact path="/login" component={Login}/>
                </Switch>
            </div>
        );
    }
}