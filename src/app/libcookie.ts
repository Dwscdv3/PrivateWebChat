export class Cookie {
    static set(name: string, value: string, days: number): void {
        let expires: string;
        if (days) {
            let date = new Date();
            date.setTime(date.getTime() + (days * 86400000));
            expires = '; expires=' + date.toUTCString();
        } else {
            expires = '';
        };
        document.cookie = name + '=' + value + expires + '; path=/';
    }
    static get(name: string): string {
        let nameEQ = name + '=';
        let ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') {
                c = c.substring(1, c.length);
            };
            if (c.indexOf(nameEQ) === 0) {
                return c.substring(nameEQ.length, c.length);
            };
        }
        return null;
    }
    static delete(name: string): void {
        this.set(name, '', -1);
    }
}
