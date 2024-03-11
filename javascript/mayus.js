/* function upperCase(e){
    e.value = e.value.toUpperCase();
} */

var cursorPosition = 0;

function upperCase(e){
    e.value = e.value.toUpperCase();
}

function rememberCursorPosition(e) {
    if (e.selectionStart || e.selectionStart == '0') {
        cursorPosition = e.selectionStart;
    }
}

function restoreCursorPosition(e) {
    e.setSelectionRange(cursorPosition, cursorPosition);
}