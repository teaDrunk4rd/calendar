window._ = require('lodash');
import {NotificationManager} from "react-notifications";
import {createHashHistory} from 'history'

const history = createHashHistory()


try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {
}


window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

let errorMessages = {
    auth: "Требуется авторизация",
    forbidden: "Доступ запрещён",
    default: "Произошла ошибка"
}

window.axios.interceptors.response.use(response => {
    return response;
}, error => {
    if (error.response.data.errors) {  // validation rule error
        NotificationManager.error(error.response.data.errors[Object.keys(error.response.data.errors)[0]][0]);
    } else if (error.response.status === 403) {
        NotificationManager.error(error.response.data.message || errorMessages.forbidden);
    } else if (error.response.status === 401 &&
        !NotificationManager.listNotify.find(x => x.message === errorMessages.auth)) {
        localStorage.clear();
        history.push('/login');
        NotificationManager.error(error.response.data.message || errorMessages.auth);
    } else if (!NotificationManager.listNotify.find(x => x.message === errorMessages.default)) {
        NotificationManager.error(error.response.data.message || errorMessages.default);
    }

    return Promise.reject(error);
})
