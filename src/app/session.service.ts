import { Injectable } from '@angular/core';
import { Http } from '@angular/http';

import { Cookie } from './libcookie';

@Injectable()
export class SessionService {
    token: string = null;
    inited: boolean = false;

    constructor(private http: Http) {
        this.initFromCookie();
    }

    initFromCookie(): void {
        let _token = Cookie.get('token');
        if (_token != null && _token.length === 32) {
            this.http.get('/api/validate.php', { withCredentials: true }).toPromise()
                .then(response => {
                    this.token = _token;
                    this.inited = true;
                }).catch(reason => {
                    this.inited = true;
                });
        } else {
            this.inited = true;
        }
    }

    setToken(token: string): void {
        this.token = token;
        Cookie.set('token', token, 7);
    }
    removeToken(): void {
        this.token = null;
    }
}
