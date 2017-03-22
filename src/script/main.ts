function pasteAsPlainText(event, element: HTMLElement) {
    event.preventDefault();

    if (event.clipboardData) {
        let content = (event.originalEvent || event).clipboardData.getData('text/plain');
        document.execCommand('insertText', false, content);
    }
}