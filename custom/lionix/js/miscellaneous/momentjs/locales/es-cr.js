// Momentjs library downloaded from:
// https://momentjs.com/downloads/moment.js
// Locale downloaded from:
// https://raw.githubusercontent.com/moment/moment/develop/locale/es-do.js
// Locale github list (download raw files)
// https://github.com/moment/moment/tree/develop/locale
// These lines create the Costa Rica locale
moment.defineLocale('es-cr', {
    parentLocale: 'es-do',
    /* */
});
moment.updateLocale('es-cr', {
    calendar: {
        sameDay: function() {
            return '['+this.fromNow()+']'+', '+'[hoy a la' + ((this.hours() !== 1) ? 's' : '') + '] LT';
        },
        sameElse: 'LLLL'
    }
});
moment.locale('es-cr');
