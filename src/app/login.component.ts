import { Component, Injectable } from '@angular/core';
import { Http } from '@angular/http';
import 'rxjs/add/operator/toPromise';

import './constants';
import { SessionService } from './session.service';

@Injectable()
@Component({
    selector: 'login',
    templateUrl: './login.component.html',
    styleUrls: ['./login.component.css']
})
export class LogInComponent {
    username?: string = '';
    password?: string = '';
    private isLoggingIn = false;

    constructor(private sessionService: SessionService, private http: Http) { }

    isValid(): boolean {
        return this.username.length > 0 && this.password.length > 0;
    }

    async login() {
        if (!this.isLoggingIn && this.isValid()) {
            try {
                let res = await this.http.post(`${REQUEST_ROOT}/api/login.php`, {
                    username: this.username,
                    password: this.password
                }).toPromise();
                let data = res.json();
                if (data.status === '1') {
                    this.sessionService.setToken(data.token);
                }
            } catch (ex) {
                console.error(ex);
            } finally {
                this.isLoggingIn = false;
            }
        }
    }
}
