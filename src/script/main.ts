// //#region Classes
// class Message {
//     id: number;
//     uid: number;
//     time: number;
//     content: string;
// }
// class Chatlog {
//     messages: Message[];
//     view: HTMLElement;

//     constructor(view: HTMLElement) {
//         this.view = view;
//     }

//     push(message: Message): void {
//         this.messages.push(message);
//     }
//     render() {

//     }
// }

// //#region Utils
// let $ = function (selectors: string) {
//     return document.querySelector(selectors);
// };
// let $$ = function (selectors: string) {
//     return document.querySelectorAll(selectors);
// };
// async function validateToken(token: string): Promise<boolean> {
//     try {
//         // let res = await fetch(DOCROOT + '/api/validate.php', {
//         //     method: 'GET',
//         //     credentials: 'include'
//         // });
//         // if (res.ok) {
//         //     let data = await res.json();
//         //     return data.result === 'true' ? true : false;
//         // }
//         return false;
//     } catch (e) {
//         return false;
//     }
// }

// //#region Global Variables
// let token: string = null;
// let ws: WebSocket = null;
// let id_loginbg = $('#loginbg') as HTMLElement;

// //#region Global Events
// function pasteAsPlainText(e: any, element: HTMLElement): void {
//     e.preventDefault();

//     if (e.clipboardData) {
//         let content = (e.originalEvent || e).clipboardData.getData('text/plain');
//         document.execCommand('insertText', false, content);
//     }
// }

// //#region Instant Scripts
// document.addEventListener('DOMContentLoaded', async function () {
//     token = readCookie('token');

//     if (token != null) {
//         let valid = await validateToken(token);
//         if (valid) {
//             init();
//         } else {
//             showLogInDialog();
//         }
//     }
// });

// function init(): void {
//     pcInit();
//     wsInit();
// }
// function pcInit(): void {
//     // Public channel
// }
// function wsInit(): void {
//     ws = new WebSocket(`wss://${window.location.hostname}:36522`);
//     ws.onopen = wsOpen;
//     ws.onmessage = wsMessage;
//     ws.onerror = wsError;
//     ws.onclose = wsClose;
// }
// function wsOpen(event: any): void {
//     ws.send(token);
// }
// function wsMessage(event: any): void {
//     // TODO: Multi channel & private message
//     let data = JSON.parse(event.data);
// }
// function wsError(event: any): void {
//     //
// }
// function wsClose(event: any): void {
//     // Re-connect
// }

// function showLogInDialog(): void {
//     id_loginbg.hidden = false;
// }
