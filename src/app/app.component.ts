import { Component, OnInit } from '@angular/core';

import { SessionService } from './session.service';

@Component({
    selector: 'my-app',
    templateUrl: './app.component.html'
})
export class AppComponent implements OnInit {
    constructor(public sessionService: SessionService) { }

    ngOnInit(): void {

    }
}
