import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import {BrowserRouter, Route} from 'react-router-dom';
import {Router} from "./Router";

class Index extends Component {
    render() {
        return (
            <BrowserRouter>
                <Route component={Router}/>
            </BrowserRouter>
        );
    }
}

ReactDOM.render(<Index/>, document.getElementById('root'));