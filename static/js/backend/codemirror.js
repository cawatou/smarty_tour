function initCodeMirror(_element, _width, _height, _lang)
{
    var
        _height = _height || '400px',
        _width  = _width || '100%',
        _lang   = _lang || 'ru';

    var myCodeMirror = CodeMirror.fromTextArea(document.getElementById(_element), {
        height: _height,
        width: _width,
        lineNumbers: true,               // показывать номера строк
        matchBrackets: true,             // подсвечивать парные скобки
        indentUnit: 4                    // размер табуляции
    });
}