import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';

import { MaterialModule } from '@angular/material';

import { AppComponent } from './app.component';
import { LogInComponent } from './login.component';
import { SessionService } from './session.service';

@NgModule({
    imports: [
        BrowserModule,
        FormsModule,
        HttpModule,
        MaterialModule
    ],
    declarations: [
        AppComponent,
        LogInComponent
    ],
    bootstrap: [AppComponent],
    providers: [
        SessionService
    ]
})
export class AppModule { }
