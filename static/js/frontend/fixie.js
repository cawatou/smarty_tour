function fixie() {
    if (!/msie/.test(navigator.userAgent.toLowerCase())) {
        return false;
    }    
    
    // in this place make fixes
}
$(document).ready(function() {
    fixie();
});