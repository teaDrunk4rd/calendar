import {Redirect, Route, withRouter} from 'react-router-dom';
import React, {Component} from 'react';


const PrivateRoute = ({component: Component, path, ...rest}) => (
    <Route path={path}
           {...rest}
           render={props => localStorage["user"] ? (<Component {...props} />) : (<Redirect to='/login'/>)}
    />);
export default withRouter(PrivateRoute);