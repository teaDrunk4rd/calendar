window._ = require('lodash');
import {NotificationManager} from "react-notifications";
import { createHashHistory } from 'history'
const history = createHashHistory()


try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}


window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

window.axios.interceptors.response.use(response => { return response; }, error => {
    if (error.response.data.errors) {
        NotificationManager.error(error.response.data.errors[Object.keys(error.response.data.errors)[0]][0]);
    } else if (error.response.status === 403) {
        NotificationManager.error("Доступ запрещён");
    } else if (error.response.status === 401 && !NotificationManager.listNotify.find(x => x.message === "Требуется авторизация")) {
        localStorage.clear();
        history.push('/login');
        NotificationManager.error("Требуется авторизация");
    } else if (!NotificationManager.listNotify.find(x => x.message === "Произошла ошибка")) {
        NotificationManager.error("Произошла ошибка");
    }

    return Promise.reject(error);
})
