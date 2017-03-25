//#region LibCookie http://www.quirksmode.org/js/cookies.html
function createCookie(name: string, value: string, days: number): void {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 86400000));
        var expires = "; expires=" + date.toUTCString();
    }
    else var expires = "";
    document.cookie = name + "=" + value + expires + "; path=/";
}
function readCookie(name: string): string {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}
function eraseCookie(name: string): void {
    createCookie(name, "", -1);
}

//#region Utils
let $ = function (selectors: string) {
    return document.querySelector(selectors);
}
let $$ = function (selectors: string) {
    return document.querySelectorAll(selectors);
}
async function validateToken(token: string): Promise<boolean> {
    try {
        let res = await fetch(DOCROOT + "/api/validate.php", {
            method: "GET",
            credentials: "include"
        });
        if (res.ok) {
            let data = await res.json();
            return data.result === "true" ? true : false;
        }
        return false;
    } catch (e) {
        return false;
    }
}

//#region Classes
class Message {
    id: number;
    uid: number;
    time: number;
    content: string;
}
class Chatlog {
    messages: Message[];
    view: HTMLElement;

    constructor(view: HTMLElement) {
        this.view = view;
    }

    push(message: Message): void {
        this.messages.push(message);
    }
    render() {

    }
}

//#region Global Constants
const DOCROOT: string = "/";

//#region Global Variables
let token = null;
let ws: WebSocket = null;
let id_loginbg = $("#loginbg") as HTMLElement;

//#region Global Events
function pasteAsPlainText(e, element: HTMLElement): void {
    e.preventDefault();

    if (e.clipboardData) {
        let content = (e.originalEvent || e).clipboardData.getData('text/plain');
        document.execCommand('insertText', false, content);
    }
}

//#region Instant Scripts
document.addEventListener("DOMContentLoaded", async function () {
    let token: string = readCookie("token");

    if (token != null) {
        let valid = await validateToken(token);
        if (valid) {
            init();
        } else {
            showLogInDialog();
        }
    }
});

function init(): void {
    pcInit();
    wsInit();
}
function pcInit(): void {
    // Public channel
}
function wsInit(): void {
    ws = new WebSocket(`wss://${window.location.hostname}:36522`);
    ws.onopen = wsOpen;
}
function wsOpen(event: any): void {
    ws.send(token);
}
function wsMessage(event: any): void {
    // TODO: Multi channel & private message
    JSON.parse(event.data);
}
function wsError(event: any): void {
    //
}
function wsClose(event: any): void {
    // Re-connect
}

function showLogInDialog(): void {
    id_loginbg.hidden = false;
}