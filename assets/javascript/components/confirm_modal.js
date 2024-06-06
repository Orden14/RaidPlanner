export function confirm(element, title, message) {
    $.confirm({
        icon: 'bi bi-exclamation-triangle-fill',
        theme: 'supervan',
        title: title,
        content: message,
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: 'Confirmer',
                action: () => {
                    $(element).trigger('submit')
                }
            },
            cancel: {
                text: 'Annuler'
            }
        }
    })
}