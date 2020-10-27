import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import {BrowserRouter, Route} from 'react-router-dom';
import {Router} from "./Router";
import {NavMenu} from "./components/NavMenu";

class Index extends Component {
    render() {
        return (
            <BrowserRouter>
                <NavMenu/>
                <Route component={Router}/>
            </BrowserRouter>
        );
    }
}

ReactDOM.render(<Index/>, document.getElementById('root'));